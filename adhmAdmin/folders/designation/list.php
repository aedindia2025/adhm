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
            <div class="col-10">
            <div class="page-title-box">                                    
            
            <h4 class="page-title">Designation</h4>
            </div>
            </div>
            <div class="col-md-2 align-self-center">
            <div class="page-title-right">
             <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            </form>
           </div>
            </div>
        </div>
                 
                       
                        
    <div class="row">
    <div class="col-12">
    <div class="card">
    <div class="card-body">
        <table id="student_datatable"  class="table dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Designation Type</th>
                    <th>Designation</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
             <tbody> 
                <!-- <tr>
                     <td>1</td>
                    <td>Admin</td>
                    <td>Active</td>
                    <td class=" text-center">
                    <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                     <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a></td> 
                </tr>
                 <tr>
                    <td>2</td>
                    <td>Staff</td>
                    <td>Active</td>
                    <td class=" text-center">
                    <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                    <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a></td>
                </tr>   -->
                <!-- <tbody>
                
            

                        // foreach ($results as $row) {
                        //     echo '<tr>
                        //             <td>' . $row['s_no'] . '</td>
                        //             <td>' . $row['designation_name'] . '</td>
                        //             <td>' . $row['is_active'] . '</td>
                        //             <td>' . $row['unique_id'] . '</td>
                        //         </tr>';
                        // } -->

                        <!-- ?> -->
                        
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
<script>
    new DataTable('#demo');
</script>
