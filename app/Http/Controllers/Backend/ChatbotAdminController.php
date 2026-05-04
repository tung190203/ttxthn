<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AiChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotAdminController extends Controller
{
    protected $aiChatService;

    public function __construct(AiChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
        parent::__construct();
    }

    private function proxyResponse($response)
    {
        return response($response->body(), $response->status())->header('Content-Type', 'application/json');
    }

    // --- Sync ---
    public function getSyncSettings()
    {
        return $this->proxyResponse($this->aiChatService->getAdminSyncSettings());
    }

    public function updateSyncSettings(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->updateAdminSyncSettings($request->all()));
    }

    public function triggerSync(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->triggerAdminSync($request->get('mode', 'delta')));
    }

    public function swapSlots()
    {
        return $this->proxyResponse($this->aiChatService->swapAdminSyncSlots());
    }

    // --- Prompts ---
    public function getPrompts()
    {
        return $this->proxyResponse($this->aiChatService->getAdminPrompts());
    }

    public function updatePrompt(Request $request, $key, $language)
    {
        return $this->proxyResponse($this->aiChatService->updateAdminPrompt($key, $language, $request->get('content')));
    }

    public function resetPrompt($key, $language)
    {
        return $this->proxyResponse($this->aiChatService->resetAdminPrompt($key, $language));
    }

    // --- Blacklist ---
    public function getBlacklist()
    {
        return $this->proxyResponse($this->aiChatService->getAdminBlacklist());
    }

    public function addBlacklistKeyword(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->addAdminBlacklistKeyword($request->all()));
    }

    public function updateBlacklistKeyword(Request $request, $keywordId)
    {
        return $this->proxyResponse($this->aiChatService->updateAdminBlacklistKeyword($keywordId, $request->all()));
    }

    public function deleteBlacklistKeyword($keywordId)
    {
        return $this->proxyResponse($this->aiChatService->deleteAdminBlacklistKeyword($keywordId));
    }

    public function updateBlacklistRefusal(Request $request, $group, $language)
    {
        return $this->proxyResponse($this->aiChatService->updateAdminBlacklistRefusal($group, $language, $request->get('content')));
    }

    public function resetBlacklistRefusal($group, $language)
    {
        return $this->proxyResponse($this->aiChatService->resetAdminBlacklistRefusal($group, $language));
    }

    public function getBlacklistLog(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->getAdminBlacklistLog($request->all()));
    }

    // --- Sessions & Feedback ---
    public function getSessions(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->getAdminSessions($request->all()));
    }

    public function getSessionDetail($sessionId)
    {
        return $this->proxyResponse($this->aiChatService->getAdminSessionDetail($sessionId));
    }

    public function exportSessions(Request $request)
    {
        // Because export can be a file download (CSV/TXT/JSON), we need to return the actual stream/content and correct content-type
        $response = $this->aiChatService->exportAdminSessions($request->all());
        $contentType = $response->header('Content-Type') ?? 'application/json';
        $contentDisposition = $response->header('Content-Disposition') ?? 'attachment; filename="export.json"';
        return response($response->body(), $response->status())
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', $contentDisposition);
    }

    public function exportSingleSession(Request $request, $sessionId)
    {
        $type = $request->get('type', 'json');
        $response = $this->aiChatService->exportAdminSingleSession($sessionId, $type);
        $contentType = $response->header('Content-Type') ?? 'application/json';
        $contentDisposition = $response->header('Content-Disposition') ?? "attachment; filename=\"session_{$sessionId}.{$type}\"";
        return response($response->body(), $response->status())
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', $contentDisposition);
    }

    public function getFeedbackList(Request $request)
    {
        return $this->proxyResponse($this->aiChatService->getAdminFeedbackList($request->all()));
    }
}
