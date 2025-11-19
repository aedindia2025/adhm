<style>
.bg-primary {
    --ct-bg-opacity: 1;
    background-color: #c7e2ce !important;
    border-color: #1c6c30 !important;
}
</style>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Nov 2023 13:53:45 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Forgot Password | Adi Dravidar Welfare Department</title>
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

<body class="authentication-bg position-relative">
    <!-- <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">
        <svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' viewBox='0 0 800 800'>
            <g fill-opacity='0.22'>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx='400' cy='400' r='600' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx='400' cy='400' r='500' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx='400' cy='400' r='300' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx='400' cy='400' r='200' />
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx='400' cy='400' r='100' />
            </g>
        </svg>
    </div> -->
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header py-4 text-center bg-primary">
                            <a href="index.html">
                                <span><img src="assets/images/ad-logo.png" alt="logo" height="50"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Forgot Password</h4>
                                <p class="text-muted mb-4">Enter your Phone Number  and  Date of birth to change the password.
                                </p>
                            </div>

                            <form>

                                <div class="mb-3">
                                    <label class='form-label'>Phone Number</label>
                                    <input id="phone_num" type="number" class="form-control" name="phone_num" tabindex="1"
                                        required="">
                                </div>

                                <div class="mb-3">
                                    
                                    <label for="date" class="form-label">Date of Birth</label>
                                    <div class="input-group input-group-merge">
                                        <input id="date" type="date" class="form-control" name="date"
                                            tabindex="2" required="">
                                    </div>
                                </div>

                                <!-- <div class="mb-3 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div> -->

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#signup-modal"> Submit </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->
    <div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                                <h4 class="modal-title" id="standard-modalLabel">Forgot Password</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                           
            
                                                            <div class="modal-body"></div>
            
                                                                <form class="ps-3 pe-3" action="#">
            
                                                                    <div class="mb-3">
                                                                        <label for="new_password" class="form-label">New Password</label>
                                                                        <input class="form-control" type="password" id="new_password" required="" placeholder="Enter new Password">
                                                                    </div>
            
                                                                    <div class="mb-3">
                                                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                                                        <input class="form-control" type="password" id="confirm_password" required="" placeholder="enter confirm password">
                                                                    </div>
            
                                                                    <div class="mb-3 text-center">
                                                                        <button class="btn btn-primary" type="submit">Reset Password</button>
                                                                    </div>
            
                                                                </form>
            
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->

    <!-- Vendor js -->
    <!-- <script src="assets/js/vendor.min.js"></script> -->

    <!-- App js -->
    <!-- <script src="assets/js/app.min.js"></script> -->

    <script src="assets/js/vendor.min.js"></script>

        <!-- Code Highlight js -->
        <script src="assets/vendor/highlightjs/highlight.pack.min.js"></script>
        <script src="assets/vendor/clipboard/clipboard.min.js"></script>
        <script src="assets/js/hyper-syntax.js"></script>
        
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>

</body>

<!-- Mirrored from coderthemes.com/hyper/saas/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Nov 2023 13:53:45 GMT -->

</html>