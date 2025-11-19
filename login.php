<?php include 'header.php' ?>
<style>
    body {
        overflow-x: hidden;
        overflow-y: hidden;
        background-color: #f7f9fd;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #43246e;
        border-color: #43246e;
    }

    .btn-primary {
        color: #fff;
        background-color: #7343b4;
        border-color: #7343b4;
        height: 47px;

    }

    .form-control,
    .input-group-text {
        min-height: 45px;
        border: 0px;
        background-color: #f5f5f5;
        font-size: 14px;
    }

    .form-control:focus {
        color: #212529;
        background-color: #f5f5f5;
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
    }

    .login-box {
        margin-left: 6rem;
        margin-right: 6rem;
    }
</style>

<style>

</style>




<div class="account-pages">
    <div class="">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-5"> <img src="img/student-login.jpg" style="width:100%" /> </div>
            <div class="col-lg-1"></div>
            <div class="col-xxl-5 col-lg-5">
                <div class=" py-4 text-center pt-0">
                    <a href="#">
                        <span><img src="img/ad-logo1.png"></span>
                    </a>
                </div>

                <div class="card login-box border-0">

                    <!-- Logo -->

                    <div class="card-body ">
                        <div class="text-center m-auto" STYLE="MARGIN-BOTTOM:27PX!IMPORTANT;">
                            <img src="img/student-icon-1.png" style="width:70px;">
                            <h5 class="mt-2 mb-4" style="color:#6d45b6;">Student Login</h5>
                        </div>


                        <form action="#">

                            <div class="mb-3">

                                <input class="form-control" type="email" id="emailaddress" required="" placeholder="Username">
                            </div>

                            <div class="mb-2">


                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" placeholder="Password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mb-4 pb-1">
                                <div class="form-check">
                                    <a href="forgot.php" class="text-muted float-end" style="color:#6d45b6;"><small>Forgot your password?</small></a>
                                </div>
                            </div>

                            <div class="text-center" style="margin-bottom: -46px;">
                                <button class="btn btn-primary" type="submit" style="width:90%"> Log In </button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->


                <!-- end row -->

            </div> <!-- end col -->

            <div class="col-lg-1"></div>


        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>





<!-- end row -->










<!--<div class="">
	<div class="row">

<div class="col-md-6 fxt-bg-color ">
<div class="logo-hed text-center mb-5">
<img src="img/ad-logo.png">
</div>
<div class="fxt-form shadow-sm rounded-2 p-5">

							<div class="text-center mb-4">
								<img src="img/student-icon-1.png" style="width:70px;">
								<h5 class="mt-2" style="color:#6d45b6;">Student Login</h5>
							</div>
							<form method="POST">
								<div class="form-group mb-4">
									
										<input type="text" class="form-control" name="text" placeholder="Username" required="required">
									
								</div>
								<div class="form-group mb-4">
									
										<input id="password" type="password" class="form-control" name="password" placeholder="Password" required="required">
										
									
								</div>
								
								<div class="form-group">
									<div class="text-center">
										<button type="submit" class="btn btn-primary fxt-btn-fill">Log in</button>
									</div>
								</div>
							</form>
						</div>
</div>

<div class="col-md-6">
<img src="assets/images/student-login.jpg"/>
</div>

	</div>
</div>-->


<?php include 'footer.php' ?>