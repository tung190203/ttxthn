<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\ConnectMap;
use App\Models\Investor;
use App\Models\WelcomeScreen;
use App\Models\Plan;
use App\Models\LegalDocument;
use App\Models\SkinApproval;
use App\Services\SkinApprovalService;
use Illuminate\Support\Facades\DB;
use Auth;

class SkinController extends Controller
{
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
        $vrtour     = Project::all();
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
            'document'    => $this->getDocumentData($vrtour_id),
        ];

        return response()->json([
            'data'    => $data,
            'status'  => true,
            'message' => 'Lấy dữ liệu thành công !'
        ], 200);
    }

    public function updateDataAll(Request $request, $vrtour_id)
    {
        try {
            DB::beginTransaction();
            //get tour
            $vrtour                     = Project::find($vrtour_id);
            // welcome screen
            if (in_array($request->type, [0, 1])) {

                $payload = [
                    'vrtour_id'        => $vrtour_id,
                    'title'            => $request['screen']['title'],
                    'description'      => $request['screen']['description'],
                    'voice'            => $request['screen']['voice'],
                    'show_investor'    => $request['screen']['status'] ? 1 : 0,
                    'investor_img'     => $request['screen']['investor_img'],
                    'investor_desc1'   => $request['screen']['investor_desc1'],
                    'investor_desc2'   => $request['screen']['investor_desc2'],
                    'investor_desc3'   => $request['screen']['investor_desc3'],
                ];

                // Tạo mới
                if (empty($request['screen']['id'])) {
                    $screen = new WelcomeScreen();
                    $screen->fill($payload);
                    $screen->user_id = Auth::id();
                    $screen->save();
                    createFile('vrtour/' . $vrtour->vrtour_code, 'welcome_screen.js');
                    file_put_contents(
                        'vrtour/' . $vrtour->vrtour_code . '/welcome_screen.js',
                        $screen
                    );
                } else {
                    $screen = WelcomeScreen::findOrFail($request['screen']['id']);
                    $result = $this->skinApprovalService->save(
                        $screen,
                        $payload,
                        SkinApproval::TYPE_WELCOME,
                        $vrtour_id
                    );
                    // Chỉ Super Admin mới cập nhật file
                    if ($result['status'] === 'approved') {
                        createFile('vrtour/' . $vrtour->vrtour_code, 'welcome_screen.js');
                        file_put_contents(
                            'vrtour/' . $vrtour->vrtour_code . '/welcome_screen.js',
                            $result['model']
                        );
                    }
                }
            }

            //connect map
            if (in_array($request->type, [0, 3])) {
                $payload = [
                    'vrtour_id'  => $vrtour_id,
                    'image'      => $request['connect_data']['image'],
                    'image_en'   => $request['connect_data']['image_en'],
                    'content'    => $request['connect_data']['content'],
                    'content_en' => $request['connect_data']['content_en'],
                ];
                if (empty($request['connect_data']['id'])) {
                    $connect_map = new ConnectMap();
                    $connect_map->fill($payload);
                    $connect_map->user_id = Auth::id();
                    $connect_map->save();
                    createFile('vrtour/' . $vrtour->vrtour_code, 'connectmap.js');
                    file_put_contents('vrtour/' . $vrtour->vrtour_code . '/connectmap.js', $connect_map);
                } else {
                    $connect_map = ConnectMap::findOrFail($request['connect_data']['id']);
                    $result = $this->skinApprovalService->save(
                        $connect_map,
                        $payload,
                        SkinApproval::TYPE_CONNECT_MAP,
                        $vrtour_id
                    );
                    if ($result['status'] === 'approved') {
                        createFile('vrtour/' . $vrtour->vrtour_code, 'connectmap.js');
                        file_put_contents(
                            'vrtour/' . $vrtour->vrtour_code . '/connectmap.js', $result['model']
                        );
                    }
                }
            }

            //document
            if (in_array($request->type, [0, 4])) {
                $payload = [];
                if (!empty($request->document)) {
                    foreach ($request->document as $doc) {
                        $payload[] = [
                            'vrtour_id'          => $vrtour_id,
                            'name'               => $doc['document_name'],
                            'name_en'            => $doc['document_name_en'],
                            'download'           => $doc['download'],
                            'extracted_text'     => $doc['extracted_text'] ?? null,
                            'extracted_summary'  => $doc['extracted_summary'] ?? null,
                            'extracted_language' => $doc['extracted_language'] ?? null,
                            'extracted_at'       => $doc['extracted_at'] ?? null,
                        ];
                    }
                }

                $result = $this->skinApprovalService->save(
                    new LegalDocument(),
                    $payload,
                    SkinApproval::TYPE_DOCUMENT,
                    $vrtour_id
                );

                // Chỉ Super Admin mới cập nhật DB thật
                if ($result['status'] === 'approved') {
                    LegalDocument::where('vrtour_id', $vrtour_id)->delete();
                    foreach ($result['model'] as $item) {
                        $document = new LegalDocument();
                        $document->fill($item);
                        $document->user_id = Auth::id();
                        $document->save();
                    }
                    $allDocument = LegalDocument::where('vrtour_id', $vrtour_id)->get();
                    createFile('vrtour/' . $vrtour->vrtour_code,'document.js');
                    file_put_contents('vrtour/' . $vrtour->vrtour_code . '/document.js',$allDocument);
                }
            }

            //plan
            if (in_array($request->type, [0, 5])) {

                $payload = [
                    'vrtour_id'   => $vrtour_id,
                    'show'        => $request['plan']['show'] ? 1 : 0,
                    'background'  => $vrtour->banner_image,

                    'image1'      => $request['plan']['image1'],
                    'title1'      => $request['plan']['title1'],
                    'title1_en'   => $request['plan']['title1_en'],
                    'content1'    => $request['plan']['content1'],
                    'content1_en' => $request['plan']['content1_en'],

                    'image2'      => $request['plan']['image2'],
                    'title2'      => $request['plan']['title2'],
                    'title2_en'   => $request['plan']['title2_en'],
                    'content2'    => $request['plan']['content2'],
                    'content2_en' => $request['plan']['content2_en'],

                    'image3'      => $request['plan']['image3'],
                    'title3'      => $request['plan']['title3'],
                    'title3_en'   => $request['plan']['title3_en'],
                    'content3'    => $request['plan']['content3'],
                    'content3_en' => $request['plan']['content3_en'],
                ];

                if (empty($request['plan']['id'])) {

                    $plan = new Plan();
                    $plan->fill($payload);
                    $plan->user_id = Auth::id();
                    $plan->save();

                    createFile('vrtour/' . $vrtour->vrtour_code, 'plan.js');
                    file_put_contents('vrtour/' . $vrtour->vrtour_code . '/plan.js',$plan);
                } else {
                    $plan = Plan::findOrFail($request['plan']['id']);
                    $result = $this->skinApprovalService->save(
                        $plan,
                        $payload,
                        SkinApproval::TYPE_PLAN,
                        $vrtour_id
                    );

                    if ($result['status'] === 'approved') {

                        createFile('vrtour/' . $vrtour->vrtour_code, 'plan.js');
                        file_put_contents(
                            'vrtour/' . $vrtour->vrtour_code . '/plan.js',
                            $result['model']
                        );
                    }
                }
            }

            //investor
            if (in_array($request->type, [0, 6])) {

                $payload = [
                    'vrtour_id'    => $vrtour_id,
                    'name'         => $request['investor']['name'],
                    'name_en'      => $request['investor']['name_en'],
                    'image'        => $request['investor']['image'],

                    'content1'     => $request['investor']['content1'],
                    'content2'     => $request['investor']['content2'],
                    'content3'     => $request['investor']['content3'],

                    'content1_en'  => $request['investor']['content1_en'],
                    'content2_en'  => $request['investor']['content2_en'],
                    'content3_en'  => $request['investor']['content3_en'],

                    'website'      => $request['investor']['website'],
                    'sologan'      => $request['investor']['sologan'],
                    'sologan_en'   => $request['investor']['sologan_en'],
                    'status'       => $request['investor']['status'] ? 1 : 0,
                ];

                if (empty($request['investor']['id'])) {

                    $investor = new Investor();
                    $investor->fill($payload);
                    $investor->user_id = Auth::id();
                    $investor->save();

                    createFile('vrtour/' . $vrtour->vrtour_code, 'investor.js');
                    file_put_contents(
                        'vrtour/' . $vrtour->vrtour_code . '/investor.js',
                        $investor
                    );
                } else {
                    $investor = Investor::findOrFail($request['investor']['id']);
                    $result = $this->skinApprovalService->save(
                        $investor,
                        $payload,
                        SkinApproval::TYPE_INVESTOR,
                        $vrtour_id
                    );
                    if ($result['status'] === 'approved') {
                        createFile('vrtour/' . $vrtour->vrtour_code, 'investor.js');
                        file_put_contents(
                            'vrtour/' . $vrtour->vrtour_code . '/investor.js',
                            $result['model']
                        );
                    }
                }
            }

            //location
            if (in_array($request->type, [0, 7])) {

                $payload = [
                    'location_in_tour' => $request['location']['map'],
                ];

                $result = $this->skinApprovalService->save(
                    $vrtour,
                    $payload,
                    SkinApproval::TYPE_LOCATION,
                    $vrtour_id
                );

                if ($result['status'] === 'approved') {
                    createFile('vrtour/' . $vrtour->vrtour_code, 'location.js');
                    file_put_contents(
                        'vrtour/' . $vrtour->vrtour_code . '/location.js',
                        json_encode([
                            'location' => $result['model']->location_in_tour,
                            'general'  => $result['model']->link,
                        ])
                    );
                }
            }

            DB::commit();
            return response()->json([
                'data'      => [],
                'status'    => true,
                'message'   => 'Cập nhật dữ liệu thành công !'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data'      => [],
                'status'    => false,
                'message'   => $e->getMessage()
            ], 500);
        }
    }

    public function rejectAll(Request $request)
    {
        $this->skinApprovalService->reject(
            $request->vrtour_id,
            $request->types
        );

        return response()->json([
            'status' => true,
            'message' => 'Đã từ chối thành công.'
        ]);
    }

    public function approveAll(Request $request)
    {
        $this->skinApprovalService->approve(
            $request->vrtour_id,
            $request->types
        );

        return response()->json([
            'status' => true,
            'message' => 'Duyệt thành công.'
        ]);
    }
    private function getSkinData(
        string $modelClass,
        int $id,
        string $column = 'vrtour_id'
    ) {
        $model = $modelClass::with([
            'pendingSkinApproval',
            'pendingSkinApproval.user'
        ])
            ->where($column, $id)
            ->first();

        if ($model && $model->pendingSkinApproval) {
            $model->forceFill($model->pendingSkinApproval->payload);
        }

        return $model;
    }

    private function getDocumentData(int $vrtourId)
    {
        $documents = LegalDocument::where('vrtour_id', $vrtourId)->get();

        $approval = SkinApproval::with('user')
            ->where('vrtour_id', $vrtourId)
            ->where('type', SkinApproval::TYPE_DOCUMENT)
            ->where('status', 'pending')->first();

        if ($approval) {
            return [
                'items' => $approval->payload,
                'pending_skin_approval' => $approval,
            ];
        }
        return [
            'items' => $documents,
            'pending_skin_approval' => null,
        ];
    }
}
