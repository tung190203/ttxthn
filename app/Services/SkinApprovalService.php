<?php

namespace App\Services;

use App\Models\ConnectMap;
use App\Models\Investor;
use App\Models\LegalDocument;
use App\Models\Plan;
use App\Models\Project;
use App\Models\SkinApproval;
use App\Models\WelcomeScreen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SkinApprovalService
{
    /**
     * Lưu dữ liệu skin.
     *
     * @param Model $model Model thật (WelcomeScreen, ConnectMap,...)
     * @param array $payload Dữ liệu cần lưu
     * @param int $type Loại skin
     * @param int $vrtourId
     * @return array
     */
    private const WELCOME_FIELDS = [
        'title'          => 'Tên dự án',
        'description'    => 'Mô tả dự án',
        'voice'          => 'Voice',
        'show_investor'  => 'Hiển thị chủ đầu tư',
        'investor_img'   => 'Logo chủ đầu tư',
        'investor_desc1' => 'Thông tin chủ đầu tư 1',
        'investor_desc2' => 'Thông tin chủ đầu tư 2',
        'investor_desc3' => 'Thông tin chủ đầu tư 3',
    ];
    private const CONNECT_MAP_FIELDS = [
        'image'      => 'Ảnh VN',
        'image_en'   => 'Ảnh EN',
        'content'    => 'Nội dung chi tiết VN',
        'content_en' => 'Nội dung chi tiết EN',
    ];
    private const PLAN_FIELDS = [
        'show'        => 'Hiển thị',
        'background'  => 'Ảnh nền',

        'image1'      => 'Ảnh giai đoạn 1',
        'title1'      => 'Giai đoạn 1',
        'title1_en'   => 'Giai đoạn 1 EN',
        'content1'    => 'Nội dung giai đoạn 1 VN',
        'content1_en' => 'Nội dung giai đoạn 1 EN',

        'image2'      => 'Ảnh giai đoạn 2',
        'title2'      => 'Giai đoạn 2',
        'title2_en'   => 'Giai đoạn 2 EN',
        'content2'    => 'Nội dung giai đoạn 2 VN',
        'content2_en' => 'Nội dung giai đoạn 2 EN',

        'image3'      => 'Ảnh giai đoạn 3',
        'title3'      => 'Giai đoạn 3',
        'title3_en'   => 'Giai đoạn 3 EN',
        'content3'    => 'Nội dung giai đoạn 3 VN',
        'content3_en' => 'Nội dung giai đoạn 3 EN',
    ];
    private const INVESTOR_FIELDS = [
        'name'         => 'Tên',
        'name_en'      => 'Tên EN',
        'image'        => 'Ảnh',

        'content1'     => 'Nội dung 1 VN',
        'content1_en'  => 'Nội dung 1 EN',

        'content2'     => 'Nội dung 2 VN',
        'content2_en'  => 'Nội dung 2 EN',

        'content3'     => 'Nội dung 3 VN',
        'content3_en'  => 'Nội dung 3 EN',

        'website'      => 'Website',
        'sologan'      => 'Tên khác',
        'sologan_en'   => 'Tên khác EN',
        'status'       => 'Hiển thị',
    ];
    private const LOCATION_FIELDS = [
    'location_in_tour' => 'Vị trí',
    ];

    private const DOCUMENT_FIELDS = [
        'name'               => 'Tên văn bản',
        'name_en'            => 'Tên văn bản EN',
        'download'           => 'Tệp đính kèm',
    ];
    private const DIFF_CONFIG = [

        SkinApproval::TYPE_WELCOME => [
            'title'  => 'Màn hình chào mừng',
            'model'  => WelcomeScreen::class,
            'fields' => self::WELCOME_FIELDS,
        ],

        SkinApproval::TYPE_CONNECT_MAP => [
            'title'  => 'Sơ đồ liên kết vùng',
            'model'  => ConnectMap::class,
            'fields' => self::CONNECT_MAP_FIELDS,
        ],

        SkinApproval::TYPE_PLAN => [
            'title'  => 'Kế hoạch triển khai',
            'model'  => Plan::class,
            'fields' => self::PLAN_FIELDS,
        ],

        SkinApproval::TYPE_INVESTOR => [
            'title'  => 'Thông tin chủ đầu tư',
            'model'  => Investor::class,
            'fields' => self::INVESTOR_FIELDS,
        ],

        SkinApproval::TYPE_LOCATION => [
            'title'  => 'Vị trí',
            'model'  => Project::class,
            'fields' => self::LOCATION_FIELDS,
        ],

        SkinApproval::TYPE_DOCUMENT => [
            'title'  => 'Văn bản pháp quy',
            'model'  => LegalDocument::class,
            'fields' => self::DOCUMENT_FIELDS,
        ],
    ];
    public function save(Model $model, array $payload, int $type, int $vrtourId): array
    {
        $user = Auth::user();
        // payload là danh sách (Document)
        $isCollection = isset($payload[0]) && is_array($payload[0]);
        // Super Admin cập nhật trực tiếp
        if ($user->is_super_admin) {
            if (!$isCollection) {
                $model->fill($payload);
                if (Schema::hasColumn($model->getTable(), 'user_id')) {
                    $model->user_id = $user->id;
                }
                $model->save();
            }
            SkinApproval::where(['vrtour_id' => $vrtourId, 'type' => $type, 'status' => 'pending'])->delete();
            return [
                'status' => 'approved',
                'model'  => $isCollection ? $payload : $model,
            ];
        }

        // Tìm draft đang pending
        $draft = SkinApproval::where('type', $type)->where('vrtour_id', $vrtourId)->where('status', 'pending')->first();
        if (!$draft) {
            $draft = new SkinApproval();
            $draft->vrtour_id = $vrtourId;
            $draft->type = $type;
            $draft->record_id = $isCollection ? 0 : $model->id;
            $draft->max_approval = 2;
            $draft->is_draft = true;
        }

        $draft->payload = $payload;
        $draft->user_id = $user->id;
        $draft->approval_level = $user->is_approve ? 1 : 0;
        $draft->status = 'pending';
        $draft->save();
        return [
            'status' => 'pending',
            'model'  => $draft,
        ];
    }

    public function reject(int $vrtourId, array $types): void
    {
        foreach ($types as $type) {
            SkinApproval::where('vrtour_id', $vrtourId)->where('type', $type)->where('status', 'pending')->delete();
        }
    }
    public function approve(int $vrtourId, array $types): void
    {
        $user = Auth::user();

        foreach ($types as $type) {

            $approval = SkinApproval::where('vrtour_id', $vrtourId)->where('type', $type)->where('status', 'pending')->first();
            if (!$approval) {
                continue;
            }
            if ($user->is_super_admin) {
                $this->approveSkin($approval);
                $approval->delete();
                continue;
            }

            if ($user->is_approve) {
                if ($approval->approval_level == 0) {
                    $approval->approval_level = 1;
                    $approval->save();
                }
                continue;
            }
        }
    }

    private function approveSkin(SkinApproval $approval): void
    {
        switch ($approval->type) {

            case SkinApproval::TYPE_WELCOME:
                $this->approveModel(
                    $approval,
                    WelcomeScreen::class,
                    'welcome_screen.js'
                );
                break;

            case SkinApproval::TYPE_CONNECT_MAP:
                $this->approveModel(
                    $approval,
                    ConnectMap::class,
                    'connectmap.js'
                );
                break;

            case SkinApproval::TYPE_PLAN:
                $this->approveModel(
                    $approval,
                    Plan::class,
                    'plan.js'
                );
                break;

            case SkinApproval::TYPE_INVESTOR:
                $this->approveModel(
                    $approval,
                    Investor::class,
                    'investor.js'
                );
                break;

            case SkinApproval::TYPE_LOCATION:
                $this->approveLocation($approval);
                break;

            case SkinApproval::TYPE_DOCUMENT:
                $this->approveDocument($approval);
                break;
        }
    }
    private function approveModel(
        SkinApproval $approval,
        string $modelClass,
        string $fileName
    ): void {
        $model = $modelClass::find($approval->record_id);

        if (!$model) {
            return;
        }

        $model->fill($approval->payload);
        $model->save();

        $project = Project::find($approval->vrtour_id);

        if (!$project) {
            return;
        }

        createFile(
            'vrtour/' . $project->vrtour_code,
            $fileName
        );

        file_put_contents(
            'vrtour/' . $project->vrtour_code . '/' . $fileName,
            $model
        );
    }
    private function approveLocation(SkinApproval $approval)
    {
        $project = Project::find($approval->vrtour_id);
        if (!$project) {
            return;
        }
        $project->fill($approval->payload);
        $project->save();
        createFile('vrtour/' . $project->vrtour_code, 'location.js');
        file_put_contents(
            'vrtour/' . $project->vrtour_code . '/location.js',
            json_encode([
                'location' => $project->location_in_tour,
                'general'  => $project->link,
            ])
        );
    }
    private function approveDocument(SkinApproval $approval)
    {
        $project = Project::find($approval->vrtour_id);
        if (!$project) {
            return;
        }
        LegalDocument::where('vrtour_id', $approval->vrtour_id)->delete();
        foreach ($approval->payload as $item) {

            $document = new LegalDocument();
            $document->fill($item);
            $document->user_id = $approval->user_id; // hoặc Auth::id()
            $document->save();
        }
        $documents = LegalDocument::where('vrtour_id', $approval->vrtour_id)->get();
        createFile('vrtour/' . $project->vrtour_code, 'document.js');
        file_put_contents('vrtour/' . $project->vrtour_code . '/document.js', $documents);
    }

    public function getDiff(int $vrtourId, int $type): array
    {
        return $type === SkinApproval::TYPE_ALL ? $this->buildAllDiff($vrtourId) : $this->buildSingleDiff($vrtourId, $type);
    }

    private function buildSingleDiff(int $vrtourId, int $type): array
    {
        if (!isset(self::DIFF_CONFIG[$type])) {
            return [];
        }
        $diff = $this->buildDiff($vrtourId, $type);
        if (empty($diff['parentData']) && empty($diff['draftData'])) {
            return [];
        }
        return [[
            'title'      => self::DIFF_CONFIG[$type]['title'],
            'type'       => $type,
            'parentData' => $diff['parentData'],
            'draftData'  => $diff['draftData'],
        ]];
    }

    private function buildAllDiff(int $vrtourId): array
    {
        $result = [];
        foreach (self::DIFF_CONFIG as $type => $config) {
            $diff = $this->buildDiff($vrtourId, $type);
            if (empty($diff['parentData']) && empty($diff['draftData'])) {
                continue;
            }
            $result[] = [
                'title'      => $config['title'],
                'type'       => $type,
                'parentData' => $diff['parentData'],
                'draftData'  => $diff['draftData'],
            ];
        }
        return $result;
    }

    private function buildDiff(int $vrtourId, int $type): array
    {
        if (!isset(self::DIFF_CONFIG[$type])) {
            return [
                'parentData' => [],
                'draftData'  => [],
            ];
        }
        $approval = SkinApproval::where(['vrtour_id' => $vrtourId,'type' => $type,'status' => 'pending',])->first();
        if (!$approval) {
            return [
                'parentData' => [],
                'draftData'  => [],
            ];
        }
        if ($type === SkinApproval::TYPE_DOCUMENT) {
            return $this->buildDocumentDiff(
                $vrtourId,
                $approval->payload ?? []
            );
        }
        $config = self::DIFF_CONFIG[$type];
        $model = $type === SkinApproval::TYPE_LOCATION ? Project::find($vrtourId) : $config['model']::where('vrtour_id', $vrtourId)->first();
        if (!$model) {
            return [
                'parentData' => [],
                'draftData'  => [],
            ];
        }
        return [
            'parentData' => [
                $this->convertFields($model, $config['fields'])
            ],
            'draftData' => [
                $this->convertFields($approval->payload ?? [], $config['fields'])
            ],
        ];
    }

    private function buildDocumentDiff(int $vrtourId, array $payload): array
    {
        $documents = LegalDocument::where('vrtour_id', $vrtourId)->get()->values();
        $parentData = [];
        $draftData  = [];
        $max = max($documents->count(), count($payload));
        for ($i = 0; $i < $max; $i++) {
            $parentData[] = $this->convertFields(
                $documents[$i] ?? [],
                self::DOCUMENT_FIELDS
            );
            $draftData[] = $this->convertFields(
                $payload[$i] ?? [],
                self::DOCUMENT_FIELDS
            );
        }
        return [
            'parentData' => $parentData,
            'draftData'  => $draftData,
        ];
    }

    private function convertFields($source, array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $label) {
            $value = data_get($source, $field, '');
            if (is_array($value) || is_object($value)) {
                $value = json_encode(
                    $value,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
            }
            $result[$label] = (string) $value;
        }
        return $result;
    }
}
