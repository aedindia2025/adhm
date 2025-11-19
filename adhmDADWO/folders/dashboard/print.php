<!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
	<link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->
<style>
	body {

		font-family: 'Poppins', �sans-serif;
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
							<div class=" mb-3 mt-1 text-center vendorListHeading2">
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

									<!-- <div class="col-sm-12">
													<p class="font-12">Hostel District   :  &nbsp;<strong><?= $_SESSION["district_name"]; ?></strong></p>
													</div>
													
													<div class="col-sm-12">
													<p class="font-12"> Hostel Taluk    :  <strong><?= $_SESSION['taluk_name']; ?></strong></p>
													</div>
													<div class="col-sm-12">
													<p class="font-12">Hostel ID   :  <strong><?php echo $_SESSION['hostel_main_id']; ?></strong></p>
													</div>
													<div class="col-sm-12">
													<p class="font-12">Hostel Name   :  <strong><?= $_SESSION['hostel_name']; ?></strong></p>
													</div> -->

								</div>
							</div><!-- end col -->
						</div>
						<div class="zone_recom3">
							<div class="box1">
								<table cellspacing="0" cellpadding="0" class="" width="100%"
									style="font-family: monospace;">
									<thead class="colspanHead">
										<tr>
											<th width="5%" colspan="1" class="blankCell">S.No</th>
											<th width="10%" colspan="1" class="blankCell">App Date</th>
											<th width="10%" colspan="1" class="blankCell">App No</th>
											<th width="10%" colspan="1" class="blankCell">Student Name</th>
											<!-- <th width="10%" colspan="1" class="blankCell">Address</th> -->
											<th width="10%" colspan="1" class="blankCell">Age</th>

											<th width="10%" colspan="1" class="blankCell">Status</th>
											<!-- <th width="10%" colspan="1" class="blankCell">Priority</th> -->


										</tr>
									</thead>
									<?php
									$start = 0;


									$today = date('Y-m-d');
									$district_name = $_GET['district_name'];
									$taluk_name = $_GET['taluk_name'];
									$hostel_name = $_GET['hostel_name'];

									if ($_GET['status'] == "approved") {
										$table_main = "std_reg_s";
										$table_main_2 = "std_reg_s2";
										$where_list .= " is_delete = 0 ";
										if ($district_name != '') {
											$where_list .= " AND hostel_district_1 ='" . $district_name . "'";
										}
										if ($taluk_name != '') {
											$where_list .= " AND hostel_taluk_1 ='" . $taluk_name . "'";
										}
										if ($hostel_name != '') {
											$where_list .= " AND hostel_name ='" . $hostel_name . "'";
										}


									}
									if ($_GET['status'] == "rejected") {
										$table_main = "batch_creation";
										$where_list = " is_delete = 0 and status = '2' ";
										if ($district_name != '') {
											$where_list .= " AND hostel_district ='" . $district_name . "'";
										}
										if ($taluk_name != '') {
											$where_list .= " AND hostel_taluk ='" . $taluk_name . "'";
										}
										if ($hostel_name != '') {
											$where_list .= " AND hostel_name ='" . $hostel_name . "'";
										}

									}
									if ($_GET['status'] == "acceptance") {
										$table_main = "batch_creation";
										$where_list = " is_delete = 0";
										if ($district_name != '') {
											$where_list .= " AND hostel_district ='" . $district_name . "'";
										}
										if ($taluk_name != '') {
											$where_list .= " AND hostel_taluk ='" . $taluk_name . "'";
										}
										if ($hostel_name != '') {
											$where_list .= " AND hostel_name ='" . $hostel_name . "'";
										}

									}
									
									if ($_GET['status'] == "applied") {
										$table_main = "std_app_s";
										$table_main_2 = "std_app_s2";
										$where_list = " is_delete = 0 ";
										if ($district_name != '') {
											$where_list .= " AND hostel_district_1 ='" . $district_name . "'";
											// $where_list .= " is_delete = 0 ";
										}
										if ($taluk_name != '') {
											$where_list .= " AND hostel_taluk_1 ='" . $taluk_name . "'";
										}
										if ($hostel_name != '') {
											$where_list .= " AND hostel_1 ='" . $hostel_name . "'";
										}
										// $where_list .= " and is_delete = 0 ";
									}

									if ($_GET['status'] == "approved" || $_GET['status'] == "applied") {
										$columns_list = [
											"@a:=@a+1 s_no",
											"entry_date",
											"std_app_no",
											"std_name",
											"(select age from $table_main_2 where $table_main_2.s1_unique_id = $table_main.unique_id) as age",
											"status",
											// "(select priority from std_app_s7 where std_app_s7.s1_unique_id = $table_main.unique_id ) as priority"
											// "entry_date",
									
										];

									} else if ($_GET['status'] == "acceptance" || $_GET['status'] == "rejected") {
										$columns_list = [
											"@a:=@a+1 s_no",
											"applied_date as entry_date",
											"std_app_no",
											"std_name",
											"(select age from std_app_s2 where std_app_s2.s1_unique_id = $table_main.s1_unique_id) as age",
											"status",
											// "(select priority from std_app_s7 where std_app_s7.s1_unique_id = $table_main.s1_unique_id) as priority"
											// "entry_date",
									
										];
										
									} else if ($_GET['status'] == "dropout") {
										$columns_list = [
											"@a:=@a+1 s_no",
											"dropout_date as entry_date",
											"std_app_no",
											"std_name",
											"(select age from std_app_s2 where std_app_s2.s1_unique_id = $table_main.s1_unique_id) as age",
											"status",
											// "(select priority from std_app_s7 where std_app_s7.s1_unique_id = $table_main.s1_unique_id) as priority",
											// "entry_date",
										];
									}

									$table_details_list = [
										$table_main . ", (SELECT @a:= " . $start . ") AS a ",
										$columns_list
									];

									$result = $pdo->select($table_details_list, $where_list);
									
									if ($result->status) {

										$res_array = $result->data;

										$table_data = "";
										if (count($res_array) == 0) {
											$table_data .= "<tr>";

											$table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
											$table_data .= "</tr>";
										} else {
											foreach ($res_array as $key => $value) {

												switch ($value['status']) {
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

												switch ($value['priority']) {
													case 1:
														$value['priority'] = 'Priority 1';
														break;
													case 2:
														$value['priority'] = 'Priority 2';
														break;
													case 3:
														$value['priority'] = 'Priority 3';
														break;

												}
												$value['entry_date'] = disdate($value['entry_date']);

												$table_data .= "<tr>";

												$table_data .= "<td>" . $value['s_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['entry_date'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_app_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_name'] . "</td>";

												$table_data .= "<td style = 'text-align : left'>" . $value['age'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['status'] . "</td>";
												// $table_data .= "<td style = 'text-align : left'>" . $value['priority'] . "</td>";
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