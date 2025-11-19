    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->



	<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit;
}

?>
    <style>

body {
    
    font-family: 'Poppins', sans-serif;
}
    	body {
    		background-color: #fff;
    	}

    	.zone_recom {
    		border: 1px solid #ccc;
    		padding: 14px;
    		margin-bottom: 30px;
    	}

    	.box1 h3 {
    		background-color: #f0f0f0;
    		padding: 4px;
    		text-align: center;
    		font-weight: 700;
    		color: #333;
    		font-size: 14px;
    	}

    	.bd-highlight {
    		font-size: 14px;
    		color: #444;
    	}

    	.contn_info.d-flex h6 {
    		text-align: right;
    		font-size: 11.5px;
    		margin-bottom: 4px;
    	}

    	.contn_info.d-flex h5 {
    		color: #000;
    		font-size: 11.5px;
    		margin-bottom: 4px;
    	}

    	.contn_info.d-flex p {
    		margin-bottom: 4px;
    	}

    	.zone_boxbor {

    		margin-bottom: 20px;

    	}

    	.zone_recom1,
    	.zone_recom3 {
    		/* border: 1px solid #ccc; */
    		padding: 4px;
    	}

    	.zone_recom2 {
    		/* border: 1px solid #ccc; */
    		padding: 4px;
    	}

    	.col-md-4 {
    		width: 33.33333333%;
    		padding-left: 5px;
    		padding-right: 5px;
    	}

    	.col-md-8 {
    		flex: 0 0 auto;
    		width: 66.66666667%;
    	}

    	.col-md-4.wid1 {
    		width: 45%;
    	}

    	.col-md-8.wid2 {
    		width: 55%;
    	}

    	table,
    	th,
    	td {
    		border: 1px solid #ccc;
    		border-collapse: collapse;
			
    	}

    	th,
    	td {
    		padding: 5px;
    		text-align: left;
			
    	}

    	.print_icon {
    		text-align: right;
    		font-size: 33px;
    	}
    </style>
    <?php

	include '../../config/dbconfig.php';

	



	?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->

    <div class="container-fluid" style="background-color:#fff;">
    	<div class="compl_print pt-2">
    		<div class="zone_boxbor">
    			<div class="row">

    				<div class="col-md-12">

    					<div class="clearfix">
							<center>
                                            <div class=" mb-3 mt-1 text-center vendorListHeading2" >
                                                <img src="../../../assets/images/ad-logo.png" alt="dark logo" height="50">
                                            </div>
	</center>

											<!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Hostel Information</b></p>
													
                                                </div> -->
                                            <!-- </div>end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												
													<div class="col-sm-12">
													<p class="font-12">Hostel District   :  &nbsp;<strong><?=$_SESSION["district_name"];?></strong></p>
													</div>

													<div class="col-sm-12">
													<p class="font-12">Date   :  &nbsp;<strong><?=date('d-m-Y');?></strong></p>
													</div>
													
													<!-- <div class="col-sm-12">
													<p class="font-12"> Hostel Taluk    :  <strong><?=$_SESSION['taluk_name'];?></strong></p>
													</div>
													<div class="col-sm-12">
													<p class="font-12">Hostel ID   :  <strong><?php echo $_SESSION['hostel_main_id']; ?></strong></p>
													</div>
													<div class="col-sm-12">
													<p class="font-12">Hostel Name   :  <strong><?=$_SESSION['hostel_name'];?></strong></p>
													</div> -->
													
                                                </div>
                                            </div><!-- end col -->
											</div>
    					<div class="zone_recom3">
    						<div class="box1">
    							<table cellspacing="0" cellpadding="0" class="" width="100%" style="font-family: monospace;">
    								<thead class="colspanHead">
    									<tr>
    										<th width="5%" colspan="1" class="blankCell">S.No</th>
    										<th width="10%" colspan="1" class="blankCell">App Date</th>
    										<th width="10%" colspan="1" class="blankCell">App No</th>
    										<th width="10%" colspan="1" class="blankCell">Hostel Name</th>
    										<th width="10%" colspan="1" class="blankCell">Student Name</th>
    										<!-- <th width="10%" colspan="1" class="blankCell">Address</th> -->
    										<th width="10%" colspan="1" class="blankCell">Age</th>
    										
    										<th width="10%" colspan="1" class="blankCell">Status</th>
    										

    									</tr>
    								</thead>
    								<?php
									$start = 0;
									$table_main = "std_app_s";

									$today  =  date('Y-m-d');

									if ($_GET['status'] == "approved") {
										$where_list = " is_delete = 0 and status = '1' and hostel_taluk_1 = '".$_SESSION["taluk_id"]."'";
									}
									if ($_GET['status'] == "rejected") {
										$where_list = " is_delete = 0 and status = '2' and hostel_taluk_1 = '".$_SESSION["taluk_id"]."'";
									}
									if ($_GET['status'] == "acceptance") {
										$where_list = " is_delete = 0 and batch_no != '' and hostel_taluk_1 = '".$_SESSION["taluk_id"]."'";
									}
									if ($_GET['status'] == "applied") {
										$where_list = " is_delete = 0 and hostel_taluk_1 = '".$_SESSION["taluk_id"]."'";
									}
									
									

									$columns_list    = [
										"@a:=@a+1 s_no",
										"entry_date",
										"std_app_no",
										"(select std_name from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as std_name",
										"(select age from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as age",
										// "(select hostel_name from std_app_s7 where std_app_s7.s1_unique_id = std_app_s.unique_id) as hostel_name "
										// "entry_date",
										"hostel_1 as hostel_name",

									];

									$table_details_list  = [
										$table_main . ", (SELECT @a:= " . $start . ") AS a ",
										$columns_list
									];

									$result         = $pdo->select($table_details_list, $where_list);
// print_r($result);die();
									if ($result->status) {

										$res_array      = $result->data;

										$hostel_name = hostel_name($value['hostel_name'])[0]['hostel_name'];

										// echo $hostel_name;

										$table_data     = "";
										if (count($res_array) == 0) {
											$table_data .= "<tr>";

											$table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
											$table_data .= "</tr>";
										} else {
											foreach ($res_array as $key => $value) {

												switch($value['status']){
													case 0:
														$value['status'] = 'Pending';
														break;
														case 1:
															$value['status'] = 'Approved';
															break;
															case 2:
																$value['status'] = 'Rejected';
																break;

												}
												$value['entry_date']        = disdate($value['entry_date']);

												$table_data .= "<tr>";

												$table_data .= "<td>" . $value['s_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['entry_date'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_app_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $hostel_name. "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['age'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['status'] . "</td>";
												$table_data .= "</tr>";
											}
										}
									}

									// }
									?>

    								<tbody>
    									<?php echo $table_data; ?>
    								</tbody>
    							</table>
    						</div>
    					</div>
    				</div>


    			</div>
    		</div>


    	</div>
    </div>