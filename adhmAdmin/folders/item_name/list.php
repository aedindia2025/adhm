           <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
           <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />

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

                                   <h4 class="page-title">Item Name List</h4>
                               </div>
                           </div>
                       </div>
                       <!-- end page title -->
                       <div class="row mb-2">

                           <div class="col-md-12">
                               <div class="page-title-right">
                                   <a href="index.php?file=item_name/model"> <button class="btn btn-primary"
                                           style="float: right;">Add New</button></a>

                               </div>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-12">
                               <div class="card">
                                   <div class="card-body">

                                       <table id="demo" class="table dt-responsive nowrap w-100">
                                           <thead>
                                               <tr>
                                                   <th>S.No</th>
                                                   <th>Item Name</th>
                                                   <th>Item Category</th>
                                                   <th>Description</th>
                                                   <th>Action</th>


                                               </tr>
                                           </thead>


                                           <tbody>
                                               <tr>
                                                   <td>1</td>
                                                   <td>Chair</td>
                                                   <td>Plastic</td>
                                                   <td>Thindal Boys Hostel</td>
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
                                                   <td>Table</td>
                                                   <td>Wood</td>
                                                   <td>Thindal Boys Hostel</td>
                                                   <td class=" text-center">
                                                       <a href="javascript: void(0);" class="btn btn-success ms-2">
                                                           <i class=" uil-edit"></i>
                                                       </a>
                                                       <a href="javascript: void(0);" class="btn btn-danger ms-2">
                                                           <i class="uil-trash-alt"></i>
                                                       </a>
                                                   </td>



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