<?php

?>
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Infrastructure and Facility</h4>
                    </div>
                </div>
                <!-- <div class="col-md-2 align-self-center">
                    <div class="page-title-right">
                        <a href="index.php?file=digital_infrastructure/model">
                            <button class="btn btn-primary" style="float: right;">Add New</button>
                        </a>
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                      
                            <table id="digital_infrastructure_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Academic Year</th>
                                        <th>Land Detail</th>
                                        <th>Land Owner Detail</th>
                                        <th>Existing/Demolished</th>
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
</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#demo');
</script>