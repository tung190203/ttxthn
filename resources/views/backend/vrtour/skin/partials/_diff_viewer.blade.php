<div class="modal fade" id="diffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    So sánh thay đổi (Bản gốc vs Bản nháp)
                </h5>

                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
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
.diff-added{
    background:#e6ffed;
    color:#22863a;
    font-weight:bold;
}

.diff-removed{
    background:#ffeef0;
    color:#cb2431;
    text-decoration:line-through;
}

.diff-container{
    border:1px solid #ddd;
    margin-bottom:20px;
    border-radius:4px;
    overflow:hidden;
}

.diff-header{
    background:#f6f8fa;
    padding:10px;
    font-weight:bold;
    border-bottom:1px solid #ddd;
}

.diff-col{
    white-space:pre-wrap;
    word-break:break-word;
    font-size:14px;
    font-family:Courier New;
}
</style>

<script>
function extractPathsFromRawText(rawText){

    if(!rawText) return [];

    let match = rawText.match(/\/(uploads|storage)\/[^\s;"']+/gi);

    if(!match) return [];

    return [...new Set(match)];
}

function renderImagesFromText(rawText){

    let paths = extractPathsFromRawText(rawText);

    let images = paths.filter(p =>
        /\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i.test(p)
    );

    if(images.length===0) return '';

    let html = '<div class="mt-2 text-center">';

    images.forEach(src=>{

        html += `
            <img
                src="${src}"
                class="img-fluid rounded border shadow-sm mb-2"
                style="max-height:250px"
                onerror="this.style.display='none'"
            >
        `;

    });

    html+='</div>';

    return html;

}

function renderFilesFromText(rawText){

    let paths = extractPathsFromRawText(rawText);

    let files = paths.filter(p =>
        !/\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i.test(p)
    );

    if(files.length===0) return '';

    let html='<div class="mt-3 d-flex flex-column">';

    files.forEach(src=>{

        let fileName = decodeURIComponent(src).split('/').pop();

        let icon='fa-file';

        if(src.match(/pdf$/i))
            icon='fa-file-pdf text-danger';

        else if(src.match(/docx?$/i))
            icon='fa-file-word text-primary';

        else if(src.match(/xlsx?$/i))
            icon='fa-file-excel text-success';

        html += `
            <a href="${src}"
               target="_blank"
               class="btn btn-light border mb-2 text-left">
                <i class="fas ${icon} mr-2"></i>
                ${fileName}
            </a>
        `;

    });

    html+='</div>';

    return html;

}

function formatTextForDiff(text){

    if(!text) return '';

    let str = String(text);

    try{

        let obj = JSON.parse(str);

        if(Array.isArray(obj))
            str=obj.join('\n');

        else if(typeof obj==='object')
            str=JSON.stringify(obj,null,2);

    }catch(e){

        if(str.includes(';')){

            str=str.split(';').join('\n');

        }

    }

    try{
        str=decodeURIComponent(str);
    }catch(e){}

    return str;

}

function renderDiffViewer(data){
    let html = '';
    $.each(data, function (_, section) {
        const sectionHtml = renderDiffSection(
            section.parentData,
            section.draftData
        );
        if (!sectionHtml) {
            return;
        }
        html += `
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    ${section.title}
                </div>
                <div class="card-body">
                    ${sectionHtml}
                </div>
            </div>
        `;
    });
    if (!html) {
        html = `
            <div class="alert alert-info">
                Không có thay đổi.
            </div>
        `;
    }
    $("#diffViewer").html(html);
}

/**
 * Chuẩn hóa dữ liệu:
 * object  -> [object]
 * array   -> array
 */
function normalizeDiffData(data){
    if (Array.isArray(data)) {
        return data;
    }
    return [data || {}];
}

function renderDiffSection(parentData, draftData){
    const parentList = normalizeDiffData(parentData);
    const draftList  = normalizeDiffData(draftData);
    let html = '';
    const max = Math.max(parentList.length, draftList.length);
    for(let i = 0; i < max; i++){
        const parentItem = parentList[i] || {};
        const draftItem  = draftList[i] || {};
        if(max > 1){
            html += `
                <div class="alert alert-secondary font-weight-bold">
                    Bản ghi ${i + 1}
                </div>
            `;
        }
        html += renderDiffItem(parentItem, draftItem);
    }
    return html;
}

function renderDiffItem(parentData, draftData){
    let html = '';
    const keys = new Set([
        ...Object.keys(parentData || {}),
        ...Object.keys(draftData || {})
    ]);
    keys.forEach(function(key){
        const oldRaw = parentData[key] ?? '';
        const newRaw = draftData[key] ?? '';
        if(oldRaw === newRaw){
            return;
        }
        const diff = Diff.diffWordsWithSpace(
            formatTextForDiff(oldRaw),
            formatTextForDiff(newRaw)
        );
        let left  = '';
        let right = '';
        diff.forEach(function(part){
            const value = part.value.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
            if(part.removed){
                left += `<del class="diff-removed">${value}</del>`;
            }else if(part.added){
                right += `<ins class="diff-added">${value}</ins>`;
            }else{
                left += value;
                right += value;
            }
        });
        const leftImg   = renderImagesFromText(oldRaw);
        const rightImg  = renderImagesFromText(newRaw);
        const leftFile  = renderFilesFromText(oldRaw);
        const rightFile = renderFilesFromText(newRaw);
        let attachment = '';
        if(leftImg || rightImg || leftFile || rightFile){
            attachment = `
                <div class="p-3 border-right bg-light">
                    ${leftImg}
                    ${leftFile}
                </div>
                <div class="p-3">
                    ${rightImg}
                    ${rightFile}
                </div>
            `;
        }
        html += `
            <div class="diff-container">
                <div class="diff-header">
                    ${key}
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr">
                    <div class="p-3 border-right bg-light diff-col">
                        <strong>Bản gốc</strong>
                        <hr>
                        ${left}
                    </div>
                    <div class="p-3 diff-col">
                        <strong>Bản nháp</strong>
                        <hr>
                        ${right}
                    </div>
                    ${attachment}
                </div>
            </div>
        `;
    });
    return html;
}
</script>
@endonce