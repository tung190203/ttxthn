{{-- Widget Thông báo thông minh: Rung rinh gây chú ý khi đổi dự án mới --}}
<style>
    /* ===== 1. KEYFRAME ANIMATION RUNG RINH GÂY CHÚ Ý ===== */
    @keyframes portalToastWiggle {
        0% { transform: translateY(0) rotate(0deg) scale(1); }
        15% { transform: translateY(-5px) rotate(-2.8deg) scale(1.02); }
        30% { transform: translateY(0px) rotate(2.4deg) scale(1.02); }
        45% { transform: translateY(-3px) rotate(-1.6deg) scale(1.01); }
        60% { transform: translateY(0px) rotate(1.2deg) scale(1.01); }
        75% { transform: translateY(-1px) rotate(-0.5deg) scale(1); }
        100% { transform: translateY(0) rotate(0deg) scale(1); }
    }

    @keyframes portalBadgeRing {
        0%, 100% { transform: rotate(0); }
        15% { transform: rotate(24deg); }
        30% { transform: rotate(-20deg); }
        45% { transform: rotate(16deg); }
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
    }
    .portal-toast-btn-primary {
        background: #1169ff;
        color: #ffffff !important;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
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
        padding: 6px 14px;
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

    @media (max-width: 767.98px) {
        .portal-toast {
            left: 12px !important;
            bottom: 12px !important;
            max-width: calc(100% - 24px);
            width: auto;
            right: 12px !important;
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
        <span id="guestNotiDistrict">TP. Hà Nội</span>
    </div>

    <div class="portal-toast-actions">
        <button type="button" id="btnGuestNotiMap" class="portal-toast-btn-primary" style="display: none;">
            <span id="btnGuestNotiMapText">Xem trên bản đồ</span>
        </button>
        <a href="#" id="btnGuestNotiDetail" class="portal-toast-btn-subtle" target="_blank">
            <span id="btnGuestNotiDetailText">Chi tiết</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let updateItems = [];
    let currentIndex = 0;
    let cycleTimer = null;
    let isUserInteracting = false;

    const notiEl = document.getElementById('guestProjectNoti');

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
        
        // Loại bỏ emoji nếu có trong text
        let cleanBadge = (item.badge_text || 'Cập nhật')
            .replace(/[\u{1F300}-\u{1F9FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/gu, '')
            .trim();
        document.getElementById('guestNotiBadgeText').textContent = cleanBadge;
        
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

        // Meta (Địa điểm, trạng thái...) - Không dùng icon lòe loẹt
        document.getElementById('guestNotiDistrict').textContent = item.district || 'TP. Hà Nội';

        document.getElementById('guestNotiTime').textContent = item.time_ago || 'Vừa xong';
        
        // Nút xem chi tiết / VR Tour
        const btnDetail = document.getElementById('btnGuestNotiDetail');
        btnDetail.href = item.url || '#';
        btnDetail.target = '_blank';
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
        if (!item || !item.lat || !item.lng) {
            if (item.url) window.location.href = item.url;
            return;
        }

        // 1. Nếu đang ở Trang chủ có sẵn hàm portalFlyToProject
        if (typeof window.portalFlyToProject === 'function') {
            window.portalFlyToProject(item);
        } else if (typeof window.map !== 'undefined' && window.map) {
            window.map.flyTo([item.lat, item.lng], 16, { animate: true, duration: 1.0 });
        } else {
            // 2. Nếu đang ở trang con khác: lưu vào sessionStorage và điều hướng về trang chủ
            try {
                sessionStorage.setItem('pending_focus_project', JSON.stringify(item));
            } catch(e){}
            const targetMapUrl = item.map_url || ("{{ url('/') }}?lat=" + item.lat + "&lng=" + item.lng + "&zoom=16");
            window.location.href = targetMapUrl;
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
    }

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
                    showNoti();
                }
            })
            .catch(function(err) {
                console.warn('Lỗi tải thông báo cập nhật:', err);
            });
    }, 1500);
});
</script>
