<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Khám phá VR Tour 360° - Đầu tư Hà Nội</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: #000;
    }
    .embed-container {
      flex: 1;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }
    .embed-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
  </style>
  <script>
    // Tự động chuyển hướng thẳng tới VR tour nếu URL hợp lệ
    var tourUrl = @json($link_vrtour);
    if (tourUrl && (tourUrl.indexOf('http://') === 0 || tourUrl.indexOf('https://') === 0)) {
      window.location.replace(tourUrl);
    }
  </script>
</head>
<body>
  <div class="embed-container">
    <iframe src="{{ $link_vrtour }}" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; xr-spatial-tracking" style="height:100%;width:100%"></iframe>
  </div>
</body>
</html>
