    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->
    <style>
		<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit;
}

// Step 2: Secure File Access (optional)
// Implement authorization checks here if necessary

// Step 3: Fetch Unique ID
if(isset($_GET['unique_id'])) {
    $unique_id = $_GET['unique_id'];
    // Process the unique ID as needed
    // For example, retrieve the corresponding PDF file and display it
    // Make sure to implement appropriate security checks here
} else {
    // Handle case where unique ID is not provided
    echo "Error: Unique ID is missing.";
}
?>

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

	

    if (isset($_GET["unique_id"])) {
        if (!empty($_GET["unique_id"])) {
    
            $unique_id = $_GET["unique_id"];
            $where = [
                "screen_unique_id" => $unique_id
            ];
    
            $table = "stock_entry";
    
            $columns = [
                "screen_unique_id",
                "stock_id",
                "date_format(created,'%Y-%m-%d') as entry_date",
				"(select hostel_name from hostel_name where hostel_name.unique_id = stock_entry.hostel_name) as hostel_name",
                "(select hostel_id from hostel_name where hostel_name.unique_id = stock_entry.hostel_name) as hostel_id",
            ];
    
            $table_details = [
                $table,
                $columns
            ];
    
            $result_values = $pdo->select($table_details, $where);
            // print_r($result);
    
            if ($result_values->status) {
    
                $result_values = $result_values->data;
    
                $screen_unique_id = $result_values[0]["screen_unique_id"];
                $stock_id = $result_values[0]["stock_id"];
                $entry_date = $result_values[0]["entry_date"];
				$hostel_name = $result_values[0]["hostel_name"];
                $hostel_id = $result_values[0]["hostel_id"];
              
    
    
    
                $btn_text = "Update";
                $btn_action = "update";
            } else {
                $btn_text = "Error";
                $btn_action = "error";
                $is_btn_disable = "disabled='disabled'";
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
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												
													<div class="col-sm-12">
													<p class="font-12">Hostel District   :  &nbsp;<strong><?=$_SESSION["district_name"];?></strong></p>
													</div>

													<div class="col-sm-4">
													<p class="font-12">Hostel ID   :  &nbsp;<strong><?=$hostel_id;?></strong></p>
													</div>

                                                    <div class="col-sm-4">
													<p class="font-12">Hostel District   :  &nbsp;<strong><?=$hostel_name;?></strong></p>
													</div>

													<div class="col-sm-12">
													<p class="font-12">Date   :  &nbsp;<strong><?=$entry_date;?></strong></p>
													</div>
													
													<div class="col-sm-12">
													<p class="font-12">  Stock ID   :  <strong><?=$stock_id;?></strong></p>
													</div>
													<!--<div class="col-sm-12">
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
    										<!-- <th width="10%" colspan="1" class="blankCell">S</th> -->
    										<!-- <th width="10%" colspan="1" class="blankCell">Stock ID</th> -->
    										<th width="10%" colspan="1" class="blankCell">Category</th>
    										<th width="10%" colspan="1" class="blankCell">Amount</th>
    						
    									</tr>
    								</thead>
    								<?php
									$start = 0;
									$table_main = "stock_entry_sub";

$where_list = "screen_unique_id = '".$screen_unique_id."' and is_delete = 0 group by category_name";
									

									$columns_list    = [
										"@a:=@a+1 s_no",
										// "stock_id",
                                        "(select product_category from product_category where product_category.unique_id = stock_entry_sub.category_name) as category_name",
                                       
                                        "sum(amount) as amount"
										

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
// echo "d";

												$table_data .= "<tr>";

												$table_data .= "<td style = 'text-align : right'>" . $value['s_no'] . "</td>";
												$table_data .= "<td style = 'text-align : left'>" . $value['category_name'] . "</td>";
												$table_data .= "<td style = 'text-align : right'>" . $value['amount'] . "</td>";
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