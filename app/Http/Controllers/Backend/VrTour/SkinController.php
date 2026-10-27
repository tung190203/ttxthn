<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use App\Models\ConnectMap;
use App\Models\Investor;
use App\Models\Plan;
use App\Models\Project;
use App\Models\SkinApproval;
use App\Models\VrtourVersion;
use App\Models\VrtourVersionItem;
use App\Models\WelcomeScreen;
use App\Services\SkinApprovalService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SkinController extends Controller
{
    /**
     * Mỗi phần skin: key là SkinApproval::TYPE_*, cũng chính là giá trị request->type
     * (request->type = 0 nghĩa là lưu tất cả các phần).
     */
    private const SECTIONS = [
        SkinApproval::TYPE_WELCOME     => ['entity' => 'welcome',     'request' => 'screen',       'model' => WelcomeScreen::class, 'file' => 'welcome_screen.js'],
        SkinApproval::TYPE_CONNECT_MAP => ['entity' => 'connect_map', 'request' => 'connect_data', 'model' => ConnectMap::class,    'file' => 'connectmap.js'],
        SkinApproval::TYPE_PLAN        => ['entity' => 'plan',        'request' => 'plan',         'model' => Plan::class,          'file' => 'plan.js'],
        SkinApproval::TYPE_INVESTOR    => ['entity' => 'investor',    'request' => 'investor',     'model' => Investor::class,      'file' => 'investor.js'],
        SkinApproval::TYPE_LOCATION    => ['entity' => 'location',    'request' => 'location',     'model' => Project::class,       'file' => 'location.js'],
    ];

    protected SkinApprovalService $skinApprovalService;

    public function __construct(SkinApprovalService $skinApprovalService)
    {
        $this->skinApprovalService = $skinApprovalService;
        $this->selectedMainMenu = 'vr_tour';
        $this->selectedSubMenu('skin');
        parent::__construct();

        if (!Gate::allows('vr_tour/skin')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index()
    {
        $user  = auth('web')->user();
        $query = Project::query()->visibleFor($user);
        $scope = $user->getScope('project');
        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }
        $vrtour = $query->orderBy('name')->get();
        return view('backend.vrtour.skin.index', compact('vrtour'));
    }

    public function getDataAll($vrtour_id, $type)
    {
        $data = [
            'connect_map' => $this->getSkinData(ConnectMap::class, $vrtour_id),
            'screen'      => $this->getSkinData(WelcomeScreen::class, $vrtour_id),
            'plan'        => $this->getSkinData(Plan::class, $vrtour_id),
            'investor'    => $this->getSkinData(Investor::class, $vrtour_id),
            'location'    => $this->getSkinData(Project::class, $vrtour_id, 'id'),
        ];

        return response()->json([
            'data'    => $data,
            'status'  => true,
            'message' => 'Lấy dữ liệu thành công !',
        ], 200);
    }

    public function updateDataAll(Request $request, $vrtour_id)
    {
        try {
            DB::beginTransaction();

            $vrtour  = Project::findOrFail($vrtour_id);
            $changes = [];

            foreach (self::SECTIONS as $type => $cfg) {
                if (!in_array($request->type, [0, $type])) {
                    continue;
                }

                $change = $type === SkinApproval::TYPE_LOCATION ? $this->saveLocation($request, $vrtour, $cfg) : $this->saveSection($request, $vrtour, $type, $cfg);

                if ($change) {
                    $changes[] = $change;
                }
            }

            // Một lần lưu = một version, mỗi phần thật sự đổi là một item
            $notice = null;
            if ($changes) {
                $version = VrtourVersion::recordSkin($vrtour->id, Auth::id(), $changes);
                $notice = $version?->pruneNotice();
            }

            DB::commit();

            return response()->json([
                'data'    => [],
                'status'  => true,
                'message' => 'Cập nhật dữ liệu thành công !',
                'notice'  => $notice,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'data'    => [],
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lưu một phần skin (welcome, connect map, plan, investor).
     * Trả về "change" để ghi version, hoặc null nếu bản thật chưa đổi (đang chờ duyệt).
     */
    private function saveSection(Request $request, Project $vrtour, int $type, array $cfg): ?array
    {
        $data    = (array) $request->input($cfg['request'], []);
        $payload = $this->buildPayload($type, $data, $vrtour);

        // Tạo mới: ghi thẳng, không qua duyệt
        if (empty($data['id'])) {
            $model = new $cfg['model']();
            $model->fill($payload);
            $model->user_id = Auth::id();
            $model->save();

            $this->writeJs($vrtour, $cfg['file'], $model);

            return [
                'type'   => $cfg['entity'],
                'key'    => $model->getKey(),
                'before' => null,
                'after'  => VrtourVersionItem::skinData($cfg['entity'], $model),
            ];
        }

        $model  = $cfg['model']::findOrFail($data['id']);
        $result = $this->skinApprovalService->save($model, $payload, $type, $vrtour->id);

        // Chỉ Super Admin ghi thẳng bản thật; người khác chờ duyệt
        if ($result['status'] !== 'approved') {
            return null;
        }

        $this->writeJs($vrtour, $cfg['file'], $result['model']);

        return $result['change'];
    }

    /** Location nằm trong cột của Project, luôn đi qua service */
    private function saveLocation(Request $request, Project $vrtour, array $cfg)
    {
        $payload = ['location_in_tour' => $request->input('location.map')];
        $result  = $this->skinApprovalService->save($vrtour, $payload, SkinApproval::TYPE_LOCATION, $vrtour->id);

        if ($result['status'] !== 'approved') {
            return null;
        }

        $this->writeJs($vrtour, $cfg['file'], json_encode([
            'location' => $result['model']->location_in_tour,
            'general'  => $result['model']->link,
        ]));

        return $result['change'];
    }

    /** Payload của từng phần, lấy từ dữ liệu request của phần đó */
    private function buildPayload(int $type, array $d, Project $vrtour): array
    {
        $base = ['vrtour_id' => $vrtour->id];

        return match ($type) {
            SkinApproval::TYPE_WELCOME => $base + [
                'title'          => $d['title'] ?? null,
                'description'    => $d['description'] ?? null,
                'voice'          => $d['voice'] ?? null,
                'show_investor'  => !empty($d['status']) ? 1 : 0,
                'investor_img'   => $d['investor_img'] ?? null,
                'investor_desc1' => $d['investor_desc1'] ?? null,
                'investor_desc2' => $d['investor_desc2'] ?? null,
                'investor_desc3' => $d['investor_desc3'] ?? null,
            ],

            SkinApproval::TYPE_CONNECT_MAP => $base + $this->pick($d, ['image', 'image_en', 'content', 'content_en']),

            SkinApproval::TYPE_PLAN => $base
                + ['show' => !empty($d['show']) ? 1 : 0, 'background' => $vrtour->banner_image]
                + $this->pick($d, $this->numbered(['image', 'title', 'title_en', 'content', 'content_en'], 3)),

            SkinApproval::TYPE_INVESTOR => $base
                + ['status' => !empty($d['status']) ? 1 : 0]
                + $this->pick($d, [
                    'name', 'name_en', 'image',
                    'content1', 'content2', 'content3',
                    'content1_en', 'content2_en', 'content3_en',
                    'website', 'sologan', 'sologan_en',
                ]),
        };
    }

    /** Lấy các key cần dùng, thiếu thì null */
    private function pick(array $data, array $keys): array
    {
        $out = [];
        foreach ($keys as $k) {
            $out[$k] = $data[$k] ?? null;
        }

        return $out;
    }

    private function numbered(array $names, int $count): array
    {
        $keys = [];
        for ($i = 1; $i <= $count; $i++) {
            foreach ($names as $n) {
                // title_en -> title1_en, content_en -> content1_en
                $keys[] = str_ends_with($n, '_en') ? substr($n, 0, -3) . $i . '_en' : $n . $i;
            }
        }

        return $keys;
    }

    private function writeJs(Project $vrtour, string $file, $content): void
    {
        $dir = 'vrtour/' . $vrtour->vrtour_code;
        createFile($dir, $file);
        file_put_contents($dir . '/' . $file, is_string($content) ? $content : $content->toJson());
    }

    public function rejectAll(Request $request)
    {
        $this->skinApprovalService->reject($request->vrtour_id, $request->types);

        return response()->json([
            'status'  => true,
            'message' => 'Đã từ chối thành công.',
        ]);
    }

    public function approveAll(Request $request)
    {
        $changes = $this->skinApprovalService->approve($request->vrtour_id, $request->types);

        $notice = null;
        if ($changes) {
            $version = VrtourVersion::recordSkin((int) $request->vrtour_id, Auth::id(), $changes, 'Duyệt Skin');
            $notice = $version?->pruneNotice();
        }

        return response()->json(['status' => true, 'message' => 'Duyệt thành công.', 'notice' => $notice]);
    }

    private function getSkinData(string $modelClass, int $id, string $column = 'vrtour_id')
    {
        $model = $modelClass::with(['pendingSkinApproval', 'pendingSkinApproval.user'])
            ->where($column, $id)
            ->first();

        if ($model && $model->pendingSkinApproval) {
            $model->forceFill($model->pendingSkinApproval->payload);
        }

        return $model;
    }

    public function getDiff(Request $request)
    {
        $request->validate(['vrtour_id' => 'required|integer','type' => 'required|integer',]);

        return response()->json([
            'status' => true,
            'data'   => $this->skinApprovalService->getDiff($request->vrtour_id, $request->type),
        ]);
    }
}