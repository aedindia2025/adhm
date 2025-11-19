<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Capture Photo</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      background-color: #f5f5f5;
    }

    video,
    canvas {
      display: block;
      margin: 10px auto;
      width: 100%;
      max-width: 400px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn_style {
      background-color: #4fd3ad;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 18px;
      width: 90%;
      max-width: 400px;
      margin: 20px auto;
      display: block;
    }

    #permission-guide {
      text-align: center;
      color: red;
      font-size: 16px;
      margin: 20px;
      display: none;
    }

    @media only screen and (max-width: 767px) {
      .btn_style {
        padding: 16px;
        font-size: 20px;
        width: 90%;
      }

      #permission-guide {
        font-size: 14px;
      }
    }
  </style>
</head>

<body>

  <div id="permission-guide">
    Camera access is blocked. <br>
    Please <a href="chrome://settings/content/siteDetails?site=https%3A%2F%2Fnallosai.tn.gov.in" target="_blank">click
      here</a> to enable camera permissions in Chrome settings, then refresh this page.
  </div>

  <video id="video" autoplay playsinline></video>
  <canvas id="canvas" style="display: none;"></canvas>
  <button class="btn_style" onclick="capture()">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 24 24"
      style="vertical-align: middle; margin-right: 8px;">
      <path
        d="M20 5h-3.2l-1.2-1.6A2 2 0 0 0 14 3H10a2 2 0 0 0-1.6.8L7.2 5H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
    </svg>
    Capture
  </button>
  <script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const permissionGuide = document.getElementById('permission-guide');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: { exact: "environment" } } })
    // navigator.mediaDevices.getUserMedia({ video: true })

      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        console.error('Camera error:', err);

        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
          permissionGuide.style.display = 'block';
        } else {
          alert('Error accessing camera: ' + err.message);
        }
      });

    function capture() {

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      const imageData = canvas.toDataURL('image/png');

      if (window.opener && !window.opener.closed) {

        window.opener.setCapturedImage2(imageData);


      }

      if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
      }

      window.close();
    }

  </script>

</body>

</html>