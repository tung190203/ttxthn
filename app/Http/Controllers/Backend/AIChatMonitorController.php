<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    /**
     * Get advanced admin stats for the dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAdvancedStats(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date', now()->subDays(6)->format('Y-m-d')); // Reduced to 7 days for better performance on development
            $endDate = $request->get('end_date', now()->format('Y-m-d'));

            $baseUrl = config('services.ai_chat.api_url');
            $apiKey = config('services.ai_chat.api_key');
            $adminKey = config('services.ai_chat.api_admin_key');

            $headers = [
                'X-API-Key' => $apiKey,
                'X-Admin-API-Key' => $adminKey,
                'Content-Type' => 'application/json'
            ];

            // Using Http Pool to fetch data in parallel to avoid blocking the single-threaded server.
            $responses = Http::pool(fn ($pool) => [
                $pool->as('overview')->withHeaders($headers)->timeout(10)->get($baseUrl . '/api/v1/admin/stats/overview', [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]),
                $pool->as('daily')->withHeaders($headers)->timeout(10)->get($baseUrl . '/api/v1/admin/stats/daily', [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]),
                $pool->as('intents')->withHeaders($headers)->timeout(10)->get($baseUrl . '/api/v1/admin/stats/intents', [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $responses['overview']?->json(),
                    'daily' => $responses['daily']?->json(),
                    'intents' => $this->formatIntents($responses['intents']?->json()),
                    'range' => [
                        'start' => $startDate,
                        'end' => $endDate
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('AI Advanced Stats Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching AI statistics'
            ], 500);
        }
    }

    /**
     * Format and translate intents for display.
     */
    private function formatIntents($data)
    {
        if (!$data || !isset($data['intents'])) {
            return $data;
        }

        foreach ($data['intents'] as &$item) {
            // Formatting: ASK_PROJECT -> Ask Project
            $formatted = str_replace(['_', '-'], ' ', $item['intent']);
            $item['intent'] = ucwords(strtolower($formatted));
        }

        return $data;
    }
}
