@if(!empty($parentData) && !empty($draftData))
    <button type="button" class="btn btn-sm fw-bold btn-warning" data-toggle="modal" data-target="#diffModal">
        <i class="fa fa-exchange-alt" aria-hidden="true"></i> Xem thay đổi
    </button>
    
    <div class="modal fade" id="diffModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">So sánh thay đổi (Bản gốc vs Bản nháp)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="diffViewer"></div>
                </div>
            </div>
        </div>
    </div>

    @once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsdiff/5.1.0/diff.min.js"></script>
    <style>
        .diff-added { background-color: #e6ffed; color: #22863a; text-decoration: none; font-weight: bold; }
        .diff-removed { background-color: #ffeef0; color: #cb2431; text-decoration: line-through; }
        .diff-container { border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px; overflow: hidden; }
        .diff-header { background: #f6f8fa; padding: 10px; font-weight: bold; border-bottom: 1px solid #ddd; }
        .diff-col { white-space: pre-wrap; font-family: 'Courier New', Courier, monospace; word-break: break-word; font-size: 14px; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#diffModal').on('show.bs.modal', function () {
                const viewer = document.getElementById('diffViewer');
                if (viewer.innerHTML.trim() !== '') return;

                const parentData = @json($parentData);
                const draftData = @json($draftData);
                
                let html = '';
                function extractPathsFromRawText(rawText) {
                    if (!rawText) return [];
                    let match = rawText.match(/\/(uploads|storage)\/[^\s;"']+/gi);
                    if (!match) return [];
                    return [...new Set(match)];
                }

                function renderImagesFromText(rawText) {
                    let paths = extractPathsFromRawText(rawText);
                    let images = paths.filter(p => p.match(/\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i));
                    
                    if (images.length === 0) return '';
                    
                    let imgHtml = '<div class="mt-2 text-center">';
                    images.forEach(src => {
                        imgHtml += `<img src="${src}" class="img-fluid rounded border shadow-sm mb-2" style="max-height: 250px; margin-right: 10px;" onerror="this.style.display='none'">`;
                    });
                    imgHtml += '</div>';
                    return imgHtml;
                }

                function renderFilesFromText(rawText) {
                    let paths = extractPathsFromRawText(rawText);
                    let files = paths.filter(p => !p.match(/\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i));
                    
                    if (files.length === 0) return '';
                    
                    let html = '<div class="mt-3 d-flex flex-column">';
                    files.forEach(src => {
                        let decodedSrc = src;
                        try { decodedSrc = decodeURIComponent(src); } catch(e) {}
                        let fileName = decodedSrc.split('/').pop();
                        
                        let icon = 'fa-file';
                        if (src.toLowerCase().endsWith('.pdf')) icon = 'fa-file-pdf text-danger';
                        else if (src.toLowerCase().match(/\.(doc|docx)$/)) icon = 'fa-file-word text-primary';
                        else if (src.toLowerCase().match(/\.(xls|xlsx)$/)) icon = 'fa-file-excel text-success';
                        
                        html += `<a href="${src}" target="_blank" class="btn btn-light border shadow-sm mb-2 text-left" style="white-space: normal; word-break: break-all;" title="${fileName}">
                                    <i class="fas ${icon} mr-2"></i> <strong>${fileName}</strong>
                                 </a>`;
                    });
                    html += '</div>';
                    return html;
                }

                function formatTextForDiff(text) {
                    if (!text) return '';
                    let str = String(text);
                    
                    try {
                        let arr = JSON.parse(str);
                        if (Array.isArray(arr)) {
                            str = arr.join('\n');
                        } else if (typeof arr === 'object') {
                            str = JSON.stringify(arr, null, 2);
                        }
                    } catch (e) {
                        if (str.includes(';')) {
                            // Check if it's a semicolon separated list of paths
                            // Avoid splitting if it contains HTML tags or HTML entities to prevent breaking Vietnamese characters
                            if (!/<[a-z][\s\S]*>/i.test(str) && !/&[a-z0-9#]+;/i.test(str)) {
                                str = str.split(';').map(s => s.trim()).filter(s => s).join('\n');
                            }
                        }
                    }
                    
                    try {
                        // Decode URL-encoded characters (like %20 to space, %C3%A1 to á)
                        str = decodeURIComponent(str);
                    } catch (e) {}
                    
                    // Strip HTML tags for cleaner text diff, preserving newlines for block elements
                    if (/<[a-z][\s\S]*>/i.test(str) || /&[a-z0-9#]+;/i.test(str)) {
                        let tmp = str;
                        tmp = tmp.replace(/<br\s*[\/]?>/gi, '\n');
                        tmp = tmp.replace(/<\/p>|<\/div>|<\/li>|<\/h[1-6]>/gi, '\n');
                        tmp = tmp.replace(/<[^>]+>/g, '');
                        
                        // Decode HTML entities
                        let doc = new DOMParser().parseFromString(tmp, "text/html");
                        str = doc.documentElement.textContent;
                    }
                    
                    return str;
                }

                for (const key in parentData) {
                    const oldRawText = parentData[key] || '';
                    const newRawText = draftData[key] || '';
                    
                    if (oldRawText === newRawText) continue;
                    
                    const oldText = formatTextForDiff(oldRawText);
                    const newText = formatTextForDiff(newRawText);
                    
                    const diff = Diff.diffWordsWithSpace(String(oldText), String(newText));
                    
                    let leftTextHtml = '';
                    let rightTextHtml = '';
                    
                    diff.forEach((part) => {
                        let val = part.value.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
                        if (part.added) {
                            rightTextHtml += `<ins class="diff-added">${val}</ins>`;
                        } else if (part.removed) {
                            leftTextHtml += `<del class="diff-removed">${val}</del>`;
                        } else {
                            leftTextHtml += `<span>${val}</span>`;
                            rightTextHtml += `<span>${val}</span>`;
                        }
                    });
                    
                    let leftImagesHtml = renderImagesFromText(oldRawText);
                    let rightImagesHtml = renderImagesFromText(newRawText);
                    let leftFilesHtml = renderFilesFromText(oldRawText);
                    let rightFilesHtml = renderFilesFromText(newRawText);
                    let hasAttachments = leftImagesHtml !== '' || rightImagesHtml !== '' || leftFilesHtml !== '' || rightFilesHtml !== '';
                    
                    let attachmentRowHtml = '';
                    if (hasAttachments) {
                        attachmentRowHtml = `
                            <div class="p-3 border-right diff-col bg-light">
                                ${leftImagesHtml}
                                ${leftFilesHtml}
                            </div>
                            <div class="p-3 diff-col">
                                ${rightImagesHtml}
                                ${rightFilesHtml}
                            </div>
                        `;
                    }
                    
                    html += `
                        <div class="diff-container">
                            <div class="diff-header">${key}</div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                <div class="p-3 border-right diff-col bg-light ${hasAttachments ? 'border-bottom' : ''}">
                                    <div class="text-muted mb-2 border-bottom pb-1"><strong><i class="fas fa-file-alt"></i> Bản gốc</strong></div>
                                    <div>${leftTextHtml}</div>
                                </div>
                                <div class="p-3 diff-col ${hasAttachments ? 'border-bottom' : ''}">
                                    <div class="text-primary mb-2 border-bottom pb-1"><strong><i class="fas fa-edit"></i> Bản nháp (Đang sửa)</strong></div>
                                    <div>${rightTextHtml}</div>
                                </div>
                                ${attachmentRowHtml}
                            </div>
                        </div>
                    `;
                }
                
                if (html === '') {
                    html = '<div class="alert alert-info">Không có sự khác biệt về dữ liệu văn bản giữa bản gốc và bản nháp.</div>';
                }
                
                viewer.innerHTML = html;
            });
        });
    </script>
    @endonce
@endif
