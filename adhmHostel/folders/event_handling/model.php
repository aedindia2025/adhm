<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

$district_unique_id = $_SESSION['district_id'];
$taluk_unique_id = $_SESSION['taluk_id'];

// Form variables
$btn_text = 'Save';
$btn_action = 'create';
$is_btn_disable = '';

$unique_id = '';

$main_screen_id = '';
$screen_section_id = '';
$screen_name = '';
$screen_folder_name = '';
$order_no = '';
$icon_name = '';
$is_active = 1;
$description = '';

$user_action_options = '';
$user_action_selected = '';

$current_date = date('Y-m-d');

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {

        // $uni_dec = str_replace( ' ', '+', $_GET[ 'unique_id' ] );
        // $get_uni_id           = openssl_decrypt( base64_decode( $uni_dec ), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv );

        //  $unique_id  = $_GET[ 'unique_id' ];

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            'unique_id' => $unique_id
        ];

        $table = 'event_handling';

        $columns = [
            'cur_date',
            'event_name',
            'remarks',
            'image_file_name',
            'video_file_name',
            'is_active',
            'unique_id'
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $current_date = $result_values[0]['cur_date'];
            $carrier_title = $result_values[0]['event_name'];

            $remarks = $result_values[0]['remarks'];
            $image_file_name = $result_values[0]['image_file_name'];

            $video_file_name = $result_values[0]['video_file_name'];
            $unique_id = $result_values[0]['unique_id'];

            // $doc_file = image_view("event_handling", $result_values[0]['unique_id'],  $result_values[0]['doc_file_name']);

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }
    }
}


$active_status_options = active_status($is_active);

// if($current_date){
//     $cur_date = $current_date;
// }else{
//     $cur_date = date('d/m/Y');
// }
// if ( $unique_id != '' ) {
//     $form_unique_id = $unique_id;
// } else if ( $unique_id == '' ) {
//     $form_unique_id = unique_id( $prefix );
// }

function image_view($folder_name = "", $unique_id = "", $dc_file_name = "")
{
    // echo $dc_file_name;
    $file_names = explode(',', $dc_file_name);
    $image_view = '';

    if ($dc_file_name) {
        foreach ($file_names as $file_key => $dc_file_name) {
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }

            $cfile_name = explode('.', $dc_file_name);

            if ($dc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' . $dc_file_name . '\')"><img src="uploads/' . $folder_name . '/' . $dc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $dc_file_name . '\')"><img src="assets/images/pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print(\'/' . $dc_file_name . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' . $dc_file_name . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }

    return $image_view;
}


$hostel_unique_id = $_SESSION['hostel_id'];

?>
<!-- Modal with form -->
<style>
    .add {
        margin-left: 20px;
    }

    .img-fluid {
        margin-left: 15px;
    }

    .modal-content {
        width: 103%;
        height: 362px;
        margin-left: 69px;
    }

    #new1 {
        width: 98%;
        height: 414px;
        margin-left: 95px;
    }

    #new {

        -ms-flex-negative: 0;
        flex-shrink: 0;
        /* width: 100%; */
        /* max-width: 100%; */
        /* padding-right: calc(var(--ct-gutter-x)* .5); */
        /* padding-left: calc(var(--ct-gutter-x)* .5); */
        margin-top: var(--ct-gutter-y);
    }

    #error_message {
        color: red;
    }

    /* #cur_date{
    pointer-events: none
} */
    .fm {
        margin-top: 7px;
    }

    .load {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }
</style>

<div class='content-page'>
    <div class='content'>
        <div class='container-fluid'>
            <!-- start page title -->
            <div class='row'>
                <div class='col-12'>
                    <div class='page-title-box'>

                        <h4 class='page-title'>Event Handling</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class='row'>
                <div class='col-12'>
                    <div class='row'>
                        <div class=''>
                            <div class='card'>
                                <div class='card-body'>
                                    <form class='was-validated' autocomplete='off'>

                                        <div class='row'>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Current Date</label>
                                                <input type='date' id='cur_date' name='cur_date' class='form-control'
                                                    value="<?php echo $current_date; ?>" required>
                                            </div>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Event Name</label>
                                                <input type='text' id='event_name' name='event_handling'
                                                    oninput="description_val(this)" class='form-control'
                                                    value="<?php echo $carrier_title; ?>">
                                                <input type="hidden" id="csrf_token" name="csrf_token"
                                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <input type='hidden' id='hostel_name' name='hostel_name'
                                                    class='form-control' value="<?php echo $hostel_unique_id; ?>">
                                            </div>
                                            <!-- <div class='col-md-4 fm'>
                                                <label class='form-label'>Status</label>
                                                <select name='is_active' id='is_active' class='form-control' required>
                                                    <?php echo $active_status_options; ?>
                                                </select> -->

                                                <input type='hidden' id='district_id' name='district_id'
                                                class='form-control' value="<?php echo $district_unique_id; ?>">
                                                <input type='hidden' id='taluk_id' name='taluk_id'
                                                class='form-control' value="<?php echo $taluk_unique_id; ?>">

                                            <input type='hidden' id='form_unique_id' name='form_unique_id'
                                                class='form-control' value="<?php echo $form_unique_id; ?>">

                                            <input type='hidden' id='unique_id' name='unique_id' class='form-control'
                                                value="<?php echo $unique_id; ?>">
                                            <!-- </div> -->

                                            <?php if ($image_file_name == '') { ?>
                                                <div class='col-md-4 fm'>
                                                    <label class='form-label'>Image Upload</label>
                                                    <input type='file' id='test_file' multiple name='test_file[]'
                                                        class='form-control' accept='image/*'>
                                                </div>
                                                <span id="error_message"></span>

                                            <?php } else { ?>
                                                <div class='col-md-4 fm'>
                                                    <label class='form-label'>Image View </label>
                                                    <img class="img-fluid" src="assets/images/images.png" width="15%"
                                                        data-bs-toggle="modal" data-bs-target=".demos">

                                                    <!-- <img class="img-fluid" src="uploads/carrier_guidance/images/<?php echo $image_file_name; ?>" width="33%"> -->
                                                </div>

                                            <?php }
                                            if ($video_file_name == '') { ?>
                                                <div class='col-md-4 fm'>
                                                    <label class='form-label'>Video Upload</label>
                                                    <input type='file' id='video_upload' name='video_upload'
                                                        class='form-control' accept='video/mp4'>
                                                </div>
                                                <span id="error_message"></span>
                                            <?php } else { ?>
                                                <div class='col-md-4 fm'>
                                                    <label class='form-label'>Video View</label>
                                                    <img class="img-fluid" src="assets/images/video.jpg" width="17%"
                                                        data-bs-toggle="modal" data-bs-target=".bs-example-modal-x12">

                                                    <!-- <?php echo $doc_file; ?> -->
                                                    <!-- <input type='file' id='doc_upload' name='doc_upload'
                                                    class='form-control' value="<?php echo $doc_file_name; ?>"> -->
                                                </div>
                                            <?php } ?>

                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Remarks</label>
                                                <textarea id='remarks' name='remarks' class='form-control'
                                                    oninput="description_val(this)"
                                                    value="<?php echo $remarks; ?>"><?php echo $remarks; ?></textarea>
                                                <!-- <input type='text' id='remarks' name='remarks' class='form-control'
                                                    value="<?php echo $remarks; ?>"> -->
                                            </div>

                                        </div>

                                        <div class='btns'>
                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php if ($unique_id == '') { ?>
                                                <button type="button" name="btn" class="btn btn-primary"
                                                    onclick="event_handling_cu2('<?php echo $unique_id; ?>')">Save</button>
                                            <?php } else { ?>
                                                <button type="button" name="btn" class="btn btn-primary"
                                                    onclick="event_handling_cu1()">Update</button>
                                            <?php } ?>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 load" id="loader">
        <i class="mdi mdi-loading mdi-spin"></i>
    </div>
</div>


<!-- video modal popup -->
<div class="modal fade bs-example-modal-x12" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-x12">
        <div class="modal-content" id="new1">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="bs-example-modal-x12">Video View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="margin-top: -22px;">
                <div class="row mt-2">
                    <video id="video-help" width="530" controls>
                        <source id="videoPath" src="uploads/event_handling/videos/<?php echo $video_file_name; ?>"
                            type="video/mp4">
                    </video>

                    <div class="modal-footer" style="margin-top: 17px;">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>
</div><!-- /.modal -->
<!-- image modal popup -->

<div class="modal fade demos" tabindex="-1" role="dialog" aria-labelledby="demos" aria-hidden="true">
    <div class="modal-dialog demos">
        <div class="modal-content">
            <div class="modal-header" style="margin-top: -7px;">
                <h5 class="modal-title w-100 text-center" id="demos">Image View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="new">
                    <?php echo image_view1($image_file_name); ?>
                    <?php function image_view1($image_file_name)
                    {
                        // print_r($image_file_name);
                        $file_names = explode(',', $image_file_name);

                        $image_view = '';

                        if ($image_file_name) {
                            foreach ($file_names as $file_key => $image_file_name) {

                                if ($file_key != 0) {
                                    if ($file_key % 4 != 0) {
                                        $image_view .= "&nbsp";
                                    } else {
                                        $image_view .= "&nbsp";
                                    }
                                }

                                $cfile_name = explode('.', $image_file_name);
                                // print_r($cfile_name);
                                if ($image_file_name) {
                                    if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {

                                        $image_view .= '<a href="javascript:print(\'' . $image_file_name . '\')"><img src="uploads/event_handling/images/' . $image_file_name . '"  height="80px" width="77px" ></a>';
                                    }
                                }
                            }
                        }
                        // print_r($image_view);    
                        return $image_view;
                    } ?>



                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i
                        class="ri-close-line me-1 align-middle"></i> Close</a>
            </div>
        </div><!-- /.modal -->




        <script>
            function showLoader() {

                $("#loader").css("display", "inline-block"); // or "block" depending on your preference
            }

            function hideLoader() {
                $("#loader").css("display", "none");
            }

            function event_handling_cu2(unique_id = "") {

                var internet_status = is_online();

                if (!internet_status) {
                    sweetalert("no_internet");
                    return false;
                }

                var cur_date = document.getElementById('cur_date').value;
                var event_name = document.getElementById('event_name').value;
                var csrf_token = document.getElementById('csrf_token').value;
                var remarks = document.getElementById('remarks').value;
                // var is_active = document.getElementById('is_active').value;
                var district_id = document.getElementById('district_id').value;
                var taluk_id = document.getElementById('taluk_id').value;
                var hostel_name = document.getElementById('hostel_name').value;
                var video_upload = document.getElementById('video_upload');
                var unique_id = document.getElementById('unique_id').value;
                var images_r = $('#test_file').val();
                var video_r = $('#video_upload').val();
                var data = new FormData();
                var image_s = document.getElementById('test_file');

                const fileInput = document.getElementById('test_file');
            const img_file = fileInput.files[0];


    const img_allowedFileTypes = [
        'image/jpeg', 'image/png', // Images
        
    ];
if(img_file){
    if (!img_allowedFileTypes.includes(img_file.type)) {
        sweetalert('invalid_ext');
        return false;
    }
}

    const vid_fileInput = document.getElementById('video_upload');
            const vid_file = vid_fileInput.files[0];


    const vid_allowedFileTypes = [
        'video/mp4',                            // MP4
    'video/x-msvideo',                      // AVI
    'video/x-ms-wmv',                       // WMV
    'video/mpeg',                           // MPEG
    'video/webm'       
    ];
   if(vid_file){
    if (!vid_allowedFileTypes.includes(vid_file.type)) {
        sweetalert('invalid_ext');
        return false;
    }
}


                if (image_s != '') {
                    for (var i = 0; i < image_s.files.length; i++) {
                        data.append("test_file[]", document.getElementById('test_file').files[i]);
                    }
                } else {
                    data.append("test_file", '');
                }




                if (video_upload != '') {
                    for (var i = 0; i < video_upload.files.length; i++) {
                        data.append("video_file", document.getElementById('video_upload').files[i]);
                    }
                }
                if (video_upload == '') {
                    data.append("video_file", '');
                }

                if (images_r != '' && video_r != '' && event_name != '' && remarks != '') {

                    showLoader();


                    var actions = "createupdate";

                    data.append("cur_date", cur_date);
                    data.append("event_name", event_name);
                    data.append("hostel_name", hostel_name);
                    data.append("remarks", remarks);
                    data.append("csrf_token", csrf_token);
                    data.append("unique_id", unique_id);
                    data.append("district_id", district_id);
                    data.append("taluk_id", taluk_id);


                    data.append("action", actions);



                    var ajax_url = sessionStorage.getItem("folder_crud_link");
                    var url = sessionStorage.getItem("list_link");

                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        method: 'POST',
                        // alert(url);
                        // alert(msg);
                        success: function (data) {
                            var obj = JSON.parse(data);
                            var msg = obj.msg;
                            var status = obj.status;
                            var error = obj.error;
                            hideLoader();
                            
                            if (msg == "form_alert") {
                                sweetalert("form_alert");
                            } else {
                                if (!status) {
                                    url = '';
                                    $(".createupdate_btn").text("Error");
                                    console.log(error);
                                } else {
                                    if (msg == "already") {
                                        // Button Change Attribute
                                        url = '';

                                        $(".createupdate_btn").removeAttr("disabled", "disabled");
                                        if (unique_id) {
                                            $(".createupdate_btn").text("Update");
                                        } else {
                                            $(".createupdate_btn").text("Save");
                                        }
                                    }
                                }
                            }
                            sweetalert(msg, url);
                        },
                        error: function (data) {
                            alert("Network Error");
                        }
                    });
                } else {
                    sweetalert("form_alert");
                }
            }

            function event_handling_cu1() {

                var internet_status = is_online();

                if (!internet_status) {
                    sweetalert("no_internet");
                    return false;
                }

                var cur_date = document.getElementById('cur_date').value;
                var event_name = document.getElementById('event_name').value;
                // var soc_media_link = document.getElementById('soc_media_link').value;
                var remarks = document.getElementById('remarks').value;
                var csrf_token = document.getElementById('csrf_token').value;
                // var is_active = document.getElementById('is_active').value;

                // var doc_upload = document.getElementById('doc_upload');
                var video_upload = document.getElementById('video_upload');
                var district_id = document.getElementById('district_id').value;
                var taluk_id = document.getElementById('taluk_id').value;

                var unique_id = document.getElementById('unique_id').value;
                var data = new FormData();


                var actions = "createupdate";
                showLoader();

                data.append("cur_date", cur_date);
                data.append("event_name", event_name);
                // data.append("soc_media_link", soc_media_link);
                data.append("hostel_name", hostel_name);
                data.append("remarks", remarks);
                data.append("csrf_token", csrf_token);
                // data.append("is_active", is_active);
                data.append("action", actions);
                data.append("unique_id", unique_id);
                data.append("district_id", district_id);
                data.append("taluk_id", taluk_id);

                var ajax_url = sessionStorage.getItem("folder_crud_link");
                var url = sessionStorage.getItem("list_link");

                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    success: function (data) {
                        var obj = JSON.parse(data);
                        var msg = obj.msg;
                        var status = obj.status;
                        var error = obj.error;
                        // alert(url);
                        // alert(msg);
                        hideLoader();
                        if (msg == "form_alert") {
                            sweetalert("form_alert");
                        } else {
                            if (!status) {
                                url = '';
                                $(".createupdate_btn").text("Error");
                                console.log(error);
                            } else {
                                if (msg == "already") {
                                    // Button Change Attribute
                                    url = '';

                                    $(".createupdate_btn").removeAttr("disabled", "disabled");
                                    if (unique_id) {
                                        $(".createupdate_btn").text("Update");
                                    } else {
                                        $(".createupdate_btn").text("Save");
                                    }
                                }
                            }
                        }
                        sweetalert(msg, url);
                    },
                    error: function (data) {
                        alert("Network Error");
                    }
                });
            }

            function print_pdf(file_name) {
                onmouseover = window.open('../adhmAdmin/uploads/carrier_guidance/documents/' + file_name, 'onmouseover',
                    'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no'
                );
            }

            function isUrlValid() {
                var userInput = document.getElementById('url').value;

                var res = userInput.match(
                    /( http( s )?:\/\/. )?( www\. )?[ -a-zA-Z0-9@:%._\+~# = ] {2, 256}\. [a - z] {2,6}\b([-a - zA - Z0 - 9 @: % _\ + .~# ? & / / = ] * )/g
                );
                if (res == null) {
                    alert('You have entered an invalid URL!');
                    return false;
                } else {
                    return true;
                }
            }
        </script>