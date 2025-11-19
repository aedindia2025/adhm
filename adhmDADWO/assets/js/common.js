// Select2 Init

$(document).ready(function(){
    // Select 2 Global Initialiation
    $(".select2").select2({
        theme: 'bootstrap4',
    });


    // // DropZone Default Options
    // Dropzone.options.testFile = {
    //     url : "",
    //     maxFilesize: 50, // MB
    //     accept: ""
    // };

    $('.dropify').dropify({
        allowedFileExtensions : "pdf xlsx xls png jpeg jpg csv txt docx doc",
        maxFileSize           : "5M",
        errorsPosition        : "outside",
        error: {
            'fileSize': 'The file size is too big ({{ value }} max).',
            'minWidth': 'The image width is too small ({{ value }}}px min).',
            'maxWidth': 'The image width is too big ({{ value }}}px max).',
            'minHeight': 'The image height is too small ({{ value }}}px min).',
            'maxHeight': 'The image height is too big ({{ value }}px max).',
            'imageFormat': 'The image format is not allowed ({{ value }} only).'
        }
    }); 

    // Datatables Bottom Button init
        var a = $("#datatable-buttons").DataTable({
            lengthChange: !1,
            buttons: [{
                extend: "copy",
                className: "btn-light"
            }, {
                extend: "print",
                className: "btn-light"
            }, {
                extend: "pdf",
                className: "btn-light"
            }],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });

});



function form_reset(form_class = "",form_name = "") {
    $('.'+form_class).find('input').val('');
    $('.'+form_class).find('select').val('');
    $('.'+form_class).find('.select2').val(null).trigger('change');
    $('.'+form_class).find('textarea').val('');
    
    // $("#mySelect option[value='']").attr('selected', true)
}


// Sweet Alert Function Starts

function sweetalert(msg='',url='',callback ='',title='') {

    switch (msg) {
      case "create":
        Swal.fire({
            icon: 'success',
            title: 'Successfully Saved',
            // text: 'Modal with a custom image.',  
            //imageUrl:'img/emoji/success.webp',
            // imageWidth: 250,
            // imageHeight: 200,
            imageAlt: 'Custom image',
            showConfirmButton: true,
            timer: 2500,
            timerProgressBar: true,
            willClose: () => {
                if (url) {
                    window.location = url;
                }
            }
        });
      break;

      case "number_alert":
        Swal.fire({
            icon: 'warning',
            title: 'Enter a valid Mobile No Starts With 6, 7, 8 or 9',
            imageAlt: 'Custom image',
            showConfirmButton: true,
            timer: 3000,
            timerProgressBar: true,
            willClose: () => {
                if (url) {
                    window.location = url;
                }
            }
        });
      break;

      case "invalid_ext":
        
        Swal.fire({
            icon: 'warning',
            title: 'Upload Valid Files',
            imageAlt: 'Custom image',
            showConfirmButton: true,
            timer: 3000,
            timerProgressBar: true,
            willClose: () => {
                if (url) {
                    window.location = url;
                }
            }
        });
      break;
  
      case "update":
        Swal.fire({
            icon: 'success',
            title: 'Successfully Updated',
            //imageUrl:'img/emoji/clapping.webp',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
                if (url) {
                    window.location = url;
                }
            }
        });
      break;
  
      case "error":
        Swal.fire({
            icon: 'error',
            title: 'Error Occured',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
              // alert("Hi");
            }
        });
      break;

      case "doc_error":
        Swal.fire({
            icon: 'error',
            title: 'Please select only image files (JPEG, JPG, PNG) or PDF and Excel files.',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
              // alert("Hi");
            }
        });
      break;
      
      case "network_err":
        Swal.fire({
            icon: 'error',
            title: 'Network Error Occured',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
              // alert("Hi");
            }
        });
      break;

      // mythili
      case "demo":
      Swal.fire({
        icon: 'warning',
        title: 'End-time must be bigger then Start-time!',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        willClose: () => {
          // alert("Hi");
        }
    });
  break;
  case "otp":
      Swal.fire({
        icon: 'success',
        title: 'OTP verified!',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        willClose: () => {
          // alert("Hi");
        }
    });
  case "otp_verify":
      Swal.fire({
        icon: 'warning',
        title: 'Please Enter valid OTP!',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        willClose: () => {
          // alert("Hi");
        }
    });
  break;
  // end



  
      case "already":
        Swal.fire({
             icon: 'warning',
            title: 'Already Exist',
            //imageUrl:'img/emoji/already.webp',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,
            willClose: () => {
              // alert("Hi");
            }
        });
      break;

      case "no_internet":
        Swal.fire({
            icon: 'warning',
            title: 'Please Check Your Internet Connection!',
            showConfirmButton: true,
            timer: 2000,
            timerProgressBar: true,

            willClose: () => {
              // alert("Hi");
            }
        });
      break;

       case "no_location":
        Swal.fire({
            icon: 'warning',
            title: 'Please Check Your Geo Location!',
            showConfirmButton: true,
            timer: 2000,
            
            timerProgressBar: true,
            willClose: () => {
              // alert("Hi");
            }
        });
      break;
  
      case "delete":
        return Swal.fire({
          title: 'Are you sure to Delete?',
         // text: "You won't be able to revert this!",
          icon: 'warning',
          //imageUrl:'img/emoji/delete.webp',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
          preConfirm: () => {
            return true;
          }
        });
      break;

      case "success_delete":
        Swal.fire({
             icon: 'success',
            title: 'Deleted!',
            //imageUrl:'img/emoji/success_delete.webp',
            showConfirmButton: true,
            timer: 1500,
            timerProgressBar: true
        });
      break;
  
      case "form_alert":
        Swal.fire({
           icon: 'info',
          title: 'Fill Out All Mandatory Fields',
          //imageUrl:'img/emoji/form_fill.webp',
          showConfirmButton: true,
          timer: 2000,
          timerProgressBar: true
        })
      break;

    
      case "approve":
        Swal.fire({
            icon: 'success',
            title: 'Successfully Approved',
            showConfirmButton: true,
            timer: 2000,
            willClose: () => {
              window.location = url;
            }
        });
      break; 

      case "convert":
        Swal.fire({
            icon: 'success',
            title: 'Successfully Converted',
            showConfirmButton: true,
            timer: 2000,
            willClose: () => {
              window.location = url;
            }
        });
      break; 
      
      case "add":
        Swal.fire({
            icon: 'success',
            title: 'Successfully Added',
		    //imageUrl:'img/emoji/success_delete.webp',
            showConfirmButton: true,
            timer: 2000,
            willClose: () => {
            //   window.location = url;
            }
        });
      break;

      case "custom":
        Swal.fire({
            icon: 'info',
            title: title,
            willClose: () => {

              if (url != "") {
                window.location = url;
              }
            }
        });
      break;

	  case "password_alert":
        Swal.fire({
           icon: 'info',
          title: 'Please Update either Password Or Profile Image',
          //imageUrl:'img/emoji/form_fill.webp',
          showConfirmButton: true,
          timer: 2000,
          timerProgressBar: true
        })
      break;
    }
}

//   Sweet Alert Delete Confirmation Function
confirm_delete = function(msg) {
	return sweetalert(msg); // <--- return the swal call which returns a promise
};

function is_online() {
    return true;
    // return(navigator.onLine);
    return false;
}

// Form Validity Checking Function
function form_validity_check(class_name = '',form_name = '') {

    var forms         = document.getElementsByClassName(class_name);

    // If ID based Form validation Needs Not Working
    if (form_name) {
        var forms         = document.getElementsByName(form_name);
    }

    console.log(forms);

    var formValidity  = false;
    var validation    = Array.prototype.filter.call(forms, function (form) {
  
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
        formValidity = false;
      } else {
        formValidity = true;
      }
    });
    if (formValidity) {
      return true;
    } else {
      return false;
    }
}

 $(document).ready(function(){

  // Datatables Bottom Button init
        // var a = $("#datatable-buttons").DataTable({
        //     lengthChange: !1,
        //     buttons: [{
        //         extend: "copy",
        //         className: "btn-light"
        //     }, {
        //         extend: "print",
        //         className: "btn-light"
        //     }, {
        //         extend: "pdf",
        //         className: "btn-light"
        //     }],
        //     language: {
        //         paginate: {
        //             previous: "<i class='mdi mdi-chevron-left'>",
        //             next: "<i class='mdi mdi-chevron-right'>"
        //         }
        //     },
        //     drawCallback: function () {
        //         $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        //     }
        // });

}); 

$.extend( $.fn.dataTable.defaults, {
    // searching: false,
    destroy     : true,
    stateSave   : true,
    ordering    : false,
    responsive  : true,
    paging      : true,
    processing  : true,
    serverSide  : true,
    searching   : true,
    "columnDefs": [
        {"className": "text-center", "targets": [0, -1]}
    ],
    lengthMenu  : [
        [10,25,50,-1],
        [10,25,50,"All"]
    ],
    // language    : {
    //     paginate    : {
    //         previous    : "<i class='mdi mdi-chevron-left'>",
    //         next        : "<i class='mdi mdi-chevron-right'>"
    //     },
    //     processing  : '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
    // },
    
    // drawCallback: function () {
    //     $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
    // }
} );


function currentreadingValidity (opening_reading = "", current_reading = "") {
  
  var Difference = current_reading - opening_reading; 

  // alert(Difference_In_Days);
  if (Difference < 0) {
    sweetalert("custom","","","Opening is Greater");
    return false;
    }
    
    return true;
}


function indianMoneyFormat(value = "") {

value = Number(value);

if (isNaN(value)) {
  value = 0;
}

return (value).toFixed(2).replace(/(\d)(?=(\d{2})+\d\.)/g, '$1,');

}

// From date must be lower than to date
function fromToDateValidity (fromDate = "", toDate = "") {
fromDate    = new Date(fromDate);
toDate    = new Date(toDate);

// To calculate the time difference of two dates 
var Difference_In_Time = fromDate.getTime() - toDate.getTime(); 

// To calculate the no. of days between two dates 
var Difference_In_Days = Math.ceil(Difference_In_Time / (1000 * 3600 * 24));

// alert(Difference_In_Days);
if (Difference_In_Days > 0) {
  sweetalert("custom","","","From Date Must be Equal or Lower than To Date");
  return false;
  }
  
  return true;
}


function date_format(date = ""){


  const d = new Date(date);

  var year = d.getFullYear();

  var month = ("0" + (d.getMonth() + 1)).slice(-2);

  var day= d.getDate();
  if(day <= 9)
  day = '0'+day;

  var date_format =  day + '-' + month + '-' + year;

  return date_format;

}

// Geolocation Enable check
function getLocation() {
    if (navigator.geolocation) {

      navigator.geolocation.getCurrentPosition(showPosition,showError);

    } else {

      document.getElementById('show_locaiton').innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {

  document.getElementById('latitude').value  = position.coords.latitude;
  document.getElementById('longitude').value = position.coords.longitude;

}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}

// function number_only(event) {

//  if((event.keyCode < 48)||(event.keyCode > 57)) event.returnValue = false;

// }

function number(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');

}

function number_only(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9','.',','];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');

}


function valid_aadhar_number(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   ];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function off_id(input) {

  const allowedChars = [' ', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','-','_',
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
  ];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function description_val(input) {

  const allowedChars = [
   'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
   'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ' ', ',', '.'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function mail_valid(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
   'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ' ', '_', '.','@'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_mobile_number(input) {
  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
  const validStartingChars = ['6', '7', '8', '9'];

  // Check if the first character is valid
  if (input.value.length > 0 && !validStartingChars.includes(input.value.charAt(0))) {
    var msg = "number_alert"
    sweetalert(msg);
    // Clear the input if the first character is invalid
    input.value = '';
    return;
  }

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function validateCharInput(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
   'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ' ', ',', '.', '!', '-', '/', '_', '@'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_user_name(input) {

  const allowedChars = [' ', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',' '
  ];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_address(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
   'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ' ', ',', '.','-', '/'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}


function num_only(input) {
  // Remove any non-numeric characters except period and comma
  input.value = input.value.replace(/[^0-9]/g, '');
}

function valid_password(input) {

  const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
   'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ' ', ',', '.','-','@','#','$','&','*', '/'];

  // Filter out characters that are not in the allowedChars array
  input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}