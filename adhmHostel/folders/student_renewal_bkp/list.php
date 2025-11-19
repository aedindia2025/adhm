<?php
include '../../config/common_fun.php';

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", "");

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", "");

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", "");

$m_district_name_list = district_name();
$m_district_name_list = select_option($m_district_name_list, "Select District", "");

$m_taluk_name_list = taluk_name();
$m_taluk_name_list = select_option($m_taluk_name_list, "Select Taluk", "");

$m_hostel_name_list = hostel_name();
$m_hostel_name_list = select_option_host($m_hostel_name_list, "Select Hostel", "");

$exit_reason_list = exit_reason();
$exit_reason_options = select_option($exit_reason_list, 'Select Reason', $exit_reason);


?>
<style>
    .text-left {
        text-align: left !important;
    }

    .modal-header {
        background-color: #00aff0;
        color: #fff;
    }

    .load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }

    .nav-justified .nav-item,
    .nav-justified>.nav-link {
        -ms-flex-preferred-size: 0;
        flex-basis: 15%;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 0;
        text-align: center;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: var(--ct-nav-pills-link-active-color);
        background-color: #00aff0;
    }

    .nav-link:focus,
    .nav-link:hover {
        color: #000000;
    }

    .page-title-box .page-title {

        line-height: 54px !important;
    }

    .modal-content {
        width: 50%;
        margin-left: unset !important;
    }

    .modal-header h5 {
        font-size: 17px;
    }

    .modal-header.border-0 {
        background: #2993bc;
        color: #ffffff;
    }

    .modal-body {
        padding: 24px;
    }

    .select2-container {
        z-index: 9999 !important;
    }

    table#m_datatable tr td {
        text-align: left !important;
    }

    table#m_datatable tr th {
        text-align: left !important;
    }

    table#auto_datatable tr td {
        text-align: left !important;
    }

    table#auto_datatable tr th {
        text-align: left !important;
    }

    button.m_transferbtn {
        background: #fff4e4;
        border: 1px solid #ffc35a;
        margin: 0px 10px 0px 0px;
        border-radius: 3px;
    }

    button.m_exitBtn {
        background: #fff2f5;
        border: 1px solid #ff0235ab;
        margin: 0px 10px;
        border-radius: 3px;
    }

    button.m_renewBtn {
        background: #e4fff7;
        border: 1px solid #0acf97;
        margin: 0px 10px;
        border-radius: 3px;
    }

    button.transferbtn {
        background: #fff4e4;
        border: 1px solid #ffc35a;
        margin: 0px 10px 0px 0px;
        border-radius: 3px;
    }

    button.exitBtn {
        background: #fff2f5;
        border: 1px solid #ff0235ab;
        margin: 0px 10px;
        border-radius: 3px;
    }

    button.conExitBtn {
        background: #fff2f5;
        border: 1px solid #ff0235ab;
        margin: 0px 10px;
        border-radius: 3px;
    }

     button.m_conExitBtn {
        background: #fff2f5;
        border: 1px solid #ff0235ab;
        margin: 0px 10px;
        border-radius: 3px;
    }

    button.renewBtn {
        background: #e4fff7;
        border: 1px solid #0acf97;
        margin: 0px 10px;
        border-radius: 3px;
    }

    div#basicwizard li a {
        padding: 6px 10px !important;
    }

    table#auto_datatable,
    table#m_datatable {
        width: 100%;
        table-layout: auto;
        white-space: nowrap;
    }

    .auto-table-wrapper,
    .m-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .alert-content {
        background: #dededef2;
        padding: 5px;
        margin-bottom: 11px;
    }
</style>

<?php
$password = '3sc3RLrpd17';
$enc_method = 'aes-256-cbc';
$enc_password = substr(hash('sha256', $password, true), 0, 32);
$enc_iv = 'av3DYGLkwBsErphc';

$menu_screen = 'print_for_dispatch/list';
$encrypted_path = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
$url = "index.php?file=" . urlencode($encrypted_path);
?>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        </div>
                        <h4 class="page-title">Student Renewal</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div id="basicwizard">
                                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2 active">
                                                <span class=" d-sm-inline">Direct</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">
                                                <span class=" d-sm-inline">Exception</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content b-0 mb-0">
                                        <div class="tab-pane active" id="basictab1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info alert-border-left alert-dismissible fade show material-shadow"
                                                        role="alert">
                                                        <div class="alert-content">
                                                            <p class="mb-0">2025-26 Direct Renewal Steps to be followed
                                                                for each student</p>
                                                        </div>
                                                        <strong class="mb-2">Step 1 : </strong>Validation of Student
                                                        Name as per Aadhar and EMIS/ UMIS
                                                        <br>
                                                        <strong class="mb-2">Step 2 : </strong>Validation of Student
                                                        hostel-school/ college/ institute-home distance
                                                        <br>
                                                        <strong class="mb-2">Step 3 : </strong>Click on transfer/ Exit/
                                                        Renew as per action required for each student
                                                        <br>
                                                        <strong class="mb-2">Step 4 : </strong>Once completed for Direct
                                                        Renewal tab, click on Go to Print for Dispatch
                                                        <br>
                                                        <strong class="mb-2">Step 5 : </strong>Prepare Print for
                                                        Despatch to send for DADWO approval
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 load" id="loader_1">
                                                    <i class="mdi mdi-loading mdi-spin"></i>
                                                </div>
                                            </div>
                                            <div class="auto-table-wrapper">
                                                <table id="auto_datatable" class="table  w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Registration No</th>
                                                            <th>Name As Aadhaar</th>
                                                            <th>Name As EMIS/UMIS</th>
                                                            <th>Name Difference</th>
                                                            <th>Distance From Home to School/College</th>
                                                            <th>Distance From Home to Hostel</th>
                                                            <th>Father Name</th>
                                                            <th>Studying Class/Branch</th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="col-md-12 mt-3" align="right">
                                                <button type="button" id="autoRenewal"
                                                    onclick="window.location.href='<?php echo $url; ?>'"
                                                    class="btn btn-primary waves-effect waves-light">Go to Print For
                                                    Dispatch</button>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="basictab2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-info alert-border-left alert-dismissible fade show material-shadow"
                                                        role="alert">
                                                        <div class="alert-content">
                                                            <p class="mb-0">2025-26 Exception Renewal Steps to be
                                                                followed for each student</p>
                                                        </div>
                                                        <strong class="mb-2">Step 1 : </strong>Validation of Student
                                                        Name as per Aadhar and EMIS/ UMIS
                                                        <br>
                                                        <strong class="mb-2">Step 2 : </strong>Validation of Student
                                                        hostel-school/ college/ institute-home distance
                                                        <br>
                                                        <strong class="mb-2">Step 3 : </strong>Click on transfer/ Exit/
                                                        Renew as per action required for each student
                                                        <br>
                                                        <strong class="mb-2">Step 4 : </strong>Raise request for
                                                        Exception Renewal processing of identified students
                                                        <br>
                                                        <strong class="mb-2">Step 5 : </strong>DADWO to verify in the
                                                        hostel and approve with report
                                                        <br>
                                                        <strong class="mb-2">Step 6 : </strong>Once completed processed
                                                        on Exception Renewal tab, click on Go to Print for Dispatch
                                                        <br>
                                                        <strong class="mb-2">Step 7 : </strong>Prepare Print for
                                                        Despatch to send for DADWO approval
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="col-md-12 load" id="loader_2">
                                                    <i class="mdi mdi-loading mdi-spin"></i>
                                                </div>
                                            </div>
                                            <div class="m-table-wrapper">
                                                <table id="m_datatable" class="table  w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Registration No</th>
                                                            <th>Name As Aadhaar</th>
                                                            <th>Name As EMIS/UMIS</th>
                                                            <th>Name Difference</th>
                                                            <th>Distance From Home to School/College</th>
                                                            <th>Distance From Home to Hostel</th>
                                                            <th>Father Name</th>
                                                            <th>Studying Class/Branch</th>
                                                            <th>Action </th>
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
            </div>
        </div>
    </div>
</div>

<!--Auto Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="transferModalLabel">Transfer Student</h5>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <input type="hidden" class="form-control" id="fromDistrict" name="fromDistrict"
                            value="<?= $_SESSION['district_id']; ?>">
                        <input type="hidden" class="form-control" id="fromTaluk" name="fromTaluk"
                            value="<?= $_SESSION['taluk_id']; ?>">
                        <input type="hidden" class="form-control" id="fromHostel" name="fromHostel"
                            value="<?= $_SESSION['hostel_id']; ?>">
                        <input type="hidden" class="form-control" id="s1UniqueId" name="s1UniqueId">
                        <input type="hidden" class="form-control" id="gender_id" name="gender_id"
                            value="<?= $_SESSION['gender_id']; ?>">
                        <input type="hidden" class="form-control" id="hostel_type_id" name="hostel_type_id"
                            value="<?= $_SESSION['hostel_type']; ?>">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="regNo">Registration No</label>
                                <input type="text" class="form-control" id="regNo" name="regNo" required readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="studentName">Student Name</label>
                                <input type="text" class="form-control" id="studentName" name="studentName" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="district_id">Transfer District</label>
                                <select class="select2 form-control" id="district_id" name="district_id"
                                    onchange="get_taluk_id()" required>
                                    <?php echo $district_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="taluk_id">Transfer Taluk</label>
                                <select class="select2 form-control" id="taluk_id" name="taluk_id"
                                    onchange="get_hostel_id()" required>
                                    <?php echo $taluk_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="hostel_id">Transfer Hostel</label>
                                <select class="select2 form-control" id="hostelId" name="hostelId" required>
                                    <?php echo $hostel_name_list; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="transferBtn" class="btn btn-primary" onclick="transferStd()">Transfer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="empty_fields()">Cancel</button>

            </div>
        </div>
    </div>
</div>

<!--Auto UMIS Modal -->
<div class="modal fade" id="umisModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center">UMIS</h5>
            </div>
            <div class="row">
                <div class="col-md-12 load" id="loader">
                    <i class="mdi mdi-loading mdi-spin"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-border-left alert-dismissible fade show material-shadow"
                                role="alert">
                                மாணவன் விடுதியில் ஒரு ஆண்டிற்கும் மேலாக தங்கியுள்ளதால், பயிலும் கல்லூரியில் வழங்கப்பட்ட
                                UMIS எண்ணை பதிவு செய்யவும். | Since the student has been studying in the hostel for more
                                than a year, UMIS number would be generated in College/ Institute. Please update to
                                process renewal.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="umis_number">UMIS NO</label>
                                <input type="text" class="form-control" id="umis_number" name="umis_number" required
                                    minlength="12" maxlength="12" oninput="number_only(this)">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="umisInsertBtn" class="btn btn-primary" onclick="submitUMIS()">Submit</button>

            </div>
        </div>
    </div>
</div>

<!--Manual Transfer Modal -->
<div class="modal fade" id="m_transferModal" tabindex="-1" role="dialog" aria-labelledby="m_transferModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="m_transferModalLabel">Transfer Student</h5>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <input type="hidden" class="form-control" id="m_fromDistrict" name="m_fromDistrict"
                            value="<?= $_SESSION['district_id']; ?>">
                        <input type="hidden" class="form-control" id="m_fromTaluk" name="m_fromTaluk"
                            value="<?= $_SESSION['taluk_id']; ?>">
                        <input type="hidden" class="form-control" id="m_fromHostel" name="m_fromHostel"
                            value="<?= $_SESSION['hostel_id']; ?>">
                        <input type="hidden" class="form-control" id="m_s1UniqueId" name="m_s1UniqueId">
                        <input type="hidden" class="form-control" id="m_gender_id" name="m_gender_id"
                            value="<?= $_SESSION['gender_id']; ?>">
                        <input type="hidden" class="form-control" id="m_hostel_type_id" name="m_hostel_type_id"
                            value="<?= $_SESSION['hostel_type']; ?>">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="m_regNo">Registration No</label>
                                <input type="text" class="form-control" id="m_regNo" name="m_regNo" required readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="m_studentName">Student Name</label>
                                <input type="text" class="form-control" id="m_studentName" name="m_studentName" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="m_district_id">Transfer District</label>
                                <select class="select2 form-control" id="m_district_id" name="m_district_id"
                                    onchange="m_get_taluk_id()" required>
                                    <?php echo $m_district_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="m_taluk_id">Transfer Taluk</label>
                                <select class="select2 form-control" id="m_taluk_id" name="m_taluk_id"
                                    onchange="m_get_hostel_id()" required>
                                    <?php echo $m_taluk_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="m_hostelId">Transfer Hostel</label>
                                <select class="select2 form-control" id="m_hostelId" name="m_hostelId" required>
                                    <?php echo $m_hostel_name_list; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="m_transferBtn" class="btn btn-primary"
                    onclick="m_transferStd()">Transfer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="m_empty_fields()">Cancel</button>

            </div>
        </div>
    </div>
</div>

<!--Manual UMIS Modal -->
<div class="modal fade" id="m_umisModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center">UMIS</h5>
            </div>
            <div class="row">
                <div class="col-md-12 load" id="m_loader">
                    <i class="mdi mdi-loading mdi-spin"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-border-left alert-dismissible fade show material-shadow"
                                role="alert">
                                மாணவன் விடுதியில் ஒரு ஆண்டிற்கும் மேலாக தங்கியுள்ளதால், பயிலும் கல்லூரியில் வழங்கப்பட்ட
                                UMIS எண்ணை பதிவு செய்யவும். | Since the student has been studying in the hostel for more
                                than a year, UMIS number would be generated in College/ Institute. Please update to
                                process renewal.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="m_umis_number">UMIS NO</label>
                                <input type="text" class="form-control" id="m_umis_number" name="m_umis_number" required
                                    minlength="12" maxlength="12" oninput="number_only(this)">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="m_umisInsertBtn" class="btn btn-primary"
                    onclick="m_submitUMIS()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>


<script>
    $(document).ready(function () {

        $(document).on('click', '.exitBtn', function () {

            var std_name = $(this).data('std_name');
            var std_reg_no = $(this).data('reg_no');
            var s1_unique_id = $(this).data('s1_unique_id');


            var reasonTextBox =
                `<br><br>
            <select class="reason-selectbox form-select">
                <?php echo $exit_reason_options ?>
            </select>`;
            $(this).parent().append(reasonTextBox);

            var rejectButton = $(this);
            rejectButton.replaceWith('<button class="conExitBtn" data-std_name='+  std_name  +' data-reg_no=' + std_reg_no + ' data-s1_unique_id=' + s1_unique_id + ' onclick="exitStudent(this)">Confirm Exit Hostel</button>');
        });

         $(document).on('click', '.m_exitBtn', function () {

            var std_name = $(this).data('std_name');
            var std_reg_no = $(this).data('reg_no');
            var s1_unique_id = $(this).data('s1_unique_id');


            var reasonTextBox =
                `<br><br>
            <select class="m_reason-selectbox form-select">
                <?php echo $exit_reason_options ?>
            </select>`;
            $(this).parent().append(reasonTextBox);

            var rejectButton = $(this);
            rejectButton.replaceWith('<button class="m_conExitBtn" data-std_name='+  std_name  +' data-reg_no=' + std_reg_no + ' data-s1_unique_id=' + s1_unique_id + ' onclick="m_exitStudent(this)">Confirm Exit Hostel</button>');
        });


    });

</script>