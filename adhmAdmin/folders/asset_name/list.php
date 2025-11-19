           <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
           <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" /> -->

           <!-- <link href="../../assets/datatable/datatable.bootstrap.min.css" />
           <link href="../../assets/datatable/bootstrap.min.css" /> -->

           <div class="content-page">
               <div class="content">

                   <!-- Start Content-->
                   <div class="container-fluid">

                       <!-- start page title -->
                        <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            </form>
                        </div>
                        <h4 class="page-title">Asset Name</h4>
                    </div>
                </div>
            </div>
                       <div class="row">
                           <div class="col-12">
                               <div class="card">
                                   <div class="card-body">

                                       <table id="asset_name_creation_datatable" class="table dt-responsive nowrap w-100">
                                           <thead>
                                               <tr>
                                                   <th>S.No</th>
                                                   <th>Asset Name</th>
                                                   <th>Asset Category</th>
                                                   <th>Description</th>
                                                   <th>Status</th>
                                                   <th>Action</th>


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

           <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
           <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
           <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script> -->

           <!-- <script src="../../assets/datatable/jquery.js"></script>
           <script src="../../assets/datatable/jquery_data.min.js"></script>
           <script src="../../assets/datatable/data.bootstrap5.min.js"></script> -->
<!-- 
           <script>
new DataTable('#demo');
           </script> -->