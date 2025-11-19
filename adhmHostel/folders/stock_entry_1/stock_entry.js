$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
	stock_sub_datatable("stock_sub_datatable",form_name,"stock_sub_datatable");
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Feedback';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'stock_entry_datatable';
var action 			= "datatable";


	
function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/stock_entry' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf(file_name) {
	var pdfUrl = "../adhmHostel/uploads/stock_entry/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../adhmHostel/uploads/stock_entry/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function ready_getdiscountamount() {
	$('#net_amt').empty();
	var discount = document.getElementById('discount').value;

	

	var actual_price = document.getElementById('total_amount').value;

	// var discount = document.getElementById('discount').value;
	if (discount == '') {
		discount = '0';
	}

	selling_price = actual_price - (actual_price * (discount / 100));
	let rounded_selling_price = Math.round(selling_price);
	// let rounded_selling_price = selling_price;




	if (discount != '' && discount != '0') {
		document.getElementById('discount_amount').value = rounded_selling_price;
		document.getElementById('aft_discount').value = rounded_selling_price;
		$('#net_amt').append(rounded_selling_price);
		$('#net_total_amount').val(rounded_selling_price);
	} else {
		document.getElementById('discount_amount').value = '0';
		document.getElementById('aft_discount').value = '0';
		$('#net_amt').text(actual_price);
		$('#net_total_amount').val(actual_price);
	}



}

function ready_getgstamount() {

	var gst = document.getElementById('gst').value;


	$('#net_amt').empty();
		var gst_total = '';



	var discount_amount = document.getElementById('discount_amount').value;

	var discount = document.getElementById('discount').value;
	var net_total_amount = document.getElementById('net_total_amount').value;
	// alert(net_total_amount);

	var total_amount = document.getElementById('total_amount').value;

	// if (total_amount == '') {
	//     var discount_amount = total_amount;
	//     alert(discount_amount);

	// }

	var gst = document.getElementById('gst').value;
	if (gst == '') {
		gst = '0';
	}



	if (discount == '') {

		const tax = total_amount * (gst / 100);

		let rounded_tax = Math.round(tax);
		// let rounded_tax = tax;
		gst_total = parseInt(total_amount) + parseInt(rounded_tax);
	} else if (discount != '') {
		const tax = discount_amount * (gst / 100);

		let rounded_tax = Math.round(tax);
		// let rounded_tax = tax;
		gst_total = parseInt(discount_amount) + parseInt(rounded_tax);


	}



	if (gst != '' && gst != '0') {
		;
		document.getElementById('aft_gst').value = gst_total;
		document.getElementById('gst_amount').value = gst_total;
		document.getElementById('net_total_amount').value = gst_total;
		$('#net_amt').text(gst_total);
	} else {
		document.getElementById('aft_gst').value = "0";
		document.getElementById('gst_amount').value = "0";
		document.getElementById('net_total_amount').value = gst_total;
		$('#net_amt').text(gst_total);
	}
	//   alert(net_total_amount);
}

function ready_getotherexpamount() {
	var expense = document.getElementById('expense').value;
	var actual_price = document.getElementById('total_amount').value;
	var gst_amt = document.getElementById('aft_gst').value;
	var discount_amt = document.getElementById('aft_discount').value;
	var discount = document.getElementById('discount').value;
	var gst = document.getElementById('gst').value;
	var net_total_amount = document.getElementById('net_total_amount').value;


	if (expense == '') {
		expense = '0';
	}

	if (gst != '') {
		net_total_amount = gst_amt;
	} else if (gst == '' && discount != '') {
		net_total_amount = discount_amt;
	} else {
		net_total_amount = actual_price;
	}

	// var gst_amount = document.getElementById('gst_amount').value;
	// if(gst != ''){
	var other_expense = parseInt(net_total_amount) + parseInt(expense);
	// }else{
	//     var other_expense = parseInt(tot_amount) +parseInt(expense);
	// }

	if (expense != '' && expense != '0') {
		document.getElementById('aft_expense').value = other_expense;
		document.getElementById('net_total_amount').value = other_expense;
		$('#net_amt').text(other_expense);
	} else {
		document.getElementById('net_total_amount').value = net_total_amount;
		$('#net_amt').text(net_total_amount);
		document.getElementById('aft_expense').value = "0";
	}


}

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var data 	  = {
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		destroy: true,
		searching: false,
		"paging": true,
		"ordering": true,
		"info": false,
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
	dom: 'Bfrtip',
	searching: false,
	buttons: [
		{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Entry'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Entry',
			filename: 'stock_entry'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Entry',
			filename: 'stock_entry'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Entry',
			filename: 'stock_entry'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Stock Entry'
		}
	],
 "columnDefs": [
                       { "className": "dt-right", "targets": [5, 6] } // Align the fourth, fifth, and sixth columns to the right
        ]
	
	});
}




function stock_entry_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();
	

	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {
	
			var data = {
				"unique_id" 	: unique_id,
				"csrf_token"    : csrf_token,
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


	function stock_sub_add_update() {
		$('#totalqty').empty();
		$('#totalamount').empty();
		var internet_status = is_online();
		var unique_id = $("#unique_id").val();
		var screen_unique_id = $("#screen_unique_id").val();
		var amount = $("#amount").val();
		// alert(amount);
		var unit = $("#unit").val();
	
		var is_form = form_validity_check("was-validated");
	
		if (is_form) {
	
			var data = $(".was-validated").serialize();
			data += "&amount=" + amount + "&unit=" + unit + "&screen_unique_id=" + screen_unique_id + "&action=stock_add_update";
	
			var ajax_url = sessionStorage.getItem("folder_crud_link");
			var url = '';
	
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				// cache: false,
				// contentType: false,
				// processData: false,
				method: 'POST',
	
				success: function (data) {
	// alert(data);
					var obj = JSON.parse(data);
					var msg = obj.msg;
					
					var status = obj.status;
					var error = obj.error;
	
					if (!status) {
						url = '';
						$(".btn").text("Error");
						console.log(error);
					} else {
	
						// $(".btn").text("Add");
						// $("#item_name").val(null).trigger('change');
						// $("#qty").val("");
						// $("#unit").val(null).trigger('change');
						// $("#rate").val("");
						// $("#amount").val("");
						
	
					}
					stock_in_add_update();
					
					// stock_sub_datatable("stock_sub_datatable",'',"stock_sub_datatable");
	
				},
				error: function (data) {
					alert("Network Error");
				}
			});
	
	
		} else {
			sweetalert("form_alert");
		}
	}


	function stock_in_add_update() {
		$('#totalqty').empty();
		$('#totalamount').empty();
		var internet_status = is_online();
		var unique_id = $("#unique_id").val();
		var screen_unique_id = $("#screen_unique_id").val();
		var unit = $("#unit").val();
		var amount = $("#amount").val();
	
		var is_form = form_validity_check("was-validated");
	
		if (is_form) {
	
			var data = $(".was-validated").serialize();
			data += "&unique_id=" + unique_id + "&amount=" + amount + "&unit=" + unit + "&screen_unique_id=" + screen_unique_id + "&action=stock_in_add_update";
	
			var ajax_url = sessionStorage.getItem("folder_crud_link");
			var url = '';
	
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				
				method: 'POST',
	
				success: function (data) {
					
					var obj = JSON.parse(data);
					var msg = obj.msg;
					
					var status = obj.status;
					var error = obj.error;
	
					if (!status) {
						url = '';
						$(".btn").text("Error");
						console.log(error);
					} else {
	
						// $(".btn").text("Add");
						$("#item_name").val(null).trigger('change');
						$("#qty").val("");
						$("#unit").val(null).trigger('change');
						$("#rate").val("");
						$("#amount").val("");
						
	
					}
					
					stock_sub_datatable("stock_sub_datatable",'',"stock_sub_datatable");
	
				},
				error: function (data) {
					alert("Network Error");
				}
			});
	
	
		} else {
			sweetalert("form_alert");
		}
	}

	function stock_tot_qty_amt(unique_id = "") {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url      = sessionStorage.getItem("list_link");
		var screen_unique_id = $("#screen_unique_id").val();
		var net_amt = $("#net_amt").val();
		var net_total_amount = $("#net_total_amount").val();
		
		
				var data = {
					"screen_unique_id" 	: screen_unique_id,
					"action"		: "stock_tot_qty_amt"
				}
		
				$.ajax({
					type 	: "POST",
					url 	: ajax_url,
					data 	: data,
					success : function(data) {
		
						var obj     = JSON.parse(data);
						var msg     = obj.msg;
						var tot_qty     = obj.tot_qty;
						var tot_amount     = obj.tot_amount;
						var status  = obj.status;
						var error   = obj.error;

						$("#tot_qty").val(tot_qty);
						$("#tot_amount").text(tot_amount);
						$("#total_amount").val(tot_amount);
						if(net_total_amount == '' || net_total_amount == '0'){
							$("#net_amt").text(tot_amount);
							$("#net_total_amount").val(tot_amount);
							}
						ready_getdiscountamount();
						ready_getgstamount();
						ready_getotherexpamount();						
		
						
					}
				});
		
			}
		
	

	function stock_sub_datatable(table_id='',form_name='',action='') {
		stock_tot_qty_amt();
		var table = $("#"+table_id);
		var screen_unique_id = $("#screen_unique_id").val();
		
		var data 	  = {
			"action"	: action, 
			"screen_unique_id"	: screen_unique_id, 
		};
		var ajax_url = sessionStorage.getItem("folder_crud_link");
	
		var datatable = table.DataTable({
			destroy: true,
			"searching": false,
			"paging": false,
			"ordering": false,
			"info": false,
		"ajax"		: {
			url 	: ajax_url,
			type 	: "POST",
			data 	: data
		},
		"columnDefs": [
			// Example: aligning first and third columns to the left
			{ "targets": [0, 2,4,5], "className": "dt-right" },
			{ "targets": [6], "className": "dt-center" }
			// Adjust column indices as needed
		]
			// dom: 'Bfrtip',
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ]
		});
	}

	function stock_sub_delete(val,item_id) {

		//  var data 	 = val;
confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {
		var data = "id=" + val + "&item_id=" + item_id + " &action=sub_delete";
		
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = '';
	
	
	
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
	
			method: 'POST',
	
	
			success: function(data) {
				
	
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var data = obj.data;
				var status = obj.status;
				var error = obj.error;
	
				if (msg == "success_delete") {
					sweetalert(msg);
					// location.reload();
					stock_sub_datatable("stock_sub_datatable",'',"stock_sub_datatable");
				}
			}
		});
	
	
	}
});
	}
	
	
	