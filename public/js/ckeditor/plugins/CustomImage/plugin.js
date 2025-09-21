CKEDITOR.plugins.add("CustomImage", {
  init: function (editor) {
    // Command mở CKFinder chọn ảnh
    editor.addCommand("customImageCommand", {
      exec: function (editor) {
        CKFinder.popup({
          chooseFiles: true,
          onInit: function (finder) {
            finder.on("files:choose", function (evt) {
              var file = evt.data.files.first();
              var imageUrl = file.getUrl();

              // Mở cropper modal
              openCropperModal(imageUrl, function (croppedDataUrl) {
                editor.insertHtml(
                  '<img src="' +
                    croppedDataUrl +
                    '" style="max-width:100%; display:block; margin:auto;"/>'
                );
              });
            });
          },
        });
      },
    });

    // Tạo nút trên toolbar
    editor.ui.addButton("CustomImage", {
      label: "Cropped Image",
      command: "customImageCommand",
      toolbar: "insert",
      icon: this.path + "icons/customimage.png",
    });

    // Xử lý crop khi paste ảnh trực tiếp
    editor.on("paste", function (evt) {
      var data = evt.data;

      // Trường hợp paste có <img> sẵn
      if (data && data.dataValue && data.dataValue.includes("<img")) {
        evt.cancel();

        var tempDiv = document.createElement("div");
        tempDiv.innerHTML = data.dataValue;
        var imgTag = tempDiv.querySelector("img");

        if (imgTag) {
          var imageUrl = imgTag.src;
          openCropperModal(imageUrl, function (croppedDataUrl) {
            editor.insertHtml(
              '<img src="' +
                croppedDataUrl +
                '" style="max-width:100%; display:block; margin:auto;"/>'
            );
          });
        }
      }

      // Trường hợp paste file ảnh từ clipboard
      if (data && data.dataTransfer && data.dataTransfer.getFilesCount()) {
        var file = data.dataTransfer.getFile(0);
        if (file && file.type.indexOf("image") === 0) {
          evt.cancel();

          var reader = new FileReader();
          reader.onload = function (e) {
            openCropperModal(e.target.result, function (croppedDataUrl) {
              editor.insertHtml(
                '<img src="' +
                  croppedDataUrl +
                  '" style="max-width:100%; display:block; margin:auto;"/>'
              );
            });
          };
          reader.readAsDataURL(file);
        }
      }
    });
  },
});
// Hàm mở cropper modal

function openCropperModal(src, callback) {
  let modal = document.createElement("div");
  modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center;
        z-index: 99999; animation: fadeInUp 0.3s ease;
    `;
  modal.innerHTML = `
        <div style="
            background: #fff; padding: 20px; border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 90%; max-width: 800px; text-align: center;
        ">
            <div style="margin-bottom:15px;">
                <img id="crop-image" src="${src}" style="
                    max-width: 100%; max-height: 500px;
                    border: 1px solid #ddd; border-radius: 6px;
                "/>
            </div>
            <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                <button id="crop-confirm" style="
                    padding: 8px 16px; font-size: 14px;
                    border-radius: 6px; cursor: pointer; border: none;
                    background: #4caf50; color: #fff; transition: 0.2s;
                ">Cắt & Chèn</button>
                <button id="crop-cancel" style="
                    padding: 8px 16px; font-size: 14px;
                    border-radius: 6px; cursor: pointer; border: none;
                    background: #f44336; color: #fff; transition: 0.2s;
                ">Hủy</button>
            </div>
        </div>
    `;
  document.body.appendChild(modal);

  let image = modal.querySelector("#crop-image");
  let cropper;

  image.onload = function () {
    cropper = new Cropper(image, {
      aspectRatio: 16 / 9,
      viewMode: 1,
      autoCropArea: 1,
    });
  };

  modal.querySelector("#crop-confirm").onclick = function () {
    if (!cropper) return;
    let canvas = cropper.getCroppedCanvas({ width: 800, height: 450 });
    callback(canvas.toDataURL("image/jpeg"));
    document.body.removeChild(modal);
  };

  modal.querySelector("#crop-cancel").onclick = function () {
    document.body.removeChild(modal);
  };
}
