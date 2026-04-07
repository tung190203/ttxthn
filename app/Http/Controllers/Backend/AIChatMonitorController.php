<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AIChatMonitorController extends Controller
{
    protected $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
        parent::__construct();
    }

    /**
     * Get combined status and metrics for the AI Bot.
     *
     * @return JsonResponse
     */
    public function getApiStatus(): JsonResponse
    {
        try {
            $health = $this->aiChatService->getHealthStatus();
            $status = $this->aiChatService->getStatus();
            $metrics = $this->aiChatService->getMetrics();

            return response()->json([
                'success' => true,
                'data' => [
                    'health' => $health->json() ?? ['status' => 'offline'],
                    'status' => $status->json() ?? [],
                    'metrics' => $metrics->json() ?? [],
                    'connected' => $health->successful()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('AI Monitor Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Could not connect to AI Service',
                'data' => [
                    'connected' => false,
                    'health' => ['status' => 'offline']
                ]
            ], 500);
        }
    }
}
