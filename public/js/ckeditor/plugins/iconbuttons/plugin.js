// File: public/ckeditor/plugins/iconbuttons/plugin.js

CKEDITOR.plugins.add('iconbuttons', {
    icons: 'checkmark,yellowarrow',
    init: function(editor) {
        
        // Command cho Check Mark
        editor.addCommand('addCheckMark', {
            exec: function(editor) {
                toggleIconWrapper(editor, '/images/success-traces.svg', 'checkmark');
            }
        });
        
        // Command cho Yellow Arrow
        editor.addCommand('addYellowArrow', {
            exec: function(editor) {
                toggleIconWrapper(editor, '/images/warning-arrow.svg', 'yellowarrow');
            }
        });
        
        // Button Check Mark
        editor.ui.addButton('CheckMark', {
            label: 'Thêm Check Mark',
            command: 'addCheckMark',
            toolbar: 'insert',
            icon: this.path + 'icons/checkmark.png'
        });
        
        // Button Yellow Arrow
        editor.ui.addButton('YellowArrow', {
            label: 'Thêm Yellow Arrow',
            command: 'addYellowArrow',
            toolbar: 'insert',
            icon: this.path + 'icons/arrow.png'
        });
        
        // Hàm toggle icon wrapper
        function toggleIconWrapper(editor, iconSrc, iconType) {
            var selection = editor.getSelection();
            var ranges = selection.getRanges();
            
            if (ranges.length === 0) return;
            
            var range = ranges[0];
            var selectedElement = range.getCommonAncestor(true);
            
            // Kiểm tra xem text đã có wrapper icon chưa
            var wrapper = findIconWrapper(selectedElement);
            
            if (wrapper) {
                // Đã có wrapper
                var currentIcon = wrapper.findOne('img');
                if (currentIcon && currentIcon.getAttribute('src') === iconSrc) {
                    // Cùng icon -> Xóa wrapper
                    removeIconWrapper(wrapper, editor);
                } else {
                    // Khác icon -> Thay đổi icon
                    if (currentIcon) {
                        currentIcon.setAttribute('src', iconSrc);
                        currentIcon.setAttribute('data-icon-type', iconType);
                    }
                }
            } else {
                // Chưa có wrapper -> Thêm mới
                addIconWrapper(editor, range, iconSrc, iconType);
            }
        }
        
        // Tìm wrapper icon
        function findIconWrapper(element) {
            var current = element;
            while (current && current.type === CKEDITOR.NODE_ELEMENT) {
                if (current.hasAttribute('data-icon-wrapper')) {
                    return current;
                }
                current = current.getParent();
            }
            return null;
        }
        
        // Thêm icon wrapper
        function addIconWrapper(editor, range, iconSrc, iconType) {
            var selectedHtml = range.extractContents();
            var fragment = new CKEDITOR.dom.documentFragment(editor.document);
            
            // Lấy tất cả các node con
            var children = selectedHtml.getChildren();
            var textLines = [];
            
            // Tách các dòng text
            for (var i = 0; i < children.count(); i++) {
                var child = children.getItem(i);
                
                if (child.type === CKEDITOR.NODE_TEXT) {
                    // Text node - tách theo line break
                    var textContent = child.getText();
                    var lines = textContent.split('\n');
                    for (var j = 0; j < lines.length; j++) {
                        if (lines[j].trim()) {
                            textLines.push(lines[j]);
                        }
                    }
                } else if (child.type === CKEDITOR.NODE_ELEMENT) {
                    // Element node (div, p, br, etc)
                    if (child.getName() === 'br') {
                        continue; // Skip br tags
                    }
                    var text = child.getText().trim();
                    if (text) {
                        textLines.push(text);
                    }
                }
            }
            
            // Nếu không có dòng nào hoặc chỉ có 1 dòng ngắn
            if (textLines.length === 0) {
                textLines.push(selectedHtml.getText().trim());
            }
            
            // Tạo wrapper cho mỗi dòng
            for (var k = 0; k < textLines.length; k++) {
                if (!textLines[k].trim()) continue;
                
                var wrapperDiv = createIconWrapper(iconSrc, iconType, textLines[k]);
                fragment.append(wrapperDiv);
                
                // Thêm br giữa các dòng (trừ dòng cuối)
                if (k < textLines.length - 1) {
                    fragment.append(new CKEDITOR.dom.element('br'));
                }
            }
            
            range.insertNode(fragment);
            editor.getSelection().selectRanges([range]);
        }
        
        // Hàm tạo 1 wrapper icon
        function createIconWrapper(iconSrc, iconType, textContent) {
            var wrapperDiv = new CKEDITOR.dom.element('div');
            wrapperDiv.setStyles({
                'display': 'flex',
                'align-items': 'center',
                'margin-bottom': '6px'
            });
            wrapperDiv.setAttribute('data-icon-wrapper', 'true');
            
            // Tạo icon container
            var iconDiv = new CKEDITOR.dom.element('div');
            iconDiv.setStyles({
                'width': '20px',
                'flex-shrink': '0'
            });
            
            var img = new CKEDITOR.dom.element('img');
            img.setAttribute('src', iconSrc);
            img.setAttribute('alt', '');
            img.setAttribute('data-icon-type', iconType);
            img.setStyles({
                'width': '15px',
                'height': '15px'
            });
            iconDiv.append(img);
            
            // Tạo text container
            var textDiv = new CKEDITOR.dom.element('div');
            textDiv.setStyle('margin-left', '8px');
            textDiv.setText(textContent);
            
            // Ghép lại
            wrapperDiv.append(iconDiv);
            wrapperDiv.append(textDiv);
            
            return wrapperDiv;
        }
        
        // Xóa icon wrapper
        function removeIconWrapper(wrapper, editor) {
            var textDiv = wrapper.findOne('div[style*="margin-left"]');
            if (textDiv) {
                var children = textDiv.getChildren();
                for (var i = 0; i < children.count(); i++) {
                    var child = children.getItem(i);
                    child.insertBefore(wrapper);
                }
            }
            wrapper.remove();
        }
    }
});