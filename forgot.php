<?php include'header.php'?>
<style>
body{overflow-x:hidden;overflow-y:hidden;background-color:#f7f9fd;}
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
.form-control, .input-group-text{
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
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
}
.login-box{margin-left:6rem;margin-right:6rem;}
</style>

<style>

</style>


           
				
				<div class="account-pages">
            <div class="">
                <div class="row justify-content-center align-items-center">
				<div class="col-lg-5"> <img src="img/student-login.jpg" style="width:100%"/> </div>
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
								<h5 class="" style="color:#6d45b6;">Forgot Password</h5>
							</div>
                                

                                <form action="#">

                                    <div class="mb-3">
                                        
                                        <input class="form-control" type="number" id="emailaddress" required="" placeholder="Mobile Number">
                                    </div>

                                    <div class="mb-4">
                                       
                                     
                                        <div class="input-group input-group-merge">
                                            <input type="date" id="date" class="form-control" placeholder="">
                                            
                                        </div>
                                    </div>

                                  

                                    <div class="text-center" style="margin-bottom: -47px;">
                                        <button class="btn btn-primary" type="submit" style="width:90%" data-bs-toggle="modal" data-bs-target="#centermodal"> Submit </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                    </div>
                        <!-- end card -->
<div class="row ">
                            <div class="col-12 text-center">
                                <p class="text-muted mt-3">Back to <a href="login.php" class="text-muted ms-1"><b>Log In</b></a></p>
                            </div> <!-- end col -->
                        </div>
                       
                        <!-- end row -->

                    </div> <!-- end col -->
					
					<div class="col-lg-1"></div>
					
					
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
					
					
	<!-- Center modal content -->
	<div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title w-100 text-center" id="myCenterModalLabel">Change Password</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pe-5 ps-5 pt-4 pb-4">
								<form action="#">
                                    <div class="mb-3">
                                        <input class="form-control" type="number" id="emailaddress" required="" placeholder="New Password">
                                    </div>
                                    <div class="mb-3">
                                        <div class="input-group input-group-merge">
                                            <input type="number" id="date" class="form-control" placeholder="Confirm Password">
                                            
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-primary" type="submit" style="width:40%" data-bs-toggle="modal" data-bs-target="#centermodal"> Submit </button>
                                    </div>
                                </form>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
            				
	

<?php include'footer.php'?>