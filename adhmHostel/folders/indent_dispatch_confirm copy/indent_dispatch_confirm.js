$(document).ready(function () {
	init_datatable(table_id, form_name, action);
})

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Indent Raise';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'indent_confirm_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);

	var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill": month_fill,
		"action": action
	};

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},

		dom: 'Bfrtip',
		searching: false,
		buttons: [
			{
				extend: 'copyHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			},
			{
				extend: 'csvHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'excelHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'pdfHtml5',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise',
				filename: 'indent_raise'
			},
			{
				extend: 'print',
				exportOptions: {
					columns: ':not(:last-child)'
				},
				title: 'Indent Raise'
			}
		]
	});
}

$(document).on('click', '.openPopup', function (e) {
	e.preventDefault();

	let hostelUniqueId = $(this).data('hostel-unique-id');
	let hostelId = $(this).data('hostel-id');
	let hostelName = $(this).data('hostel-name');
	let month = $(this).data('month');

	$('#lab_hostel_id').text(hostelId);
	$('#lab_hostel_name').text(hostelName);
	$('#lab_month').text(month);

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	// Reinitialize table
	if ($.fn.DataTable.isDataTable('#item_deatils_table')) {
		$('#item_deatils_table').DataTable().destroy();
	}

	$('#item_deatils_table').DataTable({
		ajax: {
			url: ajax_url,
			type: 'POST',
			data: {
				action: 'item_details',
				hostel_unique_id: hostelUniqueId,
				month_fill: month
			}
		},
		columns: [
			{ data: 's_no' },
			{ data: 'item_name' },
			{ data: 'dispatch_qty' },
			{ data: 'unit' },
			{
				data: 'received_status',
				render: function (data, type, row) {
					if (row.status == 1) {
						return `
                        <input type="text" class="form-control" value="${row.received_status || ''}" disabled>
                    `;
					} else {
						return `
                        <select class="form-control received_status">
                            <option value="">Select</option>
                            <option value="Received & Approved" ${data === 'Received & Approved' ? 'selected' : ''}>Approved</option>
                            <option value="Received & Returned" ${data === 'Received & Returned' ? 'selected' : ''}>Returned</option>
                            <option value="Not Received" ${data === 'Not Received' ? 'selected' : ''}>Not Received</option>
                        </select>
                    `;
					}
				}
			},
			{
				data: 'dispatch_qty',
				render: function (data, type, row) {
					return `<input type="number" class="form-control received_qty" value="${row.received_qty || data}" step="0.01" ${row.status == 1 ? 'disabled' : ''}>`;
				}
			},
			{
				data: 'remarks',
				render: function (data, type, row) {
					return `<input type="text" class="form-control remarks" value="${data || ''}" ${row.status == 1 ? 'disabled' : ''}>`;
				}
			},
			{
				data: null,
				render: function (data, type, row) {
					if (row.status == 1) {
						return `<button class="btn btn-success btn-sm" disabled>Saved</button>`;
					} else {
						return `<button class="btn btn-primary btn-sm saveRow" 
                        data-item_id="${row.item_id}" 
                        data-unique_id="${row.unique_id}">
                        Save
                    </button>`;
					}
				}
			}
		],
		createdRow: function (row, data) {
			$(row).attr('data-item_id', data.item_id);
			$(row).attr('data-unique_id', data.unique_id);
		},
		searching: false,
		paging: false,
		info: false
	});

	$('#itemDetails').modal('show');
});

// ✅ Action button click
$(document).on('click', '.saveRow', function () {
	var row = $(this).closest('tr');
	var unique_id = $(this).data('unique_id');
	var item_id = $(this).data('item_id');
	var status = row.find('.received_status').val();
	var received_qty = parseFloat(row.find('.received_qty').val()) || 0;
	var remarks = row.find('.remarks').val().trim();

	// Validation
	if (!status) {
		Swal.fire({ icon: 'warning', title: 'Please select a status!' });
		return;
	}

	if ((status === 'Received & Returned' || status === 'Not Received') && remarks === '') {
		Swal.fire({ icon: 'error', title: 'Remarks required for this status!' });
		return;
	}

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		type: 'POST',
		data: {
			action: 'update_dispatch_status',
			unique_id: unique_id,
			item_id: item_id,
			status: status,
			received_qty: received_qty,
			remarks: remarks,
			month_fill: $('#lab_month').text(),
			hostel_unique_id: $('#lab_hostel_id').text()
		},
		success: function (response) {
			var response = JSON.parse(response)
			console.log(response.status);
			if (response.status == 'success') {
				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Item details saved successfully.'
				});

				// Disable inputs
				row.find('.received_status, .received_qty, .remarks').prop('disabled', true);
				row.find('.saveRow')
					.removeClass('btn-success')
					.addClass('btn-secondary')
					.prop('disabled', true)
					.text('Saved');
			} else {
				Swal.fire({ icon: 'error', title: 'Update failed', text: response.message });
			}
		},
		error: function () {
			Swal.fire({ icon: 'error', title: 'Server error', text: 'Could not update record.' });
		}
	});
});

function stock_report_filter() {
	// var academic_year = $('#academic_year').val();
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "academic_year": academic_year,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"month_fill": month_fill,
		"action": 'datatable',

	};

	init_datatable(table_id, form_name, action, data);

}

function get_taluk() {


	var district_name = $('#district').val();

	var data = "district_name=" + district_name + "&action=get_taluk";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk").html(data);
			}
		}
	});

}

function get_hostel() {

	var taluk_name = $('#taluk').val();

	var data = "taluk_name=" + taluk_name + "&action=get_hostel";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {

			if (data) {
				$("#hostel").html(data);
			}
		}
	});
}

function showLoader() {
	$("#loader").css("display", "inline-block");
}

function hideLoader() {
	$("#loader").css("display", "none");
}

$("#export").click(function () {
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	var url = `folders/current_stock_report/excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}&month_fill=${month_fill}`;
	window.location.href = url; // ✅ triggers direct download
});

$("#consolidatedExport").click(function () {
	var district_name = $('#district').val();
	var taluk_name = $('#taluk').val();
	var hostel_name = $('#hostel').val();
	var month_fill = $('#month_fill').val();

	var url = `folders/current_stock_report/consolidated_excel.php?district_name=${district_name}&taluk_name=${taluk_name}&hostel_name=${hostel_name}&month_fill=${month_fill}`;
	window.location.href = url; // ✅ triggers direct download
});

$(document).on('click', '#generatePDF', function () {
	// const { jsPDF } = window.jspdf;
	// const doc = new jsPDF('p', 'pt', 'a4');

	// let hostelId = $('#lab_hostel_id').text();
	// let hostelName = $('#lab_hostel_name').text();
	// let month = $('#lab_month').text();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	// $.ajax({
	// 	url: ajax_url,
	// 	type: 'POST',
	// 	data: {
	// 		action: 'fetch_pdf_data',
	// 		hostel_id: hostelId,
	// 		month: month
	// 	},
	// 	dataType: 'json',
	// 	success: function (response) {
	// 		if (!response || response.length === 0) {
	// 			alert('No data available for this hostel/month.');
	// 			return;
	// 		}

	// 		// ✅ Use Tamil font
	// 		doc.setFont("NotoSansTamil");
	// 		doc.setFontSize(16);
	// 		doc.text("தமிழ் எழுத்து சோதனை", 40, 60);
	// 		doc.text("Indent Dispatch Confirmation", 40, 40);
	// 		doc.setFontSize(11);
	// 		doc.text(`Hostel ID: ${hostelId}`, 40, 65);
	// 		doc.text(`Hostel Name: ${hostelName}`, 40, 80);
	// 		doc.text(`Month: ${month}`, 40, 95);

	// 		let body = response.map((row, i) => [
	// 			i + 1,
	// 			row.item_name,
	// 			row.dispatch_qty,
	// 			row.unit,
	// 			row.received_status || '',
	// 			row.received_qty || '',
	// 			row.remarks || '',
	// 			row.status == 1 ? 'Saved' : 'Pending'
	// 		]);

	// 		doc.autoTable({
	// 			startY: 110,
	// 			head: [['S.No', 'Item Name', 'Qty', 'Unit', 'Status', 'Received Qty', 'Remarks', 'Action']],
	// 			body: body,
	// 			theme: 'grid',
	// 			headStyles: { fillColor: [60, 141, 188], textColor: 255 },
	// 			styles: {
	// 				font: "NotoSansTamil",
	// 				fontSize: 9,
	// 				cellPadding: 4,
	// 				overflow: 'linebreak'
	// 			}
	// 		});

	// 		doc.setFontSize(9);
	// 		doc.text('Generated on: ' + new Date().toLocaleString(), 40, doc.lastAutoTable.finalY + 30);

	// 		doc.save(`Indent_Dispatch_${hostelId}_${month}.pdf`);
	// 	},
	// 	error: function (err) {
	// 		console.error(err);
	// 		alert('Error fetching PDF data from server.');
	// 	}
	// });
	const { jsPDF } = window.jspdf;
	const doc = new jsPDF('p', 'pt', 'a4');

	// ✅ Check available fonts
	console.log(doc.getFontList());

	// ✅ Use Tamil font (must be loaded in NotoSansTamil-Regular.js)
	doc.setFont("NotoSansTamil");
	doc.setFontSize(12);
	doc.text("தமிழ் உரை சோதனை", 40, 60);

	doc.save("test.pdf");
});