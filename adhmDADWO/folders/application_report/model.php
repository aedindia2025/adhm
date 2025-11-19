<?php

// Form variables
$btn_text           = 'Save';
$btn_action         = 'create';
$is_btn_disable     = '';

$unique_id          = '';

$main_screen_id     = '';
$screen_section_id  = '';
$screen_name        = '';
$screen_folder_name = '';
$order_no           = '';
$icon_name          = '';
$is_active          = 1;
$description        = '';

$user_action_options    = '';
$user_action_selected   = '';

if ( isset( $_GET[ 'unique_id' ] ) ) {
    if ( !empty( $_GET[ 'unique_id' ] ) ) {

        // $uni_dec = str_replace( ' ', '+', $_GET[ 'unique_id' ] );
        // $get_uni_id           = openssl_decrypt( base64_decode( $uni_dec ), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv );

        $unique_id  = $_GET[ 'unique_id' ];
        $where      = [
            'unique_id' => $unique_id
        ];

        $table      =  'carrier_guidance';

        $columns    = [
            'cur_date',
            'carrier_title',
            'soc_media_link',
            'remarks',
            'image_file_name',
            'doc_file_name',
            'video_file_name',
            'is_active',
            'unique_id'
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select( $table_details, $where );

        if ( $result_values->status ) {

            $result_values     = $result_values->data;

            $current_date       = $result_values[0]['cur_date'];
            $carrier_title          = $result_values[0]['carrier_title'];
            $soc_media_link         = $result_values[0]['soc_media_link'];
            $remarks       = $result_values[0]['remarks'];
            $image_file_name          = $result_values[0]['image_file_name'];
            $doc_file_name         = $result_values[0]['doc_file_name'];
            $video_file_name         = $result_values[0]['video_file_name'];
            $unique_id           = $result_values[0]['unique_id'];

            $doc_file = image_view("carrier_guidance", $result_values[0]['unique_id'],  $result_values[0]['doc_file_name']);

            $btn_text           = 'Update';
            $btn_action         = 'update';
        } else {
            $btn_text           = 'Error';
            $btn_action         = 'error';
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}


$active_status_options   = active_status( $is_active );

if($current_date){
    $cur_date = $current_date;
}else{
    $cur_date = date('d/m/Y');
}
if ( $unique_id != '' ) {
    $form_unique_id = $unique_id;
} else if ( $unique_id == '' ) {
    $form_unique_id = unique_id( $prefix );
}

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

?>
<!-- Modal with form -->
<style>
.add {
    margin-left: 20px;
}

.img-fluid {
    margin-left: 15px;
}
</style>

<div class='content-page'>
    <div class='content'>
        <div class='container-fluid'>
            <!-- start page title -->
            <div class='row'>
                <div class='col-12'>
                    <div class='page-title-box'>

                        <h4 class='page-title'>Carrier Guidance</h4>
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
                                                <input type='text' id='cur_date' name='cur_date' class='form-control'
                                                    value="<?php echo $cur_date; ?>" required>
                                            </div>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Title</label>
                                                <input type='text' id='carrier_title' name='carrier_title'
                                                    class='form-control' value="<?php echo $carrier_title; ?>">
                                            </div>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Social Media Link</label>
                                                <input type='url' id='soc_media_link' name='soc_media_link'
                                                    class='form-control' value="<?php echo $soc_media_link; ?>">

                                                <input type='hidden' id='form_unique_id' name='form_unique_id'
                                                    class='form-control' value="<?php echo $form_unique_id; ?>">

                                                <input type='hidden' id='unique_id' name='unique_id' class='form-control'
                                                    value="<?php echo $form_unique_id; ?>">
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <?php if($image_file_name == ''){?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Image Upload</label>
                                                <input type='file' id='image_upload' name='image_upload'
                                                    class='form-control'>
                                            </div>
                                            <?php }else{?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Image View </label>
                                                <img class="img-fluid" src="assets/images/images.png" width="27%" 
                                                    data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2">

                                                <!-- <img class="img-fluid" src="uploads/carrier_guidance/images/<?php echo $image_file_name; ?>" width="33%"> -->
                                            </div>
                                            <?php } if($doc_file_name == ''){?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Document Upload</label>
                                                <input type='file' id='doc_upload' name='doc_upload'
                                                    class='form-control' value="<?php echo $doc_file_name; ?>">
                                            </div>
                                            <?php }else{ ?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Document View</label>

                                                <?php echo $doc_file; ?>
                                                <!-- <input type='file' id='doc_upload' name='doc_upload'
                                                    class='form-control' value="<?php echo $doc_file_name; ?>"> -->
                                            </div>
                                            <?php } if($doc_file_name == ''){?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Video Upload</label>
                                                <input type='file' id='video_upload' name='video_upload'
                                                    class='form-control' value="<?php echo $video_upload; ?>">
                                            </div>
                                            <?php } else{ ?>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Video View</label>
                                                <img class="img-fluid" src="assets/images/video.jpg" width="28%"
                                                    data-bs-toggle="modal" data-bs-target=".bs-example-modal-x1">

                                                <!-- <?php echo $doc_file; ?> -->
                                                <!-- <input type='file' id='doc_upload' name='doc_upload'
                                                    class='form-control' value="<?php echo $doc_file_name; ?>"> -->
                                            </div>
                                            <?php } ?>
                                        </div></br>
                                        <div class="row">
                                            <div class='col-md-6 fm'>
                                                <label class='form-label'>Remarks</label>
                                                <textarea id='remarks' name='remarks' class='form-control'
                                                    value="<?php echo $remarks; ?>"><?php echo $remarks; ?></textarea>
                                                <!-- <input type='text' id='remarks' name='remarks' class='form-control'
                                                    value="<?php echo $remarks; ?>"> -->
                                            </div>
                                            <div class='col-md-4 fm'>
                                                <label class='form-label'>Status</label>
                                                <select name='is_active' id='is_active' class='form-control' required>
                                                    <?php echo $active_status_options;?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class='btns'>
                                            <?php echo btn_cancel( $btn_cancel );?>
                                            <?php echo btn_createupdate( $folder_name_org, $unique_id, $btn_text );?>
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

<!-- video modal popup -->
<div class="modal fade bs-example-modal-x1" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Video View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <video id="video-help" width="530" controls>
                        <source id="videoPath" src="uploads/carrier_guidance/videos/<?php echo $video_file_name;?>"
                            type="video/mp4">
                    </video>

                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- image modal popup -->

        <div class="modal fade bs-example-modal-x2" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xa">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Image View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <video id="video-help" width="530" controls>
                        <source id="videoPath" src="uploads/carrier_guidance/videos/<?php echo $video_file_name;?>"
                            type="video/mp4">
                    </video>

                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                            data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script>
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