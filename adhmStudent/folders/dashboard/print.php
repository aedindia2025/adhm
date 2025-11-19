
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

    									</tr>
    								</thead>
    								<?php
									$start = 0;
									$table_main = "ticket_creation";

									$today  =  date('Y-m-d');

									if ($_GET['status'] == "opening") {
										$where_list = " is_delete = 0 and entry_date < '" . $today . "' and status = '61e70158c066b8987'";
									}

									if ($_GET['status'] == "new") {
										$where_list = "entry_date ='" . $today . "' and is_delete = 0 ";
									}

									if ($_GET['status'] == "completed") {
										$where_list = "is_delete = 0 and entry_date <= '" . $today . "' and status = '61d70158c066bde321'";
									}

									if ($_GET['status'] == "pending") {
										$where_list = "is_delete = 0 and entry_date <= '" . $today . "' and status = '61e70158c066b8987'";
									}
									if ($_GET['status'] == "onhold") {
										$where_list = "entry_date <= '" . $today . "' and status = '61d70158c066b95654'";
									}

									$columns_list    = [
										"@a:=@a+1 s_no",
										"entry_date",
										"ticket_no",
										"(select staff_name from staff_incharge where staff_incharge.unique_id = " . $table_main . ".staff_name) AS staff_name",
										"(select project_name from project_creation where project_creation.unique_id = " . $table_main . ".project_name) AS project_name",
										"(select call_type from call_type where call_type.unique_id = " . $table_main . ".call_type) AS  call_type",
										"(select status from status_creation where status_creation.unique_id = " . $table_main . ".status) AS status",
										"(select  task_type from  task_type where task_type.unique_id = " . $table_main . ".task_type) AS  task_type",
										"problem",
										"est_hour",
										"time_taken",

									];

									$table_details_list  = [
										$table_main . ", (SELECT @a:= " . $start . ") AS a ",
										$columns_list
									];

									$result         = $pdo->select($table_details_list, $where_list);

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

												$table_data .= "<tr>";

												$table_data .= "<td>" . $value['s_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['entry_date'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['ticket_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['staff_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['project_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['task_type'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['call_type'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['status'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['est_hour'] . "</td>";
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