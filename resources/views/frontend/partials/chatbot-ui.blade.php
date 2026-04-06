<!-- Chatbot Floating Button -->
<div id="ai-chatbot-btn-container" class="chatbot-btn-container">
    <div class="chatbot-tooltip">Trợ lý AI đang sẵn sàng hỗ trợ bạn!</div>
    <div id="ai-chatbot-btn" class="chatbot-floating-btn" onclick="toggleChatbot()">
        <i class="fal fa-comment-alt-lines fa-2x text-white"></i>
    </div>
</div>

<!-- Chatbot Window -->
<div id="ai-chatbot-window" class="chatbot-window">
    <div class="chatbot-header">
        <div class="d-flex align-items-center">
            <div class="chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="ms-2">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 text-white" style="font-size: 16px; font-weight: 600;">Trợ lý AI Đầu Tư</h5>
                    <span id="chatbot-status-dot" class="ms-2" style="width: 8px; height: 8px; background-color: #94a3b8; border-radius: 50%; display: inline-block;" title="Kiểm tra trạng thái..."></span>
                </div>
                <div class="d-flex align-items-center">
                    <small class="text-white-50" style="font-size: 12px;" id="chatbot-stage-indicator">Đang trực tuyến</small>
                    <select id="chatbot-model-select" class="ms-2 border-0 bg-transparent text-white-50" style="font-size: 10px; outline: none; cursor: pointer;">
                        <option value="">Mặc định</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="chatbot-actions">
            <button onclick="resetChatSession()" class="btn btn-sm text-white" title="Làm mới trò chuyện"><i class="fal fa-sync"></i></button>
            <button onclick="deleteChatSession()" class="btn btn-sm text-white" title="Xóa cuộc trò chuyện"><i class="fal fa-trash-alt"></i></button>
            <button onclick="toggleChatbot()" class="btn btn-sm text-white" title="Đóng"><i class="fal fa-times"></i></button>
        </div>
    </div>

    <div class="chatbot-body" id="chatbot-messages">
        <div class="chatbot-message bot-message" data-id="welcome">
            <div class="message-content">
                Xin chào! Tôi có thể giúp gì cho bạn trong việc tìm kiếm dự án và thông tin đầu tư?
            </div>
        </div>
    </div>

    <!-- Feedback Modal (Simple Overlay) -->
    <div id="chatbot-feedback-modal" class="chatbot-modal">
        <div class="chatbot-modal-content">
            <h6 class="mb-3">Gửi phản hồi cho chúng tôi</h6>
            <input type="hidden" id="feedback-message-id">
            <input type="hidden" id="feedback-rating">
            <div class="mb-3">
                <label class="form-label small">Lý do (tùy chọn):</label>
                <select id="feedback-type" class="form-select form-select-sm">
                    <option value="helpful">Hữu ích</option>
                    <option value="not_helpful">Không hữu ích</option>
                    <option value="incorrect">Thông tin sai lệch</option>
                    <option value="other">Khác</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small">Bình luận:</label>
                <textarea id="feedback-comment" class="form-control form-control-sm" rows="3" placeholder="Nhập ý kiến của bạn..."></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button onclick="hideFeedbackModal()" class="btn btn-sm btn-light">Hủy</button>
                <button onclick="submitFeedbackForm()" class="btn btn-sm btn-primary">Gửi</button>
            </div>
        </div>
    </div>

    <div class="chatbot-suggested-actions" id="chatbot-suggested-actions" style="display: none;">
        <!-- Suggested actions will be appended here -->
    </div>

    <div class="chatbot-footer">
        <form id="chatbot-form" onsubmit="sendChatMessage(event)">
            <div class="chatbot-input-group">
                <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..." autocomplete="off">
                <button type="submit" id="chatbot-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Chatbot Styling */
    :root {
        --cb-primary: #1a6fc4;
        --cb-primary-gradient: linear-gradient(135deg, #1a6fc4, #00d2ff);
        --cb-bg: #f8fafc;
        --cb-text: #334155;
        --cb-bot-msg: #ffffff;
        --cb-user-msg: #1a6fc4;
        --cb-border: #e2e8f0;
    }

    .chatbot-btn-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        align-items: center;
        z-index: 10000;
    }

    .chatbot-tooltip {
        background: white;
        padding: 8px 15px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-right: 15px;
        font-size: 13px;
        font-weight: 500;
        color: var(--cb-text);
        white-space: nowrap;
        animation: fadeInRight 0.5s ease-out forwards, floatingTooltip 3s ease-in-out infinite;
        position: relative;
        border: 1px solid var(--cb-border);
    }

    .chatbot-tooltip::after {
        content: '';
        position: absolute;
        right: -6px;
        top: 50%;
        transform: translateY(-50%) rotate(45deg);
        width: 10px;
        height: 10px;
        background: white;
        border-right: 1px solid var(--cb-border);
        border-top: 1px solid var(--cb-border);
    }

    .chatbot-floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--cb-primary-gradient);
        box-shadow: 0 10px 25px rgba(26, 111, 196, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s;
        animation: bounce 4s infinite;
    }

    .chatbot-floating-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 15px 35px rgba(26, 111, 196, 0.5);
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-15px); }
        60% { transform: translateY(-7px); }
    }

    @keyframes floatingTooltip {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(-5px); }
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .chatbot-window {
        position: fixed;
        bottom: 105px;
        right: 30px;
        width: 380px;
        max-width: calc(100vw - 40px);
        height: 600px;
        max-height: calc(100vh - 120px);
        background: var(--cb-bg);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        z-index: 10000;
        opacity: 0;
        transform: translateY(20px);
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .chatbot-window.active {
        opacity: 1;
        transform: translateY(0);
        pointer-events: all;
    }

    .chatbot-header {
        background: var(--cb-primary-gradient);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        z-index: 3;
    }

    .chatbot-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        backdrop-filter: blur(5px);
    }

    .chatbot-actions button {
        background: none;
        border: none;
        outline: none;
        padding: 5px 8px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .chatbot-actions button:hover {
        background: rgba(255,255,255,0.1);
    }

    .chatbot-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        scroll-behavior: smooth;
    }

    .chatbot-body::-webkit-scrollbar {
        width: 6px;
    }
    .chatbot-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .chatbot-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .chatbot-message {
        display: flex;
        flex-direction: column;
        max-width: 85%;
        animation: fadeIn 0.3s ease-out forwards;
        position: relative;
    }

    .chatbot-message.bot-message {
        align-self: flex-start;
        padding-right: 20px;
    }

    .chatbot-message.user-message {
        align-self: flex-end;
    }

    .message-content {
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        word-wrap: break-word;
    }

    .bot-message .message-content {
        background: var(--cb-bot-msg);
        color: var(--cb-text);
        border-bottom-left-radius: 4px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .user-message .message-content {
        background: var(--cb-primary-gradient);
        color: white;
        border-bottom-right-radius: 4px;
    }

    /* Feedback buttons */
    .chatbot-message-feedback {
        display: flex;
        gap: 8px;
        margin-top: 5px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .bot-message:hover .chatbot-message-feedback {
        opacity: 1;
    }

    .feedback-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 12px;
        cursor: pointer;
        transition: color 0.2s;
        padding: 2px 4px;
    }

    .feedback-btn:hover {
        color: var(--cb-primary);
    }
    .feedback-btn.active {
        color: var(--cb-primary);
    }

    .chatbot-metadata {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
    }

    .chatbot-entity-tag {
        font-size: 11px;
        background: #e2e8f0;
        color: #475569;
        padding: 3px 8px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .chatbot-entity-tag i {
        font-size: 10px;
        color: var(--cb-primary);
    }

    .chatbot-related-items {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 12px;
        width: 100%;
    }

    .related-item-card {
        background: white;
        border: 1px solid var(--cb-border);
        border-radius: 12px;
        padding: 12px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--cb-text);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    .related-item-card:hover {
        border-color: var(--cb-primary);
        box-shadow: 0 8px 20px rgba(26, 111, 196, 0.12);
        transform: translateY(-3px);
        color: var(--cb-primary);
    }

    .related-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--cb-primary);
        font-size: 18px;
        flex-shrink: 0;
    }

    .related-item-info {
        flex: 1;
        overflow: hidden;
    }

    .related-item-title {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .chatbot-suggested-actions {
        padding: 10px 20px;
        display: flex;
        overflow-x: auto;
        gap: 8px;
        border-top: 1px solid var(--cb-border);
        background: white;
    }

    .chatbot-suggested-actions::-webkit-scrollbar {
        height: 0px; 
    }

    .suggested-action-btn {
        white-space: nowrap;
        background: white;
        border: 1px solid var(--cb-primary);
        color: var(--cb-primary);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .suggested-action-btn:hover {
        background: var(--cb-primary);
        color: white;
    }

    .chatbot-footer {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid var(--cb-border);
        z-index: 2;
    }

    .chatbot-input-group {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 24px;
        padding: 4px 4px 4px 15px;
        border: 1px solid transparent;
        transition: border-color 0.2s;
    }

    .chatbot-input-group:focus-within {
        border-color: var(--cb-primary);
        background: white;
        box-shadow: 0 0 0 3px rgba(26, 111, 196, 0.1);
    }

    .chatbot-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 14px;
        color: var(--cb-text);
    }

    .chatbot-input-group button {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: var(--cb-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .chatbot-input-group button:hover {
        background: #155799;
    }

    .chatbot-input-group button:disabled {
        background: #cbd5e1;
    }

    /* Modal Styling */
    .chatbot-modal {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
        padding: 20px;
    }
    .chatbot-modal.active {
        display: flex;
    }
    .chatbot-modal-content {
        background: white;
        border-radius: 15px;
        padding: 20px;
        width: 100%;
        max-width: 300px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 12px 16px;
        background: var(--cb-bot-msg);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        width: fit-content;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background: #94a3b8;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // Generate or retrieve session ID
    function getChatSessionId() {
        let sid = localStorage.getItem('ttxt_chat_session_id');
        if (!sid) {
            sid = 'user-' + Math.random().toString(36).substring(2, 10);
            localStorage.setItem('ttxt_chat_session_id', sid);
        }
        return sid;
    }

    async function resetChatSession() {
        const result = await Swal.fire({
            title: 'Làm mới trò chuyện?',
            text: "Tin nhắn sẽ bị xóa nhưng phiên làm việc vẫn được giữ nguyên.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--cb-primary)',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        });

        if (!result.isConfirmed) return;

        const sid = getChatSessionId();
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Đang làm mới...</div>';

        try {
            await fetch(`/chat/session/${sid}/clear`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
            
            messagesContainer.innerHTML = `
                <div class="chatbot-message bot-message">
                    <div class="message-content">
                        Bắt đầu cuộc trò chuyện mới. Xin chào! Tôi có thể giúp gì cho bạn?
                    </div>
                </div>
            `;
            document.getElementById('chatbot-suggested-actions').style.display = 'none';
            document.getElementById('chatbot-stage-indicator').innerText = 'Trực tuyến';
            
            Swal.fire({
                icon: 'success',
                title: 'Đã làm mới!',
                showConfirmButton: false,
                timer: 1000,
                toast: true,
                position: 'top-end'
            });
        } catch (e) { 
            console.error(e);
            Swal.fire('Lỗi', 'Không thể làm mới phiên chat.', 'error');
        }
    }

    async function deleteChatSession() {
        const result = await Swal.fire({
            title: 'Xóa toàn bộ cuộc trò chuyện?',
            text: "Dữ liệu sẽ bị xóa vĩnh viễn trên hệ thống và không thể khôi phục.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Xóa vĩnh viễn',
            cancelButtonText: 'Hủy'
        });

        if (!result.isConfirmed) return;

        const sid = getChatSessionId();
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Đang xóa dữ liệu...</div>';

        try {
            await fetch(`/chat/session/${sid}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });

            localStorage.removeItem('ttxt_chat_session_id');
            const newSid = getChatSessionId();
            
            messagesContainer.innerHTML = `
                <div class="chatbot-message bot-message">
                    <div class="message-content">
                        Dữ liệu đã được xóa sạch. Tôi sẵn sàng cho câu hỏi tiếp theo của bạn!
                    </div>
                </div>
            `;
            document.getElementById('chatbot-suggested-actions').style.display = 'none';
            document.getElementById('chatbot-stage-indicator').innerText = 'Trực tuyến';

            Swal.fire({
                icon: 'success',
                title: 'Xoá cuộc hội thoại thành công!',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        } catch (e) { 
            console.error(e);
            Swal.fire('Lỗi', 'Không thể xóa dữ liệu.', 'error');
        }
    }

    function toggleChatbot() {
        const windowEl = document.getElementById('ai-chatbot-window');
        const tooltipEl = document.querySelector('.chatbot-tooltip');
        windowEl.classList.toggle('active');
        if(windowEl.classList.contains('active')) {
            document.getElementById('chatbot-input').focus();
            scrollToBottom();
            if(tooltipEl) tooltipEl.style.display = 'none'; // Hide tooltip when open
        } else {
            if(tooltipEl) tooltipEl.style.display = 'block'; // Show tooltip when closed
        }
    }

    function scrollToBottom() {
        const body = document.getElementById('chatbot-messages');
        body.scrollTop = body.scrollHeight;
    }

    function renderMarkdownBasic(text) {
        if (!text) return '';
        let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>');
        return `<p>${html}</p>`;
    }

    function showFeedbackModal(messageId, rating) {
        document.getElementById('feedback-message-id').value = messageId;
        document.getElementById('feedback-rating').value = rating;
        document.getElementById('chatbot-feedback-modal').classList.add('active');
    }

    function hideFeedbackModal() {
        document.getElementById('chatbot-feedback-modal').classList.remove('active');
    }

    async function submitFeedbackForm() {
        const sid = getChatSessionId();
        const mid = document.getElementById('feedback-message-id').value;
        const rating = document.getElementById('feedback-rating').value;
        const type = document.getElementById('feedback-type').value;
        const comment = document.getElementById('feedback-comment').value;

        try {
            await fetch('/chat/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_id: sid,
                    message_id: mid,
                    rating: parseInt(rating),
                    feedback_type: type,
                    comment: comment
                })
            });
            Swal.fire({
                icon: 'success',
                title: 'Cảm ơn bạn!',
                text: 'Phản hồi của bạn đã được gửi thành công.',
                timer: 2000,
                showConfirmButton: false
            });
            hideFeedbackModal();
        } catch (e) {
            Swal.fire('Lỗi', 'Có lỗi xảy ra khi gửi phản hồi.', 'error');
        }
    }

    function appendMessage(sender, content, metadata = null, messageId = null) {
        const container = document.getElementById('chatbot-messages');
        const msgDiv = document.createElement('div');
        msgId = messageId || 'msg-' + Math.random().toString(36).substring(2, 7);
        msgDiv.className = `chatbot-message ${sender}-message`;
        msgDiv.setAttribute('data-id', msgId);

        let innerHTML = '';
        if (sender === 'user') {
            innerHTML = `<div class="message-content">${content}</div>`;
        } else {
            let metadataHtml = '';
            let relatedItemsHtml = '';

            if (metadata) {
                if (metadata.stage) {
                    const stageNames = {
                        'greeting': 'Greeting', 'collect_criteria': 'Collecting Criteria',
                        'show_results': 'Showing Results', 'project_detail': 'Project Detail',
                        'deep_dive': 'Deep Dive', 'compare_projects': 'Comparing',
                        'policy_procedure': 'Policy & Procedure', 'cta': 'Call to Action', 'fallback': 'Fallback'
                    };
                    document.getElementById('chatbot-stage-indicator').innerText = 'Giai đoạn: ' + (stageNames[metadata.stage] || metadata.stage);
                }

                if (metadata.entities) {
                    let entityHtml = '<div class="chatbot-metadata">';
                    const e = metadata.entities;
                    (e.industries || []).forEach(v => entityHtml += `<div class="chatbot-entity-tag"><i class="fas fa-industry"></i> ${v}</div>`);
                    (e.districts || []).forEach(v => entityHtml += `<div class="chatbot-entity-tag"><i class="fas fa-map-marker-alt"></i> ${v}</div>`);
                    (e.project_types || []).forEach(v => entityHtml += `<div class="chatbot-entity-tag"><i class="fas fa-building"></i> ${v}</div>`);
                    entityHtml += '</div>';
                    if (entityHtml !== '<div class="chatbot-metadata"></div>') metadataHtml = entityHtml;
                }

                if (metadata.related_items && metadata.related_items.length > 0) {
                    relatedItemsHtml = '<div class="chatbot-related-items">';
                    metadata.related_items.forEach(item => {
                        let link = item.url || (item.type === 'project' ? `/project-detail/${item.id}` : '#');
                        relatedItemsHtml += `
                            <a href="${link}" target="_blank" class="related-item-card">
                                <div class="related-item-icon">
                                    <i class="${item.type === 'project' ? 'fas fa-city' : 'fas fa-file-alt'}"></i>
                                </div>
                                <div class="related-item-info">
                                    <div class="related-item-title">${item.name || item.title || 'Dự án'}</div>
                                    <div class="text-muted" style="font-size: 11px;">${item.type === 'project' ? 'Xem chi tiết dự án' : 'Xem tài liệu'}</div>
                                </div>
                            </a>
                        `;
                    });
                    relatedItemsHtml += '</div>';
                }
            }

            innerHTML = `
                <div class="message-content">
                    ${renderMarkdownBasic(content)}
                    ${metadataHtml}
                </div>
                ${relatedItemsHtml}
                <div class="chatbot-message-feedback">
                    <button class="feedback-btn" onclick="showFeedbackModal('${msgId}', 5)" title="Hữu ích"><i class="far fa-thumbs-up"></i></button>
                    <button class="feedback-btn" onclick="showFeedbackModal('${msgId}', 1)" title="Không hữu ích"><i class="far fa-thumbs-down"></i></button>
                </div>
            `;
        }

        msgDiv.innerHTML = innerHTML;
        container.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendTypingIndicator() {
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'chatbot-message bot-message typing-indicator-wrapper';
        div.id = 'chatbot-typing-indicator';
        div.innerHTML = `<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
        container.appendChild(div); scrollToBottom();
    }

    function removeTypingIndicator() {
        const el = document.getElementById('chatbot-typing-indicator');
        if (el) el.remove();
    }

    function renderSuggestedActions(actions) {
        const container = document.getElementById('chatbot-suggested-actions');
        if (!actions || actions.length === 0) { container.style.display = 'none'; return; }
        container.innerHTML = '';
        actions.forEach(a => {
            const btn = document.createElement('button');
            btn.className = 'suggested-action-btn'; btn.innerText = a.label;
            btn.onclick = () => { document.getElementById('chatbot-input').value = a.label; document.getElementById('chatbot-form').dispatchEvent(new Event('submit')); };
            container.appendChild(btn);
        });
        container.style.display = 'flex';
    }

    async function loadChatHistory() {
        const sid = getChatSessionId();
        try {
            const res = await fetch(`/chat/session/${sid}`);
            if (res.ok) {
                const data = await res.json();
                if (data && data.conversation && data.conversation.messages.length > 0) {
                    document.getElementById('chatbot-messages').innerHTML = '';
                    data.conversation.messages.forEach(m => appendMessage(m.role === 'assistant' ? 'bot' : 'user', m.content, m.metadata, m.id));
                }
            }
        } catch (e) { console.error('History error:', e); }
    }

    async function fetchHealthAndModels() {
        // Health Status
        try {
            const hRes = await fetch('/chat/health');
            if (hRes.ok) {
                const hData = await hRes.json();
                const dot = document.getElementById('chatbot-status-dot');
                if (hData.status === 'healthy') {
                    dot.style.backgroundColor = '#22c55e'; // Green
                    dot.title = 'Hệ thống ổn định';
                } else {
                    dot.style.backgroundColor = '#ef4444'; // Red
                    dot.title = 'Hệ thống đang gặp sự cố';
                }
            }
        } catch (e) { console.error('Health check failed', e); }

        // Models List
        try {
            const mRes = await fetch('/chat/models');
            if (mRes.ok) {
                const mData = await mRes.json();
                const select = document.getElementById('chatbot-model-select');
                if (mData.models && mData.models.length > 0) {
                    mData.models.forEach(m => {
                        const opt = document.createElement('option');
                        opt.value = m.id || m.name;
                        opt.innerText = m.name || m.id;
                        opt.style.color = '#334155';
                        select.appendChild(opt);
                    });
                }
            }
        } catch (e) { console.error('Failed to fetch models', e); }
    }

    async function sendChatMessage(e) {
        if(e) e.preventDefault();
        const input = document.getElementById('chatbot-input');
        const btn = document.getElementById('chatbot-send-btn');
        const modelSelect = document.getElementById('chatbot-model-select');
        const msg = input.value.trim();
        if (!msg) return;

        appendMessage('user', msg);
        input.value = ''; btn.disabled = true;
        document.getElementById('chatbot-suggested-actions').style.display = 'none';
        appendTypingIndicator();

        try {
            const res = await fetch('/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ 
                    session_id: getChatSessionId(), 
                    message: msg,
                    model: modelSelect.value || undefined
                })
            });
            removeTypingIndicator(); btn.disabled = false;
            if (res.ok) {
                const data = await res.json();
                appendMessage('bot', data.response, { stage: data.stage, entities: data.entities, related_items: data.related_items }, data.message_id);
                if (data.suggested_actions) renderSuggestedActions(data.suggested_actions);
            } else {
                appendMessage('bot', 'Xin lỗi, đã có lỗi kết nối.');
            }
        } catch (e) { removeTypingIndicator(); btn.disabled = false; appendMessage('bot', 'Lỗi mạng.'); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadChatHistory();
        fetchHealthAndModels();
    });
</script>
