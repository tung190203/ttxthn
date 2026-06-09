<?php

namespace App\Http\Controllers;

use App\Models\AiUsageLog;
use App\Services\AiChatService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class AIChatController extends Controller
{
    protected $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
    }

    public function chat(Request $request)
    {
        $payload = [
            'language' => "auto",
            'message' => $request->message,
            'session_id' => $request->session_id,
        ];
        if ($request->has('model')) {
            $payload['model_name'] = $request->model;
        }

        try {
            $response = $this->aiChatService->chat($payload);
        } catch (ConnectionException $e) {
            return $this->aiConnectionErrorResponse($e, '/chat', [
                'session_id' => $payload['session_id'] ?? null,
                'message_length' => isset($payload['message']) ? mb_strlen((string) $payload['message']) : null,
            ]);
        } catch (Throwable $e) {
            return $this->aiUnexpectedErrorResponse($e, '/chat');
        }

        $body = $response->json() ?? [];

        if ($response->successful()) {
            $tokens = (array) Arr::get($body, 'tokens', []);

            AiUsageLog::create([
                'endpoint' => '/chat',
                'user_id' => optional(auth('web')->user())->id ?: optional(auth('guest')->user())->id,
                'model_used' => Arr::get($body, 'model_used') ?: Arr::get($tokens, 'model'),
                'input_tokens' => (int) (Arr::get($tokens, 'input') ?? Arr::get($tokens, 'input_tokens') ?? 0),
                'output_tokens' => (int) (Arr::get($tokens, 'output') ?? Arr::get($tokens, 'output_tokens') ?? 0),
                'cost_usd' => (float) (Arr::get($tokens, 'cost_usd') ?? Arr::get($body, 'cost_usd') ?? 0),
                'payload_json' => $body,
                'called_at' => now(),
            ]);
        }

        return response()->json($body, $response->status());
    }

    public function sessionHistory($sessionId)
    {
        try {
            $response = $this->aiChatService->getSessionHistory($sessionId);
        } catch (ConnectionException $e) {
            return $this->aiConnectionErrorResponse($e, '/chat/session', [
                'session_id' => $sessionId,
            ]);
        } catch (Throwable $e) {
            return $this->aiUnexpectedErrorResponse($e, '/chat/session');
        }

        return response()->json($response->json());
    }

    public function deleteSession($sessionId)
    {
        try {
            $response = $this->aiChatService->deleteSession($sessionId);
        } catch (ConnectionException $e) {
            return $this->aiConnectionErrorResponse($e, '/chat/session/delete', [
                'session_id' => $sessionId,
            ]);
        } catch (Throwable $e) {
            return $this->aiUnexpectedErrorResponse($e, '/chat/session/delete');
        }

        return response()->json($response->json(), $response->status());
    }

    public function submitFeedback(Request $request)
    {
        $payload = [
            'session_id' => $request->session_id,
            'message_id' => $request->message_id,
            'rating' => $request->rating,
            'feedback_type' => $request->feedback_type ?? 'helpful',
            'comment' => $request->comment
        ];

        try {
            $response = $this->aiChatService->sendFeedback($payload);
        } catch (ConnectionException $e) {
            return $this->aiConnectionErrorResponse($e, '/chat/feedback', [
                'session_id' => $payload['session_id'] ?? null,
                'message_id' => $payload['message_id'] ?? null,
            ]);
        } catch (Throwable $e) {
            return $this->aiUnexpectedErrorResponse($e, '/chat/feedback');
        }

        return response()->json($response->json(), $response->status());
    }

    public function getHealthStatus()
    {
        try {
            $response = $this->aiChatService->getHealthStatus();
        } catch (ConnectionException $e) {
            return $this->aiConnectionErrorResponse($e, '/chat/health');
        } catch (Throwable $e) {
            return $this->aiUnexpectedErrorResponse($e, '/chat/health');
        }

        return response()->json($response->json());
    }

    private function aiConnectionErrorResponse(ConnectionException $e, string $endpoint, array $context = [])
    {
        Log::error('AI chat controller could not connect to AI service.', array_merge($context, [
            'endpoint' => $endpoint,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]));

        return response()->json([
            'success' => false,
            'message' => __('app.chatbot_error_connection'),
        ], 502);
    }

    private function aiUnexpectedErrorResponse(Throwable $e, string $endpoint)
    {
        Log::error('AI chat controller failed unexpectedly.', [
            'endpoint' => $endpoint,
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => __('app.chatbot_error_connection'),
        ], 500);
    }
}
