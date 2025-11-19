$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Image Uplode';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'image_uplode_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var data 	  = {
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
"searching" : false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Image Uplode'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Image Uplode',
			filename: 'image_uplode'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Image Uplode',
			filename: 'image_uplode'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Image Uplode',
			filename: 'image_uplode'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Image Uplode'
		}
		],
	});
}



// function image_view($folder_name = "", $unique_id = "")
// {
//     // echo $dc_file_name;
//     $file_names = explode(',');
//     $image_view = '';
//     if ($dc_file) {
//         foreach ($file_names as $file_key => $dc_file) {
//             if ($file_key != 0) {
//                 if ($file_key % 4 != 0) {
//                     $image_view .= "&nbsp";
//                 } else {
//                     $image_view .= "<br><br>";
//                 }
//             }
//             $cfile_name = explode('.');
// 			if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
// 				$image_view .= '<img src="uploads/' . $folder_name . '/' . $dc_file . '"  height="50px" width="50px" >';
// 				// $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
// 			} 
//         }
//     }
//     return $image_view;
// }

function image_uplode_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {
	
			var data = {
				"unique_id" 	: unique_id,
				"action"		: "delete"
			}
	
			$.ajax({
				type 	: "POST",
				url 	: ajax_url,
				data 	: data,
				success : function(data) {
	
					var obj     = JSON.parse(data);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;
	
					if (!status) {
						url 	= '';
						
					} else {
						init_datatable(table_id,form_name,action);
					}
					sweetalert(msg,url);
				}
			});
	
		} else {
			// alert("cancel");
		}
	});
	}
	
//js