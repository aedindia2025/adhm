<?php
$password       = '3sc3RLrpd17';
$enc_method     = 'aes-256-cbc';
$enc_password   = substr(hash('sha256', $password, true), 0, 32);
$enc_iv         = 'av3DYGLkwBsErphc';

$menu_screen    = 'application_form/form';
$encrypted_path = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
$url            = "index.php?file=" . urlencode($encrypted_path);
?>

<style>
	
	table#application_datatable {
	width: 100%;
    display: block;
    overflow: scroll;
	}
</style>


<div class="content-page">
	<div class="content">
		<!-- Start Content-->
		<div class="container-fluid">
			<!-- start page title -->
			<div class="row mb-4">
				<div class="col-12">
					<div class="page-title-box">
						<h4 class="page-title">Application Form</h4>
						<div class="page-title-right">
							<form class="d-flex">
								<button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo $url; ?>'">
									New Application / Application Edit
								</button>
							</form>
						</div>
						<h4 class="page-title"></h4>
					</div>
				</div>
			</div>
		</div>



		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
						<table id="application_datatable" class="table nowrap w-100">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Acc Year</th>
									<th>Application No</th>
									<th>Student Name</th>
									<th>Status</th>
									<th>Refugee</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function () {

main_datatable('application_datatable','','main_datatable');
});


var table_id = 'application_datatable';

var action = "main_datatable";


function main_datatable(table_id = '', form_name = '', action = ''){

    var table = $("#" + table_id);
    
 var data = {
       
        // "list_asset": list_asset,
        "action": action
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    var datatable = table.DataTable({
        destroy: true,
       searching: false,
	   responsive: false,
        "paging": true,
        "ordering": true,
        "info": false,
        "ajax": {
            url: ajax_url,
            type: "POST",
            data: data
        },

			dom: 'Bfrtip',
		searching: false,
	buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application',
			filename: 'application_form'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application',
			filename: 'application_form'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application',
			filename: 'application_form'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Student Application'
		}
	]

    });
}


</script>
