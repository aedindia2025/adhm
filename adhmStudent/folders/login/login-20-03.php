<style>
body {
    overflow: hidden;
}
@media only screen and (min-width:321px) and (max-width:768px){
	body {
    overflow: auto;
}
}
</style>
<head>
    <meta charset="utf-8" />
    <title>Adi Dravidar</title>
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

</head>

<div class="row">
<div class="col-md-5">
 <img src="assets/images/student-login.jpg" style="width:100%" /> 
</div>
<div class="col-lg-1"></div>
<div class="col-lg-4 justify-content-center "  style="align-self: center;">

                        <div class="card">

                                    
                               
                            <!-- Logo -->
                            <div class="card-header py-1 text-center bg-info">
                                <h4 style="color: #fff;">Student Login</h4>
                            </div>

                            <div class="card-body p-4 ">
                                
                                <center><img src="assets/images/ad-logo1.png" alt="logo"  class="mb-4"></center>

                                <form action="#">

                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">User Name</label>
                                <input id="user_name" type="text" class="form-control" name="user_name" tabindex="1" required=""  autofocus="">
                                    </div>

                                    <div class="mb-3">
                                        
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                <input type="password" class="form-control pe-5 password-input"  id="password">
                                            <div class="input-group-text" data-password="false">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="mb-0 mt-4 text-center">
                                        <button class="btn btn-info" onclick='login();' type="button"  id="loginButton"> Log In </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        
                        <!-- end row -->

                    </div>
					<div class="col-lg-2"></div>
</div>











<script>

document.addEventListener("keyup", function(event) {
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
</script>






<?php include 'inc/footer.php'; ?>