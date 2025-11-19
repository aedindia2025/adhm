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
                        <h4 class="page-title">Product Type</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <table id="product_type_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                    <th>S.no</th>
                                        <th>Product Category</th>
                                        <th>Product Type</th>
                                        <th>Unit Category</th>
                                        <th>description</th>
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