<div class="content-page">
<div class="content">
<!-- Start Content-->
<div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-10">
            <div class="page-title-box">                                    
            
            <h4 class="page-title">Biometric Attendance</h4>
            </div>
            </div>
            <div class="col-md-2 align-self-center">
            <div class="page-title-right">
             <!--<a href="index.php?file=biometric_attendance/model">  <button  class="btn btn-primary" style="float: right;">Add New</button></a>-->
            </div>
            </div>
        </div>
                 
                       
                        
    <div class="row">
    <div class="col-12">
    <div class="card">
    <div class="card-body">
        <table id="demo"  class="table dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th style="width: 8%;">S.no</th>
                    
					 <th style="width: 8%;">Name</th>
					 <th style="width: 8%;">Biometric ID</th>
					 <th style="width: 8%;">Time</th>
					  <th style="width: 8%;">Status </th>
					  <th style="width: 8%;">Face ID</th>
					  
                   	
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    
					<td>Karthi </td>
					<td>01</td>
					<td>09:30 AM</td>
					<td>IN Time</td>
					<td> - </td>
                    <!--<td class=" text-center">-->
                    <!--<a href="index.php?file=biometric_attendance/model" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>-->
                    <!--</td>-->
                </tr>
                <tr>
                    <td>2</td>
                    
					<td>Kavitha </td>
					<td>021</td>
					<td>04:30 AM</td>
					<td>OUT Time</td>
					<td>  <img src="assets/images/sample_school1.jpg" alt="logo-2" width="40%"></td>
                    <!--<td class=" text-center">-->
                    <!--<a href="index.php?file=biometric_attendance/model" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>-->
                    <!--</td>-->
                </tr>
                <tr>
                    <td>3</td>
                    
					<td>Kavitha </td>
					<td>021</td>
					<td>09:30 AM</td>
					<td>IN Time</td>
					<td>  <img src="assets/images/sample_school1.jpg" alt="logo-2" width="40%"></td>
                    <!--<td class=" text-center">-->
                    <!--<a href="index.php?file=biometric_attendance/model" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>-->
                    <!--</td>-->
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
<script>
    new DataTable('#demo');
</script>
