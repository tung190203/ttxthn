{{-- Widget Thông báo thông minh: Rung rinh thu hút chú ý + Bảng tin New Feed trực quan --}}
<style>
    /* ===== 1. KEYFRAME ANIMATION RUNG RINH GÂY CHÚ Ý ===== */
    @keyframes portalToastWiggle {
        0% { transform: translateY(0) rotate(0deg) scale(1); }
        15% { transform: translateY(-6px) rotate(-3deg) scale(1.02); }
        30% { transform: translateY(0px) rotate(2.5deg) scale(1.02); }
        45% { transform: translateY(-3px) rotate(-1.8deg) scale(1.01); }
        60% { transform: translateY(0px) rotate(1.2deg) scale(1.01); }
        75% { transform: translateY(-1px) rotate(-0.6deg) scale(1); }
        100% { transform: translateY(0) rotate(0deg) scale(1); }
    }

    @keyframes portalBadgeRing {
        0%, 100% { transform: rotate(0); }
        15% { transform: rotate(26deg); }
        30% { transform: rotate(-22deg); }
        45% { transform: rotate(18deg); }
        60% { transform: rotate(-12deg); }
        75% { transform: rotate(6deg); }
    }

    @keyframes portalGlowPulse {
        0%, 100% {
            box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.22), 0 4px 12px -2px rgba(15, 23, 42, 0.08);
        }
        50% {
            box-shadow: 0 16px 36px -2px rgba(17, 105, 255, 0.45), 0 0 0 4px rgba(17, 105, 255, 0.2);
        }
    }

    /* ===== 2. POPUP TOAST NOTIFICATION ===== */
    .portal-toast {
        position: fixed !important;
        bottom: 24px !important;
        left: 24px !important;
        z-index: 99999999 !important;
        max-width: 390px;
        width: calc(100% - 48px);
        background: #ffffff !important;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        border-left: 5px solid #1169ff;
        box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.22), 0 4px 12px -2px rgba(15, 23, 42, 0.08);
        padding: 14px 16px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        display: none;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-left-color 0.3s ease;
        pointer-events: auto;
    }
    .portal-toast.show {
        display: block !important;
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    .portal-toast.shaking {
        animation: portalToastWiggle 0.85s cubic-bezier(0.36, 0.07, 0.19, 0.97) both, portalGlowPulse 0.85s ease;
    }
    .portal-toast.shaking .portal-toast-badge i {
        animation: portalBadgeRing 0.85s ease;
        display: inline-block;
    }

    .portal-toast-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .portal-toast-badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .portal-toast-time {
        font-size: 11.5px;
        color: #94a3b8;
        font-weight: 500;
    }
    .portal-toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 6px;
        transition: all 0.2s;
    }
    .portal-toast-close:hover {
        color: #0f172a;
        background: #f1f5f9;
    }
    .portal-toast-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .portal-toast-summary {
        font-size: 12.5px;
        color: #334155;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .portal-toast-meta {
        font-size: 11.5px;
        color: #64748b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .portal-toast-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .portal-toast-btn-primary {
        background: #1169ff;
        color: #ffffff !important;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.2s;
    }
    .portal-toast-btn-primary:hover {
        background: #0d5be3;
    }
    .portal-toast-btn-subtle {
        background: #f8fafc;
        color: #334155 !important;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none !important;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
    }
    .portal-toast-btn-subtle:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .portal-toast-btn-feed {
        background: #eff6ff;
        color: #1d4ed8 !important;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 6px;
        border: 1px solid #bfdbfe;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: auto;
        transition: all 0.2s;
    }
    .portal-toast-btn-feed:hover {
        background: #dbeafe;
        color: #1e40af !important;
    }

    /* Nút kích hoạt New Feed nổi khi đóng popup */
    .portal-feed-launcher-btn {
        position: fixed !important;
        bottom: 24px !important;
        left: 24px !important;
        z-index: 99999998 !important;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        border-left: 4px solid #1169ff;
        border-radius: 30px;
        padding: 8px 16px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: none;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }
    .portal-feed-launcher-btn:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 105, 255, 0.25);
    }
    .portal-feed-launcher-btn .badge-count {
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
    }

    /* ===== 3. NEW FEED DRAWER (BẢNG TIN CẬP NHẬT) ===== */
    .portal-feed-drawer-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(3px);
        z-index: 999999998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .portal-feed-drawer-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    .portal-feed-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        max-width: 480px;
        background: #f8fafc;
        z-index: 999999999;
        box-shadow: -8px 0 32px rgba(15, 23, 42, 0.2);
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .portal-feed-drawer.active {
        transform: translateX(0);
    }

    .portal-feed-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    .portal-feed-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .portal-feed-close {
        background: none;
        border: none;
        color: #64748b;
        font-size: 20px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        line-height: 1;
        transition: all 0.2s;
    }
    .portal-feed-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .portal-feed-filters {
        padding: 10px 16px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 6px;
        overflow-x: auto;
        flex-shrink: 0;
    }
    .portal-feed-filter-btn {
        background: #f1f5f9;
        border: 1px solid transparent;
        color: #475569;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .portal-feed-filter-btn.active, .portal-feed-filter-btn:hover {
        background: #1169ff;
        color: #ffffff;
    }

    .portal-feed-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .portal-feed-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .portal-feed-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
    }
    .portal-feed-card-img-wrap {
        position: relative;
        width: 100%;
        height: 140px;
        background: #0f172a;
        overflow: hidden;
    }
    .portal-feed-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.9;
        transition: transform 0.3s ease;
    }
    .portal-feed-card:hover .portal-feed-card-img {
        transform: scale(1.04);
    }
    .portal-feed-card-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(4px);
    }
    .portal-feed-card-time {
        position: absolute;
        bottom: 8px;
        right: 10px;
        background: rgba(15, 23, 42, 0.7);
        color: #fff;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .portal-feed-card-content {
        padding: 12px 14px;
    }
    .portal-feed-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.35;
    }
    .portal-feed-card-summary {
        font-size: 12px;
        color: #475569;
        line-height: 1.4;
        margin-bottom: 8px;
    }
    .portal-feed-card-meta {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 10px;
    }
    .portal-feed-card-actions {
        display: flex;
        gap: 8px;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
    }

    @media (max-width: 767.98px) {
        .portal-toast {
            left: 12px !important;
            bottom: 12px !important;
            max-width: calc(100% - 24px);
            width: auto;
            right: 12px !important;
        }
        .portal-feed-drawer {
            max-width: 100%;
        }
    }
</style>

<!-- POPUP TOAST NOTIFICATION (HIỂN THỊ RUNG RINH) -->
<div id="guestProjectNoti" class="portal-toast" aria-live="polite">
    <div class="portal-toast-header">
        <div class="portal-toast-badge" id="guestNotiBadge">
            <i class="fas fa-bell" id="guestNotiBadgeIcon"></i>
            <span id="guestNotiBadgeText">Thông báo mới</span>
        </div>
        <div style="display: flex; align-items: center;">
            <span class="portal-toast-time" id="guestNotiTime">Vừa xong</span>
            <button type="button" class="portal-toast-close" id="btnCloseGuestNoti" aria-label="Đóng thông báo">&times;</button>
        </div>
    </div>
    
    <div class="portal-toast-title" id="guestNotiName">Đang tải thông tin...</div>
    <div class="portal-toast-summary" id="guestNotiSummary" style="display: none;"></div>
    
    <div class="portal-toast-meta">
        <span id="guestNotiDistrict"><i class="fas fa-map-marker-alt" style="color: #64748b;"></i> TP. Hà Nội</span>
    </div>

    <div class="portal-toast-actions">
        <button type="button" id="btnGuestNotiMap" class="portal-toast-btn-primary" style="display: none;">
            <i class="fas fa-map-marker-alt"></i> <span id="btnGuestNotiMapText">Xem trên bản đồ</span>
        </button>
        <a href="#" id="btnGuestNotiDetail" class="portal-toast-btn-subtle" target="_blank">
            <span id="btnGuestNotiDetailText">Chi tiết</span> <i class="fas fa-arrow-right" style="font-size: 10px;"></i>
        </a>
        <button type="button" class="portal-toast-btn-feed" id="btnOpenFeedFromToast" title="Xem tất cả cập nhật dạng Bảng tin New Feed">
            <i class="fas fa-newspaper"></i> Bảng tin
        </button>
    </div>
</div>

<!-- NÚT MỞ BẢNG TIN NEW FEED KHI ĐÓNG POPUP -->
<button type="button" id="btnPortalFeedLauncher" class="portal-feed-launcher-btn" title="Xem bảng tin cập nhật dự án">
    <i class="fas fa-bolt text-warning"></i>
    <span>Bảng tin cập nhật</span>
    <span class="badge-count" id="feedLauncherCount">0</span>
</button>

<!-- NEW FEED DRAWER (BẢNG TIN CẬP NHẬT TRỰC QUAN CHO TẤT CẢ USER ĐẶC BIỆT SIGNED IN) -->
<div class="portal-feed-drawer-backdrop" id="portalFeedBackdrop"></div>
<div class="portal-feed-drawer" id="portalFeedDrawer">
    <div class="portal-feed-header">
        <h5 class="portal-feed-title">
            <i class="fas fa-newspaper text-primary"></i>
            <span>Bảng tin cập nhật</span>
            <span class="badge bg-primary rounded-pill ms-1" style="font-size: 11px;" id="feedTotalBadge">0</span>
        </h5>
        <button type="button" class="portal-feed-close" id="btnCloseFeedDrawer" aria-label="Đóng">&times;</button>
    </div>
    
    <div class="portal-feed-filters">
        <button type="button" class="portal-feed-filter-btn active" data-filter="all">Tất cả</button>
        <button type="button" class="portal-feed-filter-btn" data-filter="interested_project">⭐ Theo dõi</button>
        <button type="button" class="portal-feed-filter-btn" data-filter="project_vrtour">🕶️ VR Tour</button>
        <button type="button" class="portal-feed-filter-btn" data-filter="project_info">📋 Dự án</button>
        <button type="button" class="portal-feed-filter-btn" data-filter="investment_guide">📘 Cẩm nang</button>
    </div>

    <div class="portal-feed-body" id="portalFeedCardsContainer">
        <!-- Feed cards render qua JS -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let updateItems = [];
    let currentIndex = 0;
    let cycleTimer = null;
    let isUserInteracting = false;
    let currentFilter = 'all';

    const notiEl = document.getElementById('guestProjectNoti');
    const launcherBtn = document.getElementById('btnPortalFeedLauncher');
    const feedDrawer = document.getElementById('portalFeedDrawer');
    const feedBackdrop = document.getElementById('portalFeedBackdrop');
    const feedContainer = document.getElementById('portalFeedCardsContainer');

    // Kích hoạt animation rung rinh lắc lư gây chú ý
    function triggerWiggle() {
        if (!notiEl) return;
        notiEl.classList.remove('shaking');
        // Force reflow để restart animation
        void notiEl.offsetWidth;
        notiEl.classList.add('shaking');
    }

    function renderItem(item) {
        if (!item || !notiEl) return;

        // Cập nhật viền & badge theo chủ đề
        const themeColor = item.badge_color || '#1169ff';
        notiEl.style.borderLeftColor = themeColor;

        const badgeEl = document.getElementById('guestNotiBadge');
        badgeEl.style.color = themeColor;
        document.getElementById('guestNotiBadgeText').textContent = item.badge_text || 'Thông báo mới';
        
        const badgeIconEl = document.getElementById('guestNotiBadgeIcon');
        badgeIconEl.className = 'fas ' + (item.badge_icon || 'fa-bell');

        // Tên đối tượng & nội dung tóm tắt
        document.getElementById('guestNotiName').textContent = item.name;
        
        const summaryEl = document.getElementById('guestNotiSummary');
        if (item.summary) {
            summaryEl.textContent = item.summary;
            summaryEl.style.display = 'block';
        } else {
            summaryEl.style.display = 'none';
        }

        // Meta (Địa điểm, trạng thái...)
        let metaHtml = '';
        if (item.type === 'investment_guide') {
            metaHtml = '<i class="fas fa-file-alt" style="color: #10b981; margin-right: 4px;"></i> ' + (item.district || 'Cẩm nang hướng dẫn đầu tư');
        } else {
            metaHtml = '<i class="fas fa-map-marker-alt" style="color: #64748b; margin-right: 4px;"></i> ' + (item.district || 'TP. Hà Nội');
        }
        document.getElementById('guestNotiDistrict').innerHTML = metaHtml;

        document.getElementById('guestNotiTime').textContent = item.time_ago || 'Vừa xong';
        
        // Nút xem chi tiết
        const btnDetail = document.getElementById('btnGuestNotiDetail');
        btnDetail.href = item.url || '#';
        document.getElementById('btnGuestNotiDetailText').textContent = item.btn_detail_text || 'Chi tiết';

        // Nút bản đồ
        const btnMap = document.getElementById('btnGuestNotiMap');
        if (item.lat && item.lng) {
            btnMap.style.display = 'inline-flex';
            document.getElementById('btnGuestNotiMapText').textContent = item.btn_action_text || 'Xem trên bản đồ';
            btnMap.onclick = function(e) {
                e.preventDefault();
                flyToProject(item);
            };
        } else {
            btnMap.style.display = 'none';
        }
    }

    function flyToProject(item) {
        if (typeof map !== 'undefined' && map && item.lat && item.lng) {
            closeFeedDrawer();
            if (typeof window.handleStartProjectIntro === 'function') window.handleStartProjectIntro();
            if (typeof window.unlockMap === 'function') window.unlockMap();
            const introLayer = document.getElementById('mapIntroLayer');
            if (introLayer) introLayer.classList.add('is-hidden');
            const homePage = document.querySelector('.home-page');
            if (homePage) homePage.classList.remove('home-map-locked');

            try {
                map.flyTo([item.lat, item.lng], 16, { animate: true, duration: 1.2 });
                if (typeof showProjectBoundary === 'function') {
                    showProjectBoundary(item);
                }
            } catch(err) {
                console.log('Lỗi flyTo:', err);
            }
        } else if (item.url) {
            window.location.href = item.url;
        }
    }

    function showNoti() {
        if (!notiEl || updateItems.length === 0) return;
        renderItem(updateItems[currentIndex]);
        
        notiEl.classList.add('show');
        triggerWiggle(); // Rung rinh lần đầu khi vừa xuất hiện

        // Tự động xoay vòng sau mỗi 7.5 giây: mỗi lần đổi DA lại RUNG RINH
        if (updateItems.length > 1) {
            clearInterval(cycleTimer);
            cycleTimer = setInterval(function() {
                if (isUserInteracting) return;

                notiEl.style.opacity = '0.2';
                notiEl.style.transform = 'translateY(12px) scale(0.98)';
                
                setTimeout(function() {
                    currentIndex = (currentIndex + 1) % updateItems.length;
                    renderItem(updateItems[currentIndex]);
                    notiEl.style.opacity = '1';
                    notiEl.style.transform = 'translateY(0) scale(1)';
                    triggerWiggle(); // RUNG RINH KHI HIỆN DỰ ÁN MỚI
                }, 280);
            }, 7500);
        }
    }

    function closeNoti() {
        if (!notiEl) return;
        notiEl.classList.remove('show');
        clearInterval(cycleTimer);
        // Khi đóng popup, hiển thị nút Bảng tin nhỏ ở góc để user vẫn bấm xem lại được
        if (launcherBtn && updateItems.length > 0) {
            launcherBtn.style.display = 'inline-flex';
        }
    }

    // ===== RENDER BẢNG TIN NEW FEED =====
    function renderNewFeed(filterType) {
        if (!feedContainer) return;
        feedContainer.innerHTML = '';

        let filtered = updateItems;
        if (filterType && filterType !== 'all') {
            filtered = updateItems.filter(i => i.type === filterType);
        }

        if (filtered.length === 0) {
            feedContainer.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                    <p class="mb-0">Không có bản tin nào thuộc mục này.</p>
                </div>
            `;
            return;
        }

        filtered.forEach(function(item) {
            const card = document.createElement('div');
            card.className = 'portal-feed-card';
            
            const badgeBg = item.badge_color || '#1169ff';
            const imgUrl = item.image || '{{ asset("images/project-1.jpg") }}';
            
            let actionBtnHtml = '';
            if (item.lat && item.lng) {
                actionBtnHtml = `
                    <button type="button" class="portal-toast-btn-primary btn-feed-map-action" style="font-size: 11.5px; padding: 5px 10px;">
                        <i class="fas fa-map-marker-alt"></i> Xem trên bản đồ
                    </button>
                `;
            }

            card.innerHTML = `
                <div class="portal-feed-card-img-wrap">
                    <img src="${imgUrl}" alt="${item.name}" class="portal-feed-card-img" onerror="this.src='{{ asset('images/intro.png') }}'">
                    <span class="portal-feed-card-badge" style="background: ${badgeBg};">
                        <i class="fas ${item.badge_icon || 'fa-bell'} me-1"></i> ${item.badge_text || 'Cập nhật'}
                    </span>
                    <span class="portal-feed-card-time"><i class="far fa-clock me-1"></i>${item.time_ago || 'Mới'}</span>
                </div>
                <div class="portal-feed-card-content">
                    <h6 class="portal-feed-card-title">
                        <a href="${item.url || '#'}" target="_blank" class="text-dark text-decoration-none">${item.name}</a>
                    </h6>
                    <div class="portal-feed-card-summary">${item.summary || ''}</div>
                    <div class="portal-feed-card-meta">
                        <i class="fas ${item.type === 'investment_guide' ? 'fa-file-alt text-success' : 'fa-map-marker-alt text-primary'} me-1"></i>
                        ${item.district || ''}
                    </div>
                    <div class="portal-feed-card-actions">
                        ${actionBtnHtml}
                        <a href="${item.url || '#'}" target="_blank" class="portal-toast-btn-subtle" style="font-size: 11.5px; padding: 5px 10px;">
                            ${item.btn_detail_text || 'Chi tiết'} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            `;

            if (item.lat && item.lng) {
                const mapBtn = card.querySelector('.btn-feed-map-action');
                if (mapBtn) {
                    mapBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        flyToProject(item);
                    });
                }
            }

            feedContainer.appendChild(card);
        });
    }

    function openFeedDrawer() {
        if (!feedDrawer || !feedBackdrop) return;
        renderNewFeed(currentFilter);
        feedDrawer.classList.add('active');
        feedBackdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFeedDrawer() {
        if (!feedDrawer || !feedBackdrop) return;
        feedDrawer.classList.remove('active');
        feedBackdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Sự kiện mở/đóng New Feed
    const btnOpenToast = document.getElementById('btnOpenFeedFromToast');
    if (btnOpenToast) btnOpenToast.addEventListener('click', openFeedDrawer);

    if (launcherBtn) launcherBtn.addEventListener('click', openFeedDrawer);

    const btnCloseDrawer = document.getElementById('btnCloseFeedDrawer');
    if (btnCloseDrawer) btnCloseDrawer.addEventListener('click', closeFeedDrawer);

    if (feedBackdrop) feedBackdrop.addEventListener('click', closeFeedDrawer);

    // Xử lý bộ lọc tabs của New Feed
    document.querySelectorAll('.portal-feed-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.portal-feed-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            renderNewFeed(currentFilter);
        });
    });

    // Hover tạm dừng chuyển đổi
    if (notiEl) {
        notiEl.addEventListener('mouseenter', function() {
            isUserInteracting = true;
            notiEl.classList.remove('shaking');
        });
        notiEl.addEventListener('mouseleave', function() {
            isUserInteracting = false;
        });

        const btnClose = document.getElementById('btnCloseGuestNoti');
        if (btnClose) btnClose.addEventListener('click', closeNoti);
    }

    // Tải dữ liệu API
    setTimeout(function() {
        const apiUrl = "{{ url('/api/public/recent-updates') }}?t=" + Date.now();
        fetch(apiUrl)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                const items = Array.isArray(data) ? data : (data.items || []);
                if (Array.isArray(items) && items.length > 0) {
                    updateItems = items;

                    // Cập nhật số lượng lên badge
                    const countBadge = document.getElementById('feedTotalBadge');
                    if (countBadge) countBadge.textContent = items.length;
                    const launcherCount = document.getElementById('feedLauncherCount');
                    if (launcherCount) launcherCount.textContent = items.length;

                    showNoti();
                }
            })
            .catch(function(err) {
                console.warn('Lỗi tải thông báo cập nhật:', err);
            });
    }, 1500);
});
</script>
