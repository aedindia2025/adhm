<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<style>
i.mdi.mdi-play-circle {
    font-size: 25px;
    color: #02a6e2;
	line-height: 26px;
}
i.mdi.mdi-tooltip-image {
    font-size: 25px;
    color: #34814d;
	line-height: 26px;
}
 /* Full-screen overlay styles */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height:100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .overlay img,
        .overlay video {
            max-width: 100%;
            max-height: 80%;
        }
        .overlay .close-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 30px;
            color: #fff;
            cursor: pointer;
            z-index: 999999;
        }

</style>



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
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">Event Handling</h4>
                        <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <table id="event_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                        <th>S.No</th>
                                            <th>Date</th>
                                            <th>Hostel Name</th>
                                            <th>Event Name</th>                                            
                                            <th>Images</th>
                                            <th>Video</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

 <script>
        function showOverlay(type, fileName) {

            var overlay = document.getElementById('overlay');
            var overlayContent = document.getElementById('overlayContent');

            if (type === 'image') {
               //  overlayContent.innerHTML = '<img src="uploads/event_handling/images/' + fileName + '" alt="Image">';
var images = fileName.split(',');
        var imageHtml = images.map(function(fileName) {
            return '<img src="uploads/event_handling/images/' + fileName + '" alt="Image">&nbsp;&nbsp;&nbsp;&nbsp';
        }).join('');
        overlayContent.innerHTML = imageHtml;
            } else if (type === 'video') {
                overlayContent.innerHTML = '<video id="overlayVideo" controls><source src="uploads/event_handling/videos/' + fileName + '" type="video/mp4"></video>';
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



