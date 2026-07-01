<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Project;
use App\Models\Panorama;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->selectedMainMenu = 'vr_tour';
        $this->selectedSubMenu('content');
        parent::__construct();

        if (!Gate::allows('vr_tour/content')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index()
    {
        $vrtour     = Project::all();
        return view('backend.vrtour.content.index', compact('vrtour'));
    }

    public function getDataAll(Request $request, $vrtour_id)
    {
        $vrtour      = Project::find($vrtour_id);
        $user = auth()->user();
        $link_vrtour = $vrtour->link_vrtour;
        $panorama    = Panorama::visibleFor($user)->where('vrtour_id', $vrtour_id)->orderByDesc('id')->get();

        if ($request->reset == 'true' || $panorama->isEmpty()) {
            $pano = getDataVrtour($link_vrtour . 'vista3d/pano.json');
            if (empty($pano)) {
                return response()->json([
                    'data' => 'Hiện tại data pano không tồn tại'
                ]);
            }
            $dbPanoramas = $panorama->keyBy('title');
            $jsonTitles  = [];
            foreach ($pano as $pn) {
                $title = $pn['title'];
                $jsonTitles[] = $title;

                if (isset($dbPanoramas[$title])) {
                    $dbPanoramas[$title]->update([
                        'ids' => json_encode($pn['ids']),
                        'label_audio'=> $pn['label_audio'] ?? null,
                    ]);
                } else {
                    Panorama::create([
                        'vrtour_id' => $vrtour_id,
                        'label_audio'=> $pn['label_audio'] ?? null,
                        'ids'       => json_encode($pn['ids']),
                        'title'     => $title,
                        'user_id'   => $user->id
                    ]);
                }
            }

            if ($request->reset == 'true') {
                Panorama::where('vrtour_id', $vrtour_id)->whereNotIn('title', $jsonTitles)->delete();
            }

            $panorama = Panorama::where('vrtour_id', $vrtour_id)->where('is_draft', 0)->get();
            createFile('vrtour/' . $vrtour->vrtour_code, 'pano.js');
            file_put_contents(
                'vrtour/' . $vrtour->vrtour_code . '/pano.js',
                $panorama
            );
        }
        $html = '';
        foreach ($panorama as $key => $pn) {
             $statusHtml = '';
            if ($pn->is_draft) {
                $statusHtml .= "<span class='badge bg-warning'>Bản chỉnh sửa</span> ";
            }

            if ($pn->status === 'pending') {
                if ($pn->approval_level == 0) {
                    $statusHtml .= "<span class='badge bg-secondary'>Chờ duyệt cấp 1</span>";
                } elseif ($pn->approval_level == 1) {
                    $statusHtml .= "<span class='badge bg-primary'>Chờ duyệt cấp 2</span>";
                }
            } elseif ($pn->status === 'approved') {
                $statusHtml .= "<span class='badge bg-success'>Đã duyệt</span>";
            } elseif ($pn->status === 'rejected') {
                $statusHtml .= "<span class='badge bg-danger'>Từ chối</span>";
            }
            $content = preg_replace('/<!--.*?-->/s', '', $pn->content);
            $html .= '
            <tr>
                <td>' . (++$key) . '</td>
                <td>' . $pn->title . '</td>
                <td>' . mb_substr($content, 0, 100, "UTF-8") . '...</td>
                <td>' . $statusHtml . '</td>
                <td class="grid_row1">
                    <a class="btn btn-info btn-sm mr-1"
                        href="' . route('backend_vrtour_content_edit', $pn->id) . '"
                        title="Chỉnh sửa">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </td>
            </tr>
        ';
        }

        return response()->json([
            'data' => $html
        ]);
    }

    public function edit($id)
    {
        if (!Gate::allows('vr_tour/content')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $pano   = Panorama::findorFail($id);
        return view('backend.vrtour.content.edit', compact(['pano']));
    }

    public function store(Request $request, $id)
    {
        if (!Gate::allows('vr_tour/content')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate([
            'ct_title'   => 'required',
            'ct_content' => 'required',
            'ct_audio'   => 'required',
        ]);

        $user = Auth::user();

        $panorama = Panorama::findOrFail($id);

        // Super Admin sửa trực tiếp
        if ($user->is_super_admin) {
            $main = $panorama->parent_id
                ? Panorama::findOrFail($panorama->parent_id)
                : $panorama;

            $main->title      = $request->ct_title;
            $main->title_en   = $request->ct_title_en;
            $main->content    = $request->ct_content;
            $main->content_en = $request->ct_content_en;
            $main->audio      = $request->ct_audio;
            $main->audio_en   = $request->ct_audio_en;
            $main->user_id    = $user->id;

            $main->approval_level = $main->max_approval;
            $main->status         = 'approved';
            $main->is_draft       = 0;
            $main->parent_id      = null;

            $main->save();

            Panorama::where('parent_id', $main->id)->delete();

            file_put_contents(
                'vrtour/' . Project::find($main->vrtour_id)->vrtour_code . '/pano.js',
                Panorama::where('vrtour_id', $main->vrtour_id)
                    ->where('is_draft', 0)
                    ->get()
            );

            return redirect()
                ->to(
                    route('backend_vrtour_content_index')
                        . '?vrtour=' . $main->vrtour_id
                )
                ->with('success', 'Đã duyệt và cập nhật nội dung');
        }

        // User thường hoặc user duyệt cấp 1
        if (!$panorama->is_draft) {

            $draft = $panorama->replicate();

            $draft->parent_id = $panorama->id;
            $draft->is_draft = 1;
            $draft->status = 'pending';
            $draft->approval_level = $user->is_approve ? 1 : 0;
        } else {
            $draft = $panorama;
            $draft->status = 'pending';
            $draft->approval_level = $user->is_approve ? 1 : 0;
        }

        $draft->title      = $request->ct_title;
        $draft->title_en   = $request->ct_title_en;
        $draft->content    = $request->ct_content;
        $draft->content_en = $request->ct_content_en;
        $draft->audio      = $request->ct_audio;
        $draft->audio_en   = $request->ct_audio_en;
        $draft->user_id    = $user->id;

        $draft->save();

        return redirect()
            ->to(
                route('backend_vrtour_content_index')
                    . '?vrtour=' . $draft->vrtour_id
            )
            ->with(
                'success',
                $user->is_approve
                    ? 'Đã gửi duyệt cấp 2'
                    : 'Đã gửi duyệt cấp 1'
            );
    }
    public function reject(Panorama $content)
    {
        $user = auth('web')->user();
        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền từ chối panorama.');
        }
        $content->delete();
        return redirect()->route('backend_vrtour_content_index')->with(
            'success',
            'Từ chối duyệt panorama thành công'
        );
    }
    public function approve(Panorama $content)
    {
        $user = auth('web')->user();

        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền duyệt nội dung.');
        }
        // Duyệt cấp 2 (Super Admin)
        if ($user->is_super_admin) {
            if ($content->parent_id) {
                $parent = Panorama::find($content->parent_id);
                if ($parent) {
                    DB::transaction(function () use ($content, $parent) {
                        $draftData = $content->toArray();
                        unset(
                            $draftData['id'],
                            $draftData['parent_id'],
                            $draftData['created_at'],
                            $draftData['updated_at']
                        );

                        $parent->fill($draftData);
                        $parent->parent_id = null;
                        $parent->is_draft = 0;
                        $parent->status = 'approved';
                        $parent->approval_level = $parent->max_approval;

                        $parent->save();
                        $project = Project::find($parent->vrtour_id);
                        file_put_contents(
                            'vrtour/' . $project->vrtour_code . '/pano.js',
                            Panorama::where('vrtour_id', $parent->vrtour_id)
                                ->where('is_draft', 0)
                                ->get()
                        );
                        // Xóa bản draft
                        $content->delete();
                    });

                    return redirect()
                        ->route('backend_vrtour_content_index')
                        ->with('success', 'Duyệt nội dung thành công.');
                }
            }
        }

        // Duyệt cấp 1
        if ($user->is_approve) {
            if ($content->approval_level < 1) {
                $content->approval_level = 1;
                $content->status = 'pending';
                $content->save();
            }
            return redirect()->route('backend_vrtour_content_index')->with('success', 'Đã duyệt cấp 1, chờ duyệt cấp 2.');
        }
    }
}
