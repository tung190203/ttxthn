<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiChatService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.ai_chat.api_url');
        $this->apiKey = config('services.ai_chat.api_key');
    }

    protected function client()
    {
        return Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'Content-Type' => 'application/json'
        ]);
    }

    public function sendFeedback($data)
    {
        return $this->client()->post($this->baseUrl . '/api/v1/chat/feedback', [
            'session_id' => $data['session_id'],
            'message_id' => $data['message_id'],
            'rating' => $data['rating'],
            'feedback_type' => $data['feedback_type'] ?? 'helpful',
            'comment' => $data['comment'] ?? null
        ]);
    }

    public function chat(array $payload)
    {
        return $this->client()->post($this->baseUrl . '/api/v1/chat', $payload);
    }

    public function getSessionHistory($sessionId)
    {
        return $this->client()->get($this->baseUrl . "/api/v1/session/{$sessionId}");
    }

    public function deleteSession($sessionId)
    {
        return $this->client()->delete($this->baseUrl . "/api/v1/session/{$sessionId}");
    }

    public function getHealthStatus()
    {
        return $this->client()->get($this->baseUrl . '/api/v1/health');
    }

    public function getStatus()
    {
        return $this->client()->get($this->baseUrl . '/api/v1/status');
    }

    public function getMetrics()
    {
        return $this->client()->get($this->baseUrl . '/api/v1/metrics');
    }
}