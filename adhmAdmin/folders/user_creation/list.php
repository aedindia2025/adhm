   
<section class="section">
  <div class="section-header">
    <h1>User Creation</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Admin</a></div>
      <div class="breadcrumb-item">User Creation</div>
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="row">
            <div class="col-md-6">
              <div class="card-header">
                <h4>User Creation List</h4>
              </div>
            </div>
            <div class="col-md-6">
                					<div class="fw">
					<a href="" class="help-con" data-toggle="modal" data-target="#basicModal">
					<i class="fa fa-question"></i>
					</a>
      <!-- basic modal -->
        <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog help-p" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Help</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body help-pop">
                  <div class="row">
                      <div class="col-md-3 hlep-gif">
                          <img src="assets/img/help.gif">
                      </div>
                   <div class="col-md-9 help-para">
                    <?php $help  =   help_description($_GET['file']); 
                       echo $help[0]['description'];
                    ?>
                    </div>
              </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
              <div class="btn-header">
                <?php echo btn_add($btn_add); ?></h5>
              </div>
            </div>
          </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="user_creation_datatable" style="width:100%;">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>User Type</th>
                    <th>Staff Name</th>
                    <th>User Name</th>
                    <th>Password</th>
                    <th>Branch</th>
                    <th>Warehouse</th>
                    <th>Status</th>
                    <th><div align="center">Action</div></th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  
</section>
