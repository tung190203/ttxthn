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
        createFile('vrtour/' . $project->vrtour_code,'document.js');
        file_put_contents( 'vrtour/' . $project->vrtour_code . '/document.js', $documents );
    }
}
