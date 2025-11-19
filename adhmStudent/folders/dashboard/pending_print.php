
    <style>
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
	// include '../../common_fun.php';

	if (isset($_GET["status"])) {
		$status = disname($_GET['status']. " Task");
	}

	?>

    <div class="container-fluid" style="background-color:#fff;">
    	<div class="compl_print pt-2">
    		<div class="zone_boxbor">
    			<div class="row">

    				<div class="col-md-12">

    					<img class="mb-3" src="../../assets/img/bg-logo.png" width="10%" />

    					<div class="row">
    						<div class="col-md-6">
    							<h6></h6>
    						</div>
    						<div class="col-md-6" style="text-align:right;">
    							<h5>DATE : <?= $today  =  date('d-m-y'); ?></h5>
    						</div>
    						<center>
    							<h3><?= $status; ?></h3>
    						</center>
    					</div>
    					<div class="zone_recom3">
    						<div class="box1">
    							<table cellspacing="0" cellpadding="0" class="" width="100%" style="font-family: monospace;">
    								<thead class="colspanHead">
    									<tr>
    										<th width="5%" colspan="1" class="blankCell">S.No</th>
    										<th width="10%" colspan="1" class="blankCell">Entry Date</th>
    										<th width="10%" colspan="1" class="blankCell">Ticket No</th>
    										<th width="10%" colspan="1" class="blankCell">Staff Name</th>
    										<th width="10%" colspan="1" class="blankCell">Project Name</th>
    										<th width="10%" colspan="1" class="blankCell">Task Type</th>
    										<th width="15%" colspan="1" class="blankCell">Call Type</th>
    										<th width="10%" colspan="1" class="blankCell">Status</th>
    										<th width="10%" colspan="1" class="blankCell">EST Hrs</th>
    										<th width="10%" colspan="1" class="blankCell">Time Taken</th>

    									</tr>
    								</thead>
    								<?php
									$start = 0;
									$table_main = "ticket_creation_sub";

									$today  =  date('Y-m-d');

									if ($_GET['status'] == "in_progress") {
										$where_list = " is_delete = 0 and remarks = '64ddaad88705279650'";
									}

									if ($_GET['status'] == "retaken") {
										$where_list = "remarks = '64ddab8516d3815801' and is_delete = 0 ";
									}

									if ($_GET['status'] == "testing") {
										$where_list = "is_delete = 0 and remarks = '64ddab60a26ea75306'";
									}

									if ($_GET['status'] == "pending") {
										$where_list = "is_delete = 0 and remarks = '64ddabc5ec02593935'";
									}
									if ($_GET['status'] == "onhold") {
										$where_list = "is_delete = 0 and remarks = '64ddaaaf0992a40367'";
									}
									if ($_GET['status'] == "bug_raised") {
										$where_list = "is_delete = 0 and remarks = '64e04797d97fc14845'";
									}
									if ($_GET['status'] == "completed") {
										$where_list = "is_delete = 0 and remarks = '64ddab6d45a6393125'";
									}
									if ($_GET['status'] == "deployed") {
										$where_list = "is_delete = 0 and remarks = '6525210bd25da18278'";
									}
									

									$columns_list    = [
										"@a:=@a+1 s_no",
										"entry_date",
										"(select ticket_no from ticket_creation where ticket_creation.unique_id = ".$table_main.".form_unique_id) as ticket_no",
										"(select staff_name from staff_incharge where staff_incharge.unique_id = " . $table_main . ".staff_name) AS staff_name",
										"(select project_name from ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS project_name",
										"(select call_type from ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS  call_type",
										"(select status from ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS status",
										"(select  task_type from  ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS  task_type",
										"(select  problem from  ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS  problem",

										"(select  est_hour from  ticket_creation where ticket_creation.unique_id = " . $table_main . ".form_unique_id) AS  est_hour",

										"time_taken",

									];

									$table_details_list  = [
										$table_main . ", (SELECT @a:= " . $start . ") AS a ",
										$columns_list
									];

									$result         = $pdo->select($table_details_list, $where_list);
                                // print_r($result);

									if ($result->status) {

										$res_array      = $result->data;

										$table_data     = "";
										if (count($res_array) == 0) {
											$table_data .= "<tr>";

											$table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
											$table_data .= "</tr>";
										} else {
											foreach ($res_array as $key => $value) {


												$value['entry_date']        = disdate($value['entry_date']);
												$project_name = project($value['project_name'])[0]['project_name'];
												$task_type = task_type($value['task_type'])[0]['task_type'];
												$call_type = call_type($value['call_type'])[0]['call_type'];
												$status = status_type($value['status'])[0]['status'];
											

												$table_data .= "<tr>";

												$table_data .= "<td>" . $value['s_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['entry_date'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['ticket_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['staff_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $project_name . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $task_type . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $call_type . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $status . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['est_hour'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['time_taken'] . "</td>";
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