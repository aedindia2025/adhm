<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Full-screen overlay styles */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .overlay img,
        .overlay video {
            max-width:100%;
            max-height: 80%;
        }
        .overlay .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            z-index:99999;
        }
    </style>
</head>
<body>
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <form class="d-flex">
                                    <!-- <?php echo btn_add($btn_add); ?> -->
                                </form>
                            </div>
                            <h4 class="page-title">Career Guidence</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <table id="carrier_guidance_datatable" class="table dt-responsive w-100">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <!-- <th>Date</th> -->
                                                <th>Topic Name</th>
                                                <th>Social Media</th>
                                                <th>Image</th>
                                                <th>Document</th>
                                                <th>Video</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full-Screen Overlay -->
    <div id="overlay" class="overlay">
        <span class="close-btn" onclick="closeOverlay()">&times;</span>
        <div id="overlayContent"></div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        function showOverlay(type, fileName) {

            var overlay = document.getElementById('overlay');
            var overlayContent = document.getElementById('overlayContent');

            if (type === 'image') {
                overlayContent.innerHTML = '<img src="../../adhmAdmin/uploads/carrier_guidance/images/' + fileName + '" alt="Image">';
            } else if (type === 'video') {
                overlayContent.innerHTML = '<video id="overlayVideo" controls><source src="../../adhmAdmin/uploads/carrier_guidance/videos/' + fileName + '" type="video/mp4"></video>';
            }

            overlay.style.display = 'flex';
        }

        function closeOverlay() {
            var overlay = document.getElementById('overlay');
            var overlayContent = document.getElementById('overlayContent');
            
            // Stop video playback
            var video = document.getElementById('overlayVideo');
            if (video) {
                video.pause();
                video.currentTime = 0; // Reset video to the beginning
                video.src = ""; // Clear the source to stop loading the video
            }
            
            overlay.style.display = 'none';
            overlayContent.innerHTML = ''; // Clear the overlay content
        }
    </script>
</body>
</html>
