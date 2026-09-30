<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestDashboardController extends Controller
{
    public function index(Request $request)
    {
        $guest = auth('guest')->user();
        if (!$guest) {
            return redirect()->route('home_page');
        }

        // 1. Danh sách bookmark (Dự án quan tâm & Tin tức yêu thích)
        $bookmarks = $guest->interests()
            ->with('interestable')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Các dự án bookmark vừa cập nhật (ví dụ trong 7 ngày qua)
        $updatedBookmarks = $bookmarks->filter(function($interest) {
            return $interest->interestable && $interest->interestable->updated_at >= now()->subDays(7);
        });

        // 3. Gợi ý dự án theo lĩnh vực (interested_sectors)
        $sectors = $guest->interested_sectors ?? [];
        $suggestions = collect();
        if (!empty($sectors)) {
            $suggestions = \App\Models\Project::whereIn('industry_number', $sectors)
                ->latest()
                ->take(6)
                ->get();
        }

        // 4. Lịch sử tra cứu (90 ngày)
        $viewHistories = $guest->viewHistories()
            ->with('project')
            ->orderBy('viewed_at', 'desc')
            ->take(20)
            ->get();

        $layout = $guest->dashboard_layout ?? [];

        // --- NEW SLIDER DATA ---
        $industries = \App\Models\ProjectIndustries::all()->map(fn($industry) => [
            'id' => $industry->id,
            'name' => $industry->name,
        ])->toArray();

        $interestQuery = $guest->interests()
            ->where('interestable_type', \App\Models\Project::class)
            ->with([
                'interestable' => function ($q) {
                    $q->withRelations();
                }
            ]);

        $list_project_interest = $interestQuery->get()
            ->map(function ($item) {
                $project = $item->interestable;
                if ($project) {
                    $project->is_interested = true;
                    $project->interest_id = $item->id;
                    $project->interest_notes = $item->notes;
                    $project->interest_tag = $item->tag;
                    return $project;
                }
                return null;
            })->filter();

        if ($request->industry) {
            $list_project_interest = $list_project_interest->filter(function ($project) use ($request) {
                return $project->industry_number == $request->industry;
            });
        }

        if ($request->keyword) {
            $keyword = mb_strtolower($request->keyword);
            $list_project_interest = $list_project_interest->filter(function ($project) use ($keyword) {
                return str_contains(mb_strtolower($project->name), $keyword);
            });
        }

        $list_project_interest = $list_project_interest->values();

        $list_post_interest = $guest->interests()
            ->where('interestable_type', \App\Models\Post::class)
            ->with('interestable')
            ->get()
            ->map(function ($item) {
                $post = $item->interestable;
                if ($post) {
                    return [
                        'id' => $post->id,
                        'name' => $post->name,
                        'slug' => $post->slug,
                        'image' => $post->image,
                        'published_at' => $post->published_at,
                        'description' => $post->description,
                        'is_interested' => true,
                        'interest_id' => $item->id,
                        'interest_notes' => $item->notes,
                        'interest_tag' => $item->tag,
                    ];
                }
                return null;
            })->filter()->values();

        return view('frontend.guest.dashboard', compact(
            'bookmarks', 'updatedBookmarks', 'suggestions', 'viewHistories', 'layout',
            'list_project_interest', 'list_post_interest', 'industries'
        ));
    }

    public function updateLayout(Request $request)
    {
        $guest = auth('guest')->user();
        if (!$guest) {
            return response()->json(['success' => false], 401);
        }

        $guest->dashboard_layout = $request->input('layout');
        $guest->save();

        return response()->json(['success' => true]);
    }
    
    public function exportBookmarks(Request $request)
    {
        $guest = auth('guest')->user();
        if (!$guest) {
            return redirect()->route('home_page');
        }

        $bookmarks = $guest->interests()
            ->with('interestable')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [];
        $stt = 1;
        foreach ($bookmarks as $b) {
            if (!$b->interestable) continue;

            $type = '';
            $sector = '';
            $location = '';

            if ($b->interestable_type == 'App\Models\Project') {
                $type = 'Dự án';
                $sector = $b->interestable->industry->name ?? '';
                $location = $b->interestable->districts->pluck('name')->join(', ');
            } elseif ($b->interestable_type == 'App\Models\Post') {
                $type = 'Tin tức';
                $sector = $b->interestable->category->name ?? '';
                $location = '';
            }

            $data[] = [
                $stt++,
                $type,
                $b->interestable->name ?? $b->interestable->title ?? '',
                $sector,
                $location,
                $b->tag,
                $b->notes,
                $b->created_at->format('d/m/Y H:i'),
            ];
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BookmarksExport($data), 'danh-sach-quan-tam.xlsx');
    }

    public function createCompareSession(Request $request)
    {
        $guest = auth('guest')->user();
        if (!$guest) return redirect()->route('home_page');

        $projectIds = $request->input('project_ids', []);
        
        if (count($projectIds) < 2 || count($projectIds) > 4) {
            return back()->with('error', 'Chỉ so sánh được từ 2 đến tối đa 04 dự án cùng lúc.');
        }

        $session = \App\Models\CompareSession::create([
            'guest_id' => $guest->id,
            'name' => 'Phiên so sánh ' . now()->format('d/m/Y H:i'),
            'project_ids' => $projectIds
        ]);

        return redirect()->route('guest.compare.show', $session->id);
    }

    public function showCompareSession($id)
    {
        $guest = auth('guest')->user();
        if (!$guest) return redirect()->route('home_page');

        $session = \App\Models\CompareSession::where('id', $id)->where('guest_id', $guest->id)->firstOrFail();
        
        $projects = \App\Models\Project::with(['industry', 'districts', 'type'])
            ->whereIn('id', $session->project_ids)
            ->get();
            
        // Maintain original order
        $projects = $projects->sortBy(function($model) use ($session) {
            return array_search($model->id, $session->project_ids);
        })->values();

        $notes = $session->notes->groupBy('project_id')->map(function($projectNotes) {
            return $projectNotes->keyBy('criterion_key');
        });

        return view('frontend.guest.compare', compact('session', 'projects', 'notes'));
    }

    public function saveCompareNotes(Request $request, $id)
    {
        $guest = auth('guest')->user();
        if (!$guest) return response()->json(['success' => false], 401);

        $session = \App\Models\CompareSession::where('id', $id)->where('guest_id', $guest->id)->firstOrFail();
        
        if ($request->has('name')) {
            $session->update(['name' => $request->name]);
        }

        $notes = $request->input('notes', []);
        foreach ($notes as $projectId => $criteria) {
            foreach ($criteria as $criterionKey => $noteText) {
                \App\Models\CompareNote::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'project_id' => $projectId,
                        'criterion_key' => $criterionKey,
                    ],
                    [
                        'note' => $noteText
                    ]
                );
            }
        }

        return response()->json(['success' => true]);
    }

    public function exportComparePdf(Request $request, $id)
    {
        $guest = auth('guest')->user();
        if (!$guest) abort(401);
        
        $session = \App\Models\CompareSession::where('id', $id)->where('guest_id', $guest->id)->firstOrFail();
        
        $projects = \App\Models\Project::with(['industry', 'districts', 'type'])
            ->whereIn('id', $session->project_ids)
            ->get();
            
        // Map notes
        $savedNotes = \App\Models\CompareNote::where('session_id', $session->id)->get();
        $notes = [];
        foreach ($savedNotes as $n) {
            $notes[$n->project_id][$n->criterion_key] = $n->note;
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('frontend.guest.compare_pdf', compact('session', 'projects', 'notes'));
        // Load custom font or default font config if needed, dompdf supports UTF-8 with correct fonts like DejaVu Sans.
        
        return $pdf->download('SoSanhDuAn_' . now()->format('YmdHi') . '.pdf');
    }
}
