<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />

<!-- <link href="../../assets/datatable/datatable.bootstrap.min.css" />
           <link href="../../assets/datatable/bootstrap.min.css" /> -->
           <?php
session_start();

           if (empty($_SESSION['csrf_token'])) {
               $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
           }
           if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die('Invalid CSRF token');
            }
        }
           ?>
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
                        <h4 class="page-title">Supplier Creation</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="supplier_name_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Supplier Name</th>
                                        <th>Mobile No.</th>
                                        <th>Email ID</th>
                                        <th>City</th>
                                        <th>Status</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>


                                <tbody>
                                    <!-- <tr>
                                                   <td>1</td>
                                                   <td>Prabu</td>
                                                   <td>9898989898</td>
                                                   <td>prabu@gmail.com</td>
                                                   <td>Chennai</td>
                                                   <td class=" text-center">
                                                       <a href="javascript: void(0);" class="btn btn-success ms-2">
                                                           <i class=" uil-edit"></i>
                                                       </a>
                                                       <a href="javascript: void(0);" class="btn btn-danger ms-2">
                                                           <i class="uil-trash-alt"></i>
                                                       </a>
                                                   </td>



                                               </tr>
                                               <tr>
                                                   <td>2</td>
                                                   <td>Ranjith</td>
                                                   <td>6987898780</td>
                                                   <td>ranjith@gmail.com</td>
                                                   <td>Covai</td>
                                                   <td class=" text-center">
                                                       <a href="javascript: void(0);" class="btn btn-success ms-2">
                                                           <i class=" uil-edit"></i>
                                                       </a>
                                                       <a href="javascript: void(0);" class="btn btn-danger ms-2">
                                                           <i class="uil-trash-alt"></i>
                                                       </a>
                                                   </td> -->



                                    </tr>
                                </tbody>
                            </table>




                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- <script src="../../assets/datatable/jquery.js"></script>
           <script src="../../assets/datatable/jquery_data.min.js"></script>
           <script src="../../assets/datatable/data.bootstrap5.min.js"></script> -->

<script>
    new DataTable('#demo');
</script>