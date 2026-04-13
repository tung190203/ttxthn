<?php

namespace App\Http\Controllers;

use App\Services\AiChatService;
use Illuminate\Http\Request;

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

        $response = $this->aiChatService->chat($payload);

        return response()->json($response->json());
    }

    public function sessionHistory($sessionId)
    {
        $response = $this->aiChatService->getSessionHistory($sessionId);

        return response()->json($response->json());
    }

    public function deleteSession($sessionId)
    {
        $response = $this->aiChatService->deleteSession($sessionId);

        return response()->json($response->json(), $response->status());
    }

    public function submitFeedback(Request $request)
    {
        $response = $this->aiChatService->sendFeedback([
            'session_id' => $request->session_id,
            'message_id' => $request->message_id,
            'rating' => $request->rating,
            'feedback_type' => $request->feedback_type ?? 'helpful',
            'comment' => $request->comment
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function getHealthStatus()
    {
        $response = $this->aiChatService->getHealthStatus();
        return response()->json($response->json());
    }
}
