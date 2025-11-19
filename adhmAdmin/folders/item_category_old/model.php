<?php
// Form variables
$btn_text           = 'Save';
$btn_action         = 'create';

$unique_id          = '';
$item_category      = '';
$description        = '';
$is_active          = 1;

if ( isset( $_GET[ 'unique_id' ] ) ) {
    if ( !empty( $_GET[ 'unique_id' ] ) ) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            'unique_id' => $unique_id
        ];

        $table      =  'item_category';

        $columns    = [
            'item_category',
            'description',
            'is_active'
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select( $table_details, $where );

        if ( $result_values->status ) {

            $result_values      = $result_values->data;

            $item_category      = $result_values[ 0 ]['item_category'];
            $description        = $result_values[ 0 ]['description'];
            $is_active          = $result_values[ 0 ]['is_active'];

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
?>
<!-- Modal with form -->

<div class='content-page'>
    <div class='content'>

        <!-- Start Content-->
        <div class='container-fluid'>

            <!-- start page title -->
            <div class='row'>
                <div class='col-12'>
                    <div class='page-title-box'>

                        <h4 class='page-title'>Item Category Form</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class='row'>
                <div class='col-12'>

                    <div class='row'>

                        <div class='col-xl-12'>
                            <div class='card'>
                                <div class='card-body'>

                                    <form class='was-validated' autocomplete='off'>
                                        <div class='row'>
                                            <div class='col-md-3'>
                                                <div class='mb-3'>
                                                    <label>Item Category</label>
                                                    <input type='text' class='form-control' id='item_category'
                                                        name='item_category' value="<?=$item_category;?>" required>
                                                </div>
                                            </div>

                                            <div class='col-md-3'>
                                                <div class='mb-3'>
                                                    <label>Description</label>
                                                    <input type='text' class='form-control' id='description'
                                                        name='description' value="<?=$description;?>" required>
                                                </div>
                                            </div>

                                            <div class='col-md-3'>
                                                <div class='mb-3'>
                                                    <label>Status</label>
                                                    <select name='is_active' id='is_active' class='select2 form-control'
                                                        required>
                                                        <?php echo $active_status_options;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo btn_cancel( $btn_cancel );?>
                                        <?php echo btn_createupdate( $folder_name_org, $unique_id, $btn_text );?>
                                    </form>

                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>