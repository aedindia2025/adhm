function resetSessionCookie() {
 
  document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}

function hashPassword(password) {
  return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
}
  
function login() {
   
  var internet_status = is_online();

  if (!internet_status) {
    sweetalert("no_internet");
    return false;
  }


  var user_name = $("#user_name").val();
  var password = $("#password").val();
  var captcha = $("#captcha").val();
  var token = $("#token").val();
  var encodedtoken = base256Encode(token);

  var hashedPassword = hashPassword(password);
  

  if ((user_name !== "") && (password !== "")) {
    // var data 	= $(".was-validated").serialize();

    // data  		+= "&action=login";

    var data = {
      //"acc_year" : acc_year,
      "user_name": user_name,
      "password":  hashedPassword+token,
      "token" : encodedtoken,
      "captcha": captcha,
      "action": "login",
    };


    var ajax_url = "folders/login/crud.php";

    var url = "index.php";

    $.ajax({
      type: "POST",
      url: ajax_url,
      data: data,
      success: function (data) {
          
          
        var obj = JSON.parse(data);
        var msg = obj.msg;
        var status = obj.status;
        var url = obj.url;
        var error = obj.error;



        if (msg == 'incorrect') {
          url = '';
          log_sweetalert("wrong_user");
          resetSessionCookie();
          captch();
        } else if(msg == 'success_login'){

          
        log_sweetalert(msg, url);
        window.location = "index.php?file="+url;

        }else if(msg == 'invalid_captcha'){
          url = '';
          log_sweetalert(msg);
        }
       
      }
    });
  } else {
    log_sweetalert("empty");
  }
}

// Login only Sweeet Alert Functions
function log_sweetalert(msg = '', url = '', callback = '') {

  switch (msg) {
    case "create":
      Swal.fire({
        icon: 'success',
        title: 'Successfully Saved',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        onClose: () => {
          window.location = url;
        }
      });
      break;

    case "update":
      Swal.fire({
        icon: 'success',
        title: 'Successfully Updated',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        onClose: () => {
          window.location = url;
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
        onClose: () => {
          // alert("Hi");
        }
      });
      break;

      case "invalid_captcha":
        Swal.fire({
          icon: 'error',
          title: 'Invalid Captcha',
          showConfirmButton: true,
          timer: 2000,
          timerProgressBar: true,
          onClose: () => {
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
        onClose: () => {
          // alert("Hi");
        }
      });
      break;



    case "already":
      Swal.fire({
        icon: 'warning',
        title: 'Already Exist',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true,
        onClose: () => {
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
        onClose: () => {
          // alert("Hi");
        }
      });
      break;

    case "delete":
      return Swal.fire({
        title: 'Are you sure to Delete?',
        text: "You won't be able to revert this!",
        icon: 'warning',
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
        showConfirmButton: true,
        timer: 1500,
        timerProgressBar: true
      });
      break;

    case "form_alert":
      Swal.fire({
        icon: 'info',
        title: 'Fill Out All Mantantory Fields',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true
      })
      break;

    case "wrong_user":
      Swal.fire({
        icon: 'warning',
        title: 'Username or Password Incorrect',
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
        onClose: () => {
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
        onClose: () => {
          window.location = url;
        }
      });
      break;

      case "success_login":
        Swal.fire({
          icon: 'success',
          title: 'Successfully Login',
          showConfirmButton: true,
          timer: 2000,
          onClose: () => {
            window.location = url;
          }
        });
        break;

    case "empty":
      Swal.fire({
        icon: 'info',
        title: 'Enter Username and Password!',
        showConfirmButton: true,
        timer: 2000,
        timerProgressBar: true
      })
      break;
  }
}

function is_online() {
  return true;
  // return(navigator.onLine);
  return false;
}


// document.addEventListener("keydown", function (event) {
//   if (event.key === "Enter") {
//     event.preventDefault();
//     login();
//   }
// });

const passwordField = document.getElementById("password");
const showPasswordToggle = document.getElementById("show-password-toggle");
const eyeIcon = document.getElementById('eye-icon');

// showPasswordToggle.addEventListener("click", function() {
//   if (passwordField.type === "password") {
//     passwordField.type = "text";
//     eyeIcon.classList.add('right-to-left');
//   } else {
//     passwordField.type = "password";
//     eyeIcon.classList.remove('right-to-left');
//   }
// });



function base256Encode(str) {
  var result = '';
  for (var i = 0; i < str.length; i++) {
    var charCode = str.charCodeAt(i);
    result += pad(charCode, 3);
  }
  return result;
}

function pad(num, size) {
  var s = num + "";
  while (s.length < size) s = "0" + s;
  return s;
}
