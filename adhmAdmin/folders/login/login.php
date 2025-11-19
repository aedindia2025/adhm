<?php
// Set cookie with attributes
setcookie('PHPSESSID', session_id(), [
    'expires' => time() + 3600, // Example expiration time
    'path' => '/', // Example path
    'domain' => '.yourdomain.com', // Example domain
    'secure' => true, // Set to true for HTTPS-only
    'httponly' => true, // Set HttpOnly flag
    'samesite' => 'Strict' // Set SameSite attribute
]);

session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

?>



<style>
    body {
        overflow: hidden;
        background: #ebfaff !important;
    }

    @media only screen and (min-width:321px) and (max-width:768px) {
        body {
            overflow: auto;
        }
    }

    a.back_home i {
        font-size: 23px;
    }

    a.back_home {
        background-color: #00afef;
        padding: 5px 11px;
        border-radius: 50px;
        color: #fff;
        float: inline-end;
        width: 45px;
        height: 45px;
    }

    button#loginButton {
        padding: 10px 46px;
        font-size: 17px;
        border-radius: 52px;
    }

    .input-file input {
        background: #fff !important;
        height: 68px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
    }

    .card {
        border-radius: 14px !important;
    }

    .card-header {
        border-radius: 14px 14px 0px 0px !important;
    }

    html {
        background: #ebfaff;
    }

    .overall-bg {
        background: #ebfaff;
        margin: 20px 60px;
    }

    .input-file p {
        font-size: 21px;
        color: #000;
        font-weight: 500;
        border-bottom: 1px solid #ccc;
        padding-bottom: 49px;
    }

    .input-file h3 {
        font-size: 36px;
        color: #000;
        font-weight: 700;
    }

    form.frm-to {
        margin-top: 41px;
    }

    button#loginButton {
        padding: 10px 46px;
        font-size: 19px;
        border-radius: 10px;
        border: 1px solid #000;
        width: 100%;
        margin-top: 32px;
        color: #000;
    }

    button#loginButton:hover {
        border: 1px solid #00afef;
    }

    button#change-captcha {
        padding: .45rem .9rem;
        font-size: .9rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--ct-body-color);
        text-align: center;
        white-space: nowrap;
        background-color: var(--ct-tertiary-bg);
        border: var(--ct-border-width) solid var(--ct-border-color);
        border-radius: .25rem;
    }

    img#captcha-image {
        border-radius: 8px 0px 0px 8px;
    }
</style>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Nov 2023 13:53:45 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Log In | Adi Dravidar Welfare Department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png">

    <!-- Theme Config Js -->
    <script src="assets/js/hyper-config.js"></script>

    <!-- App css -->
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <script>
        resetSessionCookie();

        function resetSessionCookie() {
            // Resetting session cookie securely
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
    </script>

</head>

<div class=" overall-bg">

    <div class="row mb-3">
        <div class="col-md-6">
            <img src="assets/images/ad-logo.png">
        </div>
        <div class="col-md-6">
            <a href="../index.php" class="back_home"><i class="mdi mdi-home"></i></a>
        </div>
    </div>
    <div class="row ">
        <div class="col-md-12 col-xl-7 col-sm-12 align-self-center">
            <img src="assets/images/Staffing.webp" width="80%">
        </div>
        <div class="col-md-12 col-xl-4 col-sm-12 input-file text-center">
            <h3>Welcome</h3>
            <p>Login into Admin Account</p>
            <form class="frm-to" method="POST">
                <div class="mb-3">
                    <input type="hidden" id="token" value="<?= $_SESSION['token']; ?>">
                    <input id="user_name" type="text" class="form-control" name="user_name" tabindex="1" required=""
                        placeholder="User Name" autofocus="">
                </div>
                <div class="mb-3">

                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control pe-5 password-input" placeholder="Enter password"
                            id="password">
                        <div class="input-group-text" data-password="false">
                            <span class="password-eye" onclick="togglePasswordVisibility()"></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group input-group-merge">
                        <img id="captcha-image" src="" alt="CAPTCHA Image">
                        <input type="text" class="form-control" placeholder="Enter Captcha" name="captcha" id="captcha"
                            required>
                        <button type="button" id="change-captcha" onclick="captch()"><i
                                class="mdi mdi-reload"></i></button>
                    </div>
                </div>
            </form>
            <button class="btn " type="button" id="loginButton" onclick='login();'> Log In </button>

        </div>
        <div class="col-md-3 input-file">
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
<script>



    document.addEventListener("keyup", function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("loginButton").click();
        }
    });

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");

        // Toggle the type attribute of the password input
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }

    $(document).ready(function () {
        captch();
    });

    function captch() {
        $('#captcha-image').attr('src', 'captcha.php?' + Date.now());
    }
    
    $('#captcha').on('input', function () {
        this.value = this.value.toUpperCase();
    });
</script>