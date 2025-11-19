    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->
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
        

        table tr th, td {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            border: 1px solid #cccccc94 !important;
        }
    </style>
    <link href='../../assets/css/app-saas.min.css' rel='stylesheet' type='text/css'>
    <?php
 



	include '../../config/dbconfig.php';

	if (isset($_GET["unique_id"])) {
        if (!empty($_GET["unique_id"])) {
    
            $unique_id = $_GET["unique_id"];
            $where = [
                "batch_no" => $unique_id
            ];
    
            $table = "batch_creation";
    
            $columns = [
              "batch_no"
            ];
    
            $table_details = [
                $table,
                $columns
            ];
    
            $result_values = $pdo->select($table_details, $where);
            // print_r($result);
    
            if ($result_values->status) {
    
                $result_values      = $result_values->data;
    
                $batch_no         = $result_values[0]["batch_no"];
               
    
                if ($approval_status == '1') {
                    $approval_status  = 'pending';
                }
                if ($approval_status == '2') {
                    $approval_status  = 'Approved';
                }
                if ($approval_status == '3') {
                    $approval_status  = 'Rejected';
                }
    
    
                $btn_text           = "Update";
                $btn_action         = "update";
            } else {
                $btn_text           = "Error";
                $btn_action         = "error";
                $is_btn_disable     = "disabled='disabled'";
            }
        }
    }



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
                                                <div class="mt-0 float-sm-left" style="margin: 0px 7px;">
												<div class="row">
												
													
													<div class="col-md-8">
													<p class="font-12">Hostel Name   :  <strong><?=$_SESSION['hostel_name'];?></strong></p>
													</div>
                                                    <div class="col-md-4" style="text-align: end;">
													<p class="font-12">Printed Date   :  <strong><?=date('d-m-Y');?></strong></p>
													</div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-sm-12">
													<p class="font-12">Hostel Id   :  <strong><?=hostel_name($_SESSION['hostel_id'])[0]['hostel_id'];?></strong></p>
													</div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-sm-12">
													<p class="font-12">Batch No   :  <strong><?=$batch_no;?></strong></p>
													</div>
                                                    
													
                                                </div>
                                            </div><!-- end col -->
											</div>
    					<div class="zone_recom3">
    						<div class="box1">
    							<table cellspacing="0" cellpadding="0" class="" width="100%" style="font-family: monospace;">
    								<thead class="colspanHead">
    									<tr>
    										<th width="5%" colspan="1" class="blankCell">S.No</th>
    										<th width="10%" colspan="1" class="blankCell">Student Application Number</th>
    										<th width="10%" colspan="1" class="blankCell">Student Name (As per Aadhaar)</th>
    										<th width="10%" colspan="1" class="blankCell">Student Name (As per EMIS/UMIS)</th>
    										<th width="10%" colspan="1" class="blankCell">EMIS / UMIS No</th>
    										<th width="10%" colspan="1" class="blankCell">Education Institute Name</th>
    										<th width="10%" colspan="1" class="blankCell">Class / Degree Name</th>
    										<th width="10%" colspan="1" class="blankCell">Community</th>
    										<th width="10%" colspan="1" class="blankCell">Income Level</th>
    										<th width="10%" colspan="1" class="blankCell">Status</th>
    										

    									</tr>
    								</thead>
    								<?php

$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";


$mysqli = new mysqli($host, $username, $password, $databasename);

									$start = 0;
									$table_main = "batch_creation";

									$today  =  date('Y-m-d');

									$where_list = "hostel_name = '".$_SESSION['hostel_id']."' and is_delete = '0' and batch_no = '".$_GET['unique_id']."' ";
									

									$columns_list    = [
                                        "@a:=@a+1 s_no",
                                        // "batch_no",
                                        "std_app_no",
                                        "std_name",
                                        "'' as std_umis_emis_name",
                                        "'' as std_umis_emis_no",
                                        "'' as std_umis_emis_ins_name",
                                        "'' as std_umis_emis_cls_deg",
                                        "status",
                                        "unique_id",
                                        "s1_unique_id"

									];

                                    function fetchcertno($mysqli, $s1_unique_id)
                                    {
                                        $sql = "SELECT com_name as c_no,income_level as i_no FROM std_app_s5 WHERE s1_unique_id = ? LIMIT 1";
                            
                                        $stmt = $mysqli->prepare($sql);
                                        $stmt->bind_param("s", $s1_unique_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                            
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            // return strtoupper($row['std_name']);
                                            // return $row;
                                            return [
                                                'c_no' => $row['c_no'],
                                                'i_no' => $row['i_no'],
                                                
                                            ];
                                        }
                            
                                        return ''; // Return empty string if no result
                                    }
                                    function fetchStdEmisNo($mysqli, $s1_unique_id)
                                    {
                                        $sql = "SELECT std_name,emis_no,school_name,class FROM std_app_emis_s3 WHERE s1_unique_id = ? LIMIT 1";
                            
                                        $stmt = $mysqli->prepare($sql);
                                        $stmt->bind_param("s", $s1_unique_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                            
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            // return strtoupper($row['std_name']);
                                            // return $row;
                                            return [
                                                'emis_name' => strtoupper($row['std_name']),
                                                'emis_no' => $row['emis_no'],
                                                'school_name' => $row['school_name'],
                                                'class' => $row['class'],
                                            ];
                                        }
                            
                                        return ''; // Return empty string if no result
                                    }
                            
                                    function fetchUmisName($mysqli, $s1_unique_id)
                                    {
                                        $sql = "SELECT umis_name,umis_no,umis_clg_name,umis_std_degree FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";
                            
                                        $stmt = $mysqli->prepare($sql);
                                        $stmt->bind_param("s", $s1_unique_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                            
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            // return strtoupper($row['umis_name']);
                                            // return $row;
                                            return [
                                                'umis_name' => strtoupper($row['umis_name']),
                                                'umis_no' => $row['umis_no'],
                                                'umis_clg_name' => $row['umis_clg_name'],
                                                'umis_std_degree' => $row['umis_std_degree'],
                                            ];
                                        }
                            
                                        return ''; // Return empty string if no result
                                    }
                            
                                    function fetchNoUmisName($mysqli, $s1_unique_id)
                                    {
                                        $sql = "SELECT no_umis_name,no_umis_college,no_umis_course FROM std_app_umis_s4 WHERE s1_unique_id = ? LIMIT 1";
                            
                                        $stmt = $mysqli->prepare($sql);
                                        $stmt->bind_param("s", $s1_unique_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                            
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            // return strtoupper($row['no_umis_name']);
                                            return [
                                                'no_umis_name' => strtoupper($row['no_umis_name']),
                                                'no_umis_clg' => strtoupper($row['no_umis_college']),
                                                'no_umis_course' => $row['no_umis_course'],
                                            ];
                                        }
                            
                                        return ''; // Return empty string if no result
                                    }
                            
                            
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

                                                $std_emis_name = fetchStdEmisNo($mysqli, $value['s1_unique_id']);
               
                                                $umis_name = fetchUmisName($mysqli, $value['s1_unique_id']);
                                               
                                                $no_umis_name = fetchNoUmisName($mysqli, $value['s1_unique_id']);
                                                $cert_no = fetchcertno($mysqli, $value['s1_unique_id']);
                                               
                                               if(!empty($cert_no['c_no'])){
                                                $community_no = $cert_no['c_no'];
                                               }else{
                                                $community_no = 'Manually Uploaded Certificate';
                                               }
                                               if(!empty($cert_no['i_no'])){
                                                $income_no = $cert_no['i_no'];
                                               }else{
                                                $income_no = '-';
                                               }
                                
                                                if (!empty($std_emis_name['emis_name'])) {
                                                    $value['std_umis_emis_name'] = $std_emis_name['emis_name'];
                                                    $value['std_umis_emis_no'] = $std_emis_name['emis_no'];
                                                    $value['std_umis_emis_ins_name'] = $std_emis_name['school_name'];
                                                    $value['std_umis_emis_cls_deg'] = $std_emis_name['class'];
                                                } elseif (!empty($umis_name['umis_name'])) {
                                                    $value['std_umis_emis_name'] = $umis_name['umis_name'];
                                                    $value['std_umis_emis_no'] = $umis_name['umis_no'];
                                                    $value['std_umis_emis_ins_name'] = $umis_name['umis_clg_name'];
                                                    $value['std_umis_emis_cls_deg'] = $umis_name['umis_std_degree'];
                                                } elseif (!empty($no_umis_name['no_umis_name'])) {
                                                    $value['std_umis_emis_name'] = $no_umis_name['no_umis_name'];
                                                    $value['std_umis_emis_no'] = 'No UMIS Entry';
                                                    $value['std_umis_emis_ins_name'] = $no_umis_name['no_umis_clg'];
                                                    $value['std_umis_emis_cls_deg'] = courseName($no_umis_name['no_umis_course']);
                                                } else {
                                                    $value['std_umis_emis_name'] = '-'; // Default value if none found
                                                    $value['std_umis_emis_no'] = '-'; // Default value if none found
                                                    $value['std_umis_emis_ins_name'] = '-'; // Default value if none found
                                                    $value['std_umis_emis_cls_deg'] = '-'; // Default value if none found
                                                }

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
												$table_data .= "<td style = 'text-align : left'>" . $value['std_app_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_umis_emis_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_umis_emis_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_umis_emis_ins_name'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['std_umis_emis_cls_deg'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $community_no . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $income_no . "</td>";
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