$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);

    $('#assets_datatable').on('click', '.kitchen-asset-count', function() {
        var hostelId = $(this).data('hostel-id');
        // alert(hostelId); // Get the hostel ID from the clicked element
        fetchKitchenAssets(hostelId);
       
    });
    $('#assets_datatable').on('click', '.digital-asset-count', function() {
        var hostelId = $(this).data('hostel-id'); // Get the hostel ID from the clicked element
       
        fetchDigitalAssets(hostelId);
    }
);
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Moveable Asset';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'assets_datatable';
var action = "datatable";



function filter() {

	var district_name = $('#district_name').val();

	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val();



	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"action": 'datatable',
	};

	init_datatable(table_id,form_name,action,data);

}

function init_datatable(table_id='',form_name='',action='') {

	var table = $("#"+table_id);

	
	// var student_id = $('#student_id').val();
	var district_name = $('#district_name').val();
	// var current_date = $('#current_date').val();

	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val()
	// var from_year =$('#from_year').val();

	
	var data 	  = {

		// "student_id"	: student_id,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		// "current_date":current_date,
		"action"	: action, 
	};



	var ajax_url = sessionStorage.getItem("folder_crud_link");

	
	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
	dom: 'Bfrtip',
	searching: false,
	buttons: [{
		extend: 'copyHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Moveable Report'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Moveable Report',
		filename: 'moveable_report'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Moveable Report',
		filename: 'moveable_report'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Moveable Report',
		filename: 'moveable_report'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Moveable Report'
	}
	]
	});
}


function get_taluk() {


	var district_name = $('#district_name').val();

	var data = "district_name=" + district_name + "&action=district_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk_name").html(data);
			}
		}
	});

}

function get_hostel() {



	var taluk_name = $('#taluk_name').val();

	var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {


			if (data) {
				$("#hostel_name").html(data);
			}
		}
	});

}

function fetchKitchenAssets(hostelId) {
    var data = "hostel_id=" + hostelId + "&action=fetch_kitchen_assets";
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function(response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }

            var tbody = $('#kitchenAssetsTable tbody');
            tbody.empty(); // Clear existing rows

            var serialNo = 1; // Initialize serial number
            $.each(response, function(category, items) {
                // Insert category row with bold text
                var categoryRow = '<tr><td colspan="5" style="font-weight: bold; background-color: #f5f5f5;">Category:   ' + category + '</td></tr>';
                tbody.append(categoryRow);

                // Insert item rows under the category
                $.each(items, function(index, item) {
					
                    var itemRow = '<tr><td>' + serialNo + '</td><td>' + item.created + '</td><td>' + item.asset + '</td><td>' + item.quantity + '</td><td>' + item.big_small + '</td></tr>';
                    tbody.append(itemRow);
                    serialNo++; // Increment serial number for each item
                });
            });

            $('#kitchenAssetsModal').modal('show'); // Show the modal
        },
        error: function(xhr, status, error) {
            console.error('Error fetching kitchen assets:', error);
        }
    });
}


function fetchDigitalAssets(hostelId) {
    var data = "hostel_id=" + hostelId + "&action=fetch_Digital_assets";
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function(response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }

            var tbody = $('#DigitalAssetsTable tbody');
            tbody.empty(); // Clear existing rows

            var serialNo = 1; // Initialize serial number
            $.each(response, function(category, items) {
                // Insert category row with bold text
                var categoryRow = '<tr><td colspan="3" style="font-weight: bold; background-color: #f5f5f5;">Category:   ' + category + '</td></tr>';
                tbody.append(categoryRow);

                // Insert item rows under the category
                $.each(items, function(index, item) {
                    var itemRow = '<tr><td>' + serialNo + '</td><td>' + item.created + '</td><td>' + item.asset + '</td><td>' + item.quantity + '</td></tr>';
                    tbody.append(itemRow);
                    serialNo++; // Increment serial number for each item
                });
            });

            $('#DigitalAssetsModal').modal('show'); // Show the modal
        },
        error: function(xhr, status, error) {
            console.error('Error fetching Digital assets:', error);
        }
    });
}
