<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Project;
use App\Models\Panorama;
use App\Models\VrtourVersion;
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
        $user = auth('web')->user();
        $query = Project::query()->visibleFor($user);
        $scope = $user->getScope('project');
        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }
        $vrtour = $query->orderBy('name')->get();
        return view('backend.vrtour.content.index', compact('vrtour'));
    }

    public function getDataAll(Request $request, $vrtour_id)
    {
        $vrtour      = Project::find($vrtour_id);
        $user        = auth()->user();
        $link_vrtour = $vrtour->link_vrtour;
        $panorama    = Panorama::visibleFor($user)->where('vrtour_id', $vrtour_id)->orderByDesc('id')->get();
        $notice = null;
        if ($request->reset == 'true' || $panorama->isEmpty()) {
            $pano = getDataVrtour($link_vrtour . 'vista3d/pano.json');
            if (empty($pano)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hiện tại data pano không tồn tại',
                    'data'    => ''
                ]);
            }

            DB::transaction(function () use ($request, $pano, $vrtour_id, $user, &$notice) {
                // Chụp snapshot trước khi reset để còn khôi phục được
                if ($request->reset == 'true') {
                    $version = VrtourVersion::createPanoramaSnapshot($vrtour_id, $user->id, [
                        'change_summary' => 'Reset dữ liệu Panorama',
                    ]);
                    $notice = $this->versionNotice($version);
                }
                // Chỉ đối chiếu với bản đã xuất bản, không dùng visibleFor để khỏi bỏ sót bản ghi
                $existing = Panorama::where('vrtour_id', $vrtour_id)->where('is_draft', 0)->get();
                $byLabel = $existing->filter(fn($p) => !empty($p->label_audio))->keyBy('label_audio');
                $byTitle = $existing->keyBy('title');
                $byIds   = $existing->keyBy(fn($p) => $this->normalizeIds($p->ids));
                $keepIds = [];

                foreach ($pano as $pn) {
                    $label = $pn['label_audio'] ?? null;
                    $ids   = $this->normalizeIds($pn['ids'] ?? []);
                    $model = ($label ? $byLabel->get($label) : null) ?? $byTitle->get($pn['title']) ?? $byIds->get($ids);

                    if ($model) {
                        // Chỉ cập nhật cấu trúc, giữ nguyên title/content/audio admin đã sửa
                        $model->update([
                            'ids'         => json_encode($pn['ids']),
                            'label_audio' => $label,
                        ]);
                    } else {
                        $model = Panorama::create([
                            'vrtour_id'   => $vrtour_id,
                            'label_audio' => $label,
                            'ids'         => json_encode($pn['ids']),
                            'title'       => $pn['title'],
                            'user_id'     => $user->id,
                        ]);
                    }
                    $keepIds[] = $model->id;
                }

                if ($request->reset == 'true') {
                    // Chỉ xóa panorama đã xuất bản mà JSON không còn
                    $removeIds = Panorama::where('vrtour_id', $vrtour_id)->where('is_draft', 0)->whereNotIn('id', $keepIds)->pluck('id');
                    // Xóa luôn bản nháp con của chúng (nếu có)
                    Panorama::where('vrtour_id', $vrtour_id)->where(fn($q) => $q->whereIn('id', $removeIds)->orWhereIn('parent_id', $removeIds))->delete();
                }
            });

            $panorama = $vrtour->syncPanoFile();
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
            'success' => true,
            'data' => $html,
            'notice' => $notice ?? null,
        ]);
    }

    public function edit($id)
    {
        if (!Gate::allows('vr_tour/content')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $pano = Panorama::query()->with('user')->findOrFail($id);
        $parentData = [];
        $draftData = [];
        if ($pano->is_draft && $pano->parent_id) {
            $parent = Panorama::find($pano->parent_id);
            if ($parent) {
                $fields = [
                    'title'      => 'Tiêu đề',
                    'title_en'   => 'Tiêu đề (EN)',
                    'content'    => 'Nội dung',
                    'content_en' => 'Nội dung (EN)',
                    'audio'      => 'Audio',
                    'audio_en'   => 'Audio (EN)',
                ];
                foreach ($fields as $field => $label) {
                    $parentData[$label] = (string)($parent->$field ?? '');
                    $draftData[$label]  = (string)($pano->$field ?? '');
                }
            }
        }
        $allDataVrtour = $pano;
        return view('backend.vrtour.content.edit', compact('pano', 'parentData', 'draftData', 'allDataVrtour'));
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

            $version = DB::transaction(function () use ($main, $request, $user) {
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

                return VrtourVersion::createPanoramaSnapshot($main->vrtour_id, $user->id);
            });

            Project::findOrFail($main->vrtour_id)->syncPanoFile();

            return redirect()
                ->to(route('backend_vrtour_content_index') . '?vrtour=' . $main->vrtour_id)
                ->with('success', 'Đã duyệt và cập nhật nội dung')
                ->with('warning', $this->versionNotice($version));
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
                route('backend_vrtour_content_index'). '?vrtour=' . $draft->vrtour_id
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
        return redirect()->route('backend_vrtour_content_index', [
            'vrtour' => $content->vrtour_id,
        ])->with(
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
        if ($user->is_super_admin && $content->parent_id) {
            $parent = Panorama::find($content->parent_id);
            if ($parent) {
                $version = DB::transaction(function () use ($content, $parent, $user) {
                    $draftData = $content->toArray();
                    unset($draftData['id'], $draftData['parent_id'], $draftData['created_at'], $draftData['updated_at']);

                    $parent->fill($draftData);
                    $parent->parent_id      = null;
                    $parent->is_draft       = 0;
                    $parent->status         = 'approved';
                    $parent->approval_level = $parent->max_approval;
                    $parent->save();

                    // Xóa bản nháp trước, rồi mới chụp snapshot
                    $content->delete();

                    return VrtourVersion::createPanoramaSnapshot($parent->vrtour_id, $user->id);
                });

                // Ghi file ngoài transaction: lỗi ghi file không làm rollback DB
                Project::findOrFail($parent->vrtour_id)->syncPanoFile();

                return redirect()
                    ->route('backend_vrtour_content_index', ['vrtour' => $parent->vrtour_id])
                    ->with('success', 'Duyệt nội dung thành công.')
                    ->with('warning', $this->versionNotice($version));
            }
        }

        // Duyệt cấp 1
        if ($user->is_approve) {
            if ($content->approval_level < 1) {
                $content->approval_level = 1;
                $content->status = 'pending';
                $content->save();
            }
            return redirect()->route('backend_vrtour_content_index', [
                'vrtour' => $content->vrtour_id
            ])->with('success', 'Đã duyệt cấp 1, chờ duyệt cấp 2.');
        }
    }

    private function normalizeIds($ids): string
    {
        $arr = is_string($ids) ? (json_decode($ids, true) ?? []) : (array) $ids;
        $arr = array_map('intval', $arr);
        sort($arr);
        return implode(',', $arr);
    }
    /** Tạo thông báo nếu snapshot vừa xóa bản cũ do vượt giới hạn lưu trữ */
    private function versionNotice(?VrtourVersion $version): ?string
    {
        if (!$version || empty($version->pruned)) {
            return null;
        }

        $list = collect($version->pruned)->pluck('number')->map(fn($n) => 'v' . $n)->implode(', ');

        return 'Đã đạt giới hạn lưu trữ, phiên bản cũ nhất (' . $list . ') đã bị xóa.';
    }
}
