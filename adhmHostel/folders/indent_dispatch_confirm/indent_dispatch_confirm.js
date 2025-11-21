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

	let ajax_url = sessionStorage.getItem("folder_crud_link");

	// Reinitialize table
	if ($.fn.DataTable.isDataTable('#item_deatils_table')) {
		$('#item_deatils_table').DataTable().destroy();
	}

	// Add explanation above table
	let explanationHtml = `
        <div class="alert alert-info mb-3">
            <strong>Instructions:</strong><br>
            <span class="text-success">✔ Received & Approved</span> — Confirms order as accepted.<br>
            <span class="text-warning">✔ Received & Returned</span> — Item was returned, remarks required.<br>
            <span class="text-danger">✔ Not Received</span> — Item not received, remarks required.<br>
        </div>
    `;
	$('.alert-info').remove();
	$('#item_deatils_table').before(explanationHtml);

	// Load table
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
			// { data: 'item_name' },
			{
				data: 'item_name',
				render: function (data, type, row) {
					// Include category as hidden data attribute
					return `<span class="item-name" data-category="${row.category || ''}">${data}</span>`;
				}
			},
			{ data: 'dispatch_qty' },
			{ data: 'unit' },
			{
				data: 'dispatch_qty',
				render: function (data, type, row) {
					return `<input type="number" class="form-control received_qty" value="${row.received_qty || data}" step="0.01" ${row.status == 1 ? 'readonly' : ''}>`;
				}
			},
			{
				data: 'received_status',
				render: function (data, type, row) {
					// ✅ Date restriction logic
					let entryDate = new Date(row.entry_date);
					let currentDate = new Date();
					let diffDays = Math.floor((currentDate - entryDate) / (1000 * 60 * 60 * 24));

					let isExpired = diffDays > 2; // 0 = same day, 1 = +1 day, 2 = +2 days (3rd day limit)

					if (row.status == 1) {
						let colorClass =
							data === "Received & Approved" ? "text-success fw-bold" :
								data === "Received & Returned" ? "text-warning fw-bold" :
									data === "Not Received" ? "text-danger fw-bold" : "";

						return `
							<div class="status-display">
								<span class="${colorClass}">${data || ''}</span>
								${row.remarks ? `<div class="text-muted small mt-1">(${row.remarks})</div>` : ''}
							</div>`;
					}
					else {
						// ✅ Disable buttons if 3 days passed
						let disabledAttr = isExpired ? 'disabled' : '';
						let disableNote = isExpired
							? `<div class="text-muted small mt-1 text-danger">Action expired (more than 3 days old)</div>`
							: '';

						return `
							<div class="status-actions">
								<button class="btn btn-success btn-sm btn-approve" ${disabledAttr}>Received</button>
								<button class="btn btn-warning btn-sm btn-return" ${disabledAttr}>Returned</button>
								<button class="btn btn-danger btn-sm btn-not" ${disabledAttr}>Not Received</button>
								${disableNote}
								<div class="remarks-section mt-2 d-none">
									<textarea class="form-control remarks" rows="1" placeholder="Enter remarks..."></textarea>
									<div class="mt-2 d-flex justify-content-start gap-2">
										<button class="btn btn-primary btn-sm btn-confirm">Confirm</button>
										<button class="btn btn-secondary btn-sm btn-cancel">Cancel</button>
									</div>
								</div>
							</div>
						`;
					}
				}
			},
			// { data: 'remarks', render: data => `<input type="text" class="form-control remarks-display" value="${data || ''}" readonly>` },
			{ data: null, render: () => '' }
		],
		createdRow: function (row, data) {
			$(row).attr('data-item_id', data.item_id);
			$(row).attr('data-unique_id', data.unique_id);
			$(row).attr('data-category', data.category || ''); // Add category as data attribute
		},
		searching: false,
		paging: false,
		info: false
	});

	$('#itemDetails').modal('show');
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
	const { jsPDF } = window.jspdf;
	const doc = new jsPDF('p', 'pt', 'a4');

	let hostelId = $('#lab_hostel_id').text();
	let hostelName = $('#lab_hostel_name').text();
	let month = $('#lab_month').text();

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		url: ajax_url,
		type: 'POST',
		data: {
			action: 'fetch_pdf_data',
			hostel_id: hostelId,
			month: month
		},
		dataType: 'json',
		success: function (response) {
			if (!response || response.length === 0) {
				alert('No data available for this hostel/month.');
				return;
			}

			// ✅ Use Tamil font
			doc.setFont("NotoSansTamil");
			doc.setFontSize(16);
			doc.text("தமிழ் எழுத்து சோதனை", 40, 60);
			doc.text("Indent Dispatch Confirmation", 40, 40);
			doc.setFontSize(11);
			doc.text(`Hostel ID: ${hostelId}`, 40, 65);
			doc.text(`Hostel Name: ${hostelName}`, 40, 80);
			doc.text(`Month: ${month}`, 40, 95);

			let body = response.map((row, i) => [
				i + 1,
				row.item_name,
				row.dispatch_qty,
				row.unit,
				row.received_status || '',
				row.received_qty || '',
				row.remarks || '',
				row.status == 1 ? 'Saved' : 'Pending'
			]);

			doc.autoTable({
				startY: 110,
				head: [['S.No', 'Item Name', 'Qty', 'Unit', 'Status', 'Received Qty', 'Remarks', 'Action']],
				body: body,
				theme: 'grid',
				headStyles: { fillColor: [60, 141, 188], textColor: 255 },
				styles: {
					font: "NotoSansTamil",
					fontSize: 9,
					cellPadding: 4,
					overflow: 'linebreak'
				}
			});

			doc.setFontSize(9);
			doc.text('Generated on: ' + new Date().toLocaleString(), 40, doc.lastAutoTable.finalY + 30);

			doc.save(`Indent_Dispatch_${hostelId}_${month}.pdf`);
		},
		error: function (err) {
			console.error(err);
			alert('Error fetching PDF data from server.');
		}
	});

});

// 🔹 2️⃣ RETURNED (Orange button) — Remarks mandatory
// $(document).on('click', '.btn-approve', function () {
// 	let section = $(this).closest('.status-actions');
// 	let remarksBox = section.find('.remarks');
// 	section.find('.remarks-section').removeClass('d-none');
// 	section.data('selected-status', 'Received & Approved');
// 	remarksBox.attr('placeholder', 'Enter remarks');
// });

// 🔹 APPROVE button click
$(document).on('click', '.btn-approve', function () {

	let row = $(this).closest('tr');
	let section = $(this).closest('.status-actions');

	// 🔥 Quantities from table
	let dispatch_qty = parseFloat(row.find('td:eq(2)').text()) || 0;  // Quantity column
	let received_qty = parseFloat(row.find('.received_qty').val()) || 0; // Received Qty input

	// 🔥 Hidden data attributes
	let unique_id = row.data('unique_id');
	let item_id = row.data('item_id');
	let category = row.data('category');

	// 🔥 Unit
	let item_unit = row.find('td:eq(3)').text() || '';

	// ------------------------------------------------------------
	// 1️⃣ DIRECT APPROVAL — when received == dispatched
	// ------------------------------------------------------------
	if (received_qty === dispatch_qty) {

		updateDispatchStatus(
			row,
			row.find('td:last'),     // status cell reference
			unique_id,
			item_id,
			'Received & Approved',
			received_qty,
			'',                      // No remarks needed
			item_unit,
			category
		);

		return;
	}

	// ------------------------------------------------------------
	// 2️⃣ REQUIRE REMARKS — when received < dispatched
	// ------------------------------------------------------------
	section.find('.remarks-section').removeClass('d-none');
	section.data('selected-status', 'Received & Approved');

	let remarksBox = section.find('.remarks');
	remarksBox.attr('placeholder', 'Enter remarks...');
});

// 🔹 2️⃣ RETURNED (Orange button) — Remarks mandatory
$(document).on('click', '.btn-return', function () {
	let section = $(this).closest('.status-actions');
	let remarksBox = section.find('.remarks');
	section.find('.remarks-section').removeClass('d-none');
	section.data('selected-status', 'Received & Returned');
	remarksBox.attr('placeholder', 'Enter return reason');
});

// 🔹 3️⃣ NOT RECEIVED (Red button) — Remarks mandatory
$(document).on('click', '.btn-not', function () {
	let section = $(this).closest('.status-actions');
	let remarksBox = section.find('.remarks');
	section.find('.remarks-section').removeClass('d-none');
	section.data('selected-status', 'Not Received');
	remarksBox.attr('placeholder', 'Enter reason');
});

// 🔹 4️⃣ CONFIRM after remarks entered
$(document).on('click', '.btn-confirm', function () {

	let section = $(this).closest('.status-actions');
	let cell = $(this).closest('td');
	let row = $(this).closest('tr');
	let remarks = section.find('.remarks').val().trim();
	let status = section.data('selected-status');

	// 🔥 Fetch actual quantities
	let received_qty = parseFloat(row.find('.received_qty').val()) || 0;
	let dispatch_qty = parseFloat(row.find('td:eq(2)').text()) || 0;
	let item_unit = row.find('td:eq(3)').text() || '';

	// 🔥 **Validation – received quantity > dispatched quantity**
	if (received_qty > dispatch_qty) {
		Swal.fire({
			icon: 'warning',
			title: 'Invalid Quantity',
			text: 'Received quantity cannot be greater than dispatched quantity.'
		});
		return;
	}

	if (!remarks) {
		Swal.fire({
			icon: 'warning',
			title: 'Remarks Required',
			text: 'Please provide remarks before confirming.'
		});
		return;
	}

	let unique_id = row.data('unique_id');
	let item_id = row.data('item_id');
	let category = row.data('category');
	// let received_qty = parseFloat(row.find('.received_qty').val()) || 0;

	updateDispatchStatus(row, cell, unique_id, item_id, status, received_qty, remarks, item_unit, category);
});

// 🔹 5️⃣ CANCEL — Hide remarks section again
$(document).on('click', '.btn-cancel', function () {
	$(this).closest('.remarks-section').addClass('d-none').find('textarea').val('');
});

function updateDispatchStatus(row, cell, unique_id, item_id, status, received_qty, remarks, unit, category) {
	let ajax_url = sessionStorage.getItem("folder_crud_link");
	var screen_unique_id = $('#screen_unique_id').val();
	var stock_id = $('#stock_id').val();

	$.ajax({
		url: ajax_url,
		type: 'POST',
		data: {
			action: 'update_dispatch_status',
			unique_id,
			item_id,
			status,
			received_qty,
			remarks,
			screen_unique_id,
			stock_id,
			unit,
			month_fill: $('#lab_month').text(),
			hostel_unique_id: $('#lab_hostel_id').text(),
			category
		},
		success: function (response) {
			let res = {};
			try { res = JSON.parse(response); } catch { res = {}; }

			if (res.status === 'success') {

				// --------------------------------------------------
				// COLOR & TEXT FORMATTING BASED ON FINAL STATUS
				// --------------------------------------------------
				let colorClass = "";
				let extraNote = "";

				if (status === "Received & Approved") {
					colorClass = "text-success fw-bold";
					extraNote = `<div class="text-muted small">(${remarks})</div>`;
				}
				else if (status === "Received & Returned") {
					colorClass = "text-warning fw-bold";
					extraNote = remarks ? `<div class="text-muted small mt-1">(${remarks})</div>` : "";
				}
				else if (status === "Not Received") {
					colorClass = "text-danger fw-bold";
					extraNote = remarks ? `<div class="text-muted small mt-1">(${remarks})</div>` : "";
				}

				// --------------------------------------------------
				// REPLACE BUTTONS WITH FINAL STATUS FORMAT
				// --------------------------------------------------
				cell.html(`
					<div class="status-display">
						<span class="${colorClass}">${status}</span>
						${extraNote}
					</div>
				`);

				// --------------------------------------------------
				// LOCK THE RECEIVED QTY INPUT
				// --------------------------------------------------
				row.find('.received_qty').prop('readonly', true);

				Swal.fire({
					icon: 'success',
					title: 'Updated!',
					text: 'Status confirmed successfully.'
				});
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Update Failed',
					text: res.message || 'Please try again.'
				});
			}
		},
		error: function () {
			Swal.fire({
				icon: 'error',
				title: 'Server Error',
				text: 'Could not connect to server.'
			});
		}
	});
}