<!-- Modal with form -->
<?php 

?>
<style>
    #stock_id{
        border: none;
    font-weight: bold;
    }
    </style>


<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
<?php
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
$district_name      = "";
$is_active          = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id  = $_GET["unique_id"];
        // $where      = [
        //     "unique_id" => $unique_id
        // ];
$where = "unique_id = '$unique_id'";
$table_main             = "stock_entry";

        $columns        = [
            // "entry_date",
            // "grievance_id",
            // "grievance_cate",
            // "grievance_description",
            // "student_name",
            // "reg_no",
            // "hostel_name as hostel_name_id",
            // "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
            // // "(select hostel_id from hostel_name where unique_id = $table.hostel_id) as hostel_id",
            // "hostel_id as hostel_id",
            // "hostel_id as hostel_id_val",
            // "grievance_no",
            // "district as district_id_val",
            // "(select district_name from district_name where unique_id = $table.district) as district",
            // "taluk as taluk_id_val",
            // "(select taluk_name from taluk_creation where unique_id = $table.taluk) as taluk",
            // "tahsildar",
            // "file_name",
            // "is_active",
            // "unique_id"
            "' ' as sno",
                "entry_date",
                "stock_id",
                // "(select supplier_name from supplier_name_creation where unique_id = $table.supplier_name) as supplier_name",
                "supplier_name",
                "address",
                // "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
                // "(select user_name from user where unique_id = $table.user_id) as user_id", 
                "bill_no",
                "hostel_name",
                "district",
                "discount",
                "expense",
                "gst",
                "net_total_amount",
                "taluk",
                // "unit",
                "unique_id",

        ];

        $table_details   = [
            $table_main,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);
// print_r($result_values);
        if ($result_values->status) {

            $result_values      = $result_values->data;


            $entry_date      = $result_values[0]["entry_date"];
             $stock_ids      = $result_values[0]["stock_id"];
            $supplier_name      = $result_values[0]["supplier_name"];
            $address      = $result_values[0]["address"];
            $bill_no      = $result_values[0]["bill_no"];
            $reg_no      = $result_values[0]["reg_no"];
            $discount      = $result_values[0]["discount"];
            $expense      = $result_values[0]["expense"];
            $gst      = $result_values[0]["gst"];
            $net_total_amount      = $result_values[0]["net_total_amount"];
            // $hostel_name_id      = $result_values[0]["hostel_name_id"];
            $hostel_name      = $result_values[0]["hostel_name"];
            // $hostel_id_val      = $result_values[0]["hostel_id_val"];
            // $hostel_id      = $result_values[0]["hostel_id"];
            //  $grievance_no      = $result_values[0]["grievance_no"];
            // $district_id_val      = $result_values[0]["district_id_val"];
            $district      = $result_values[0]["district"];
            // $taluk_id_val      = $result_values[0]["taluk_id_val"];
            $taluk      = $result_values[0]["taluk"];
            // $tahsildar      = $result_values[0]["tahsildar"];
            // $file_name      = $result_values[0]["file_name"];
            // $is_active          = $result_values[0]["is_active"];
            $unique_id          = $result_values[0]["unique_id"];

            // if($result_values[0]["stock_id"] == ''){
            //    $stock_ids= stock_id();
            // }else{
            //    $stock_ids= $result_values[0]["stock_id"];
            // }
            
            if($result_values[0]["entry_date"] == ''){
                $entry_date = date('Y-m-d');
            }else{
                $entry_date = $result_values[0]["entry_date"];
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


$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select District Name",$district);
$taluk_options = taluk_name_get();
$taluk_name_options = select_option($taluk_options, "Select Zone",$taluk);
$hostel_options = hostel_name();
$hostel_name_options = select_option($hostel_options, "Select Hostel", $hostel_name);
$supplier_name_options = supplier_name_creation();
$supplier_name_options = select_option($supplier_name_options, "Select supplier Name", $supplier_name);
$product_type_options = product_type_name();
$product_type_option = select_option($product_type_options, "Select Item Name",$item_name);

$unit_options = unit_measurement();
$unit_options = select_option($unit_optionss, "Select Item Name",$unit);
// print_r($taluk_name_option);

function stock_id()
{
    $date = date("Y");
    $st_date = substr($date, 2);
    $month = date("m");
    $datee = $st_date . $month;

    $servername = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $database_name = "adi_dravidar";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch (PDOException $e) {
        // echo "Connection failed: " . $e->getMessage();
    }
    $acc_year = $date;
    $a = str_split($acc_year);
     $splt_acc_yr = $a[2].$a[3];

$stmt = $conn->query("SELECT * FROM stock_entry where stock_id LIKE 'STK%' order by id desc");

if ($res1 = $stmt->fetch()) {
    $pur_array = explode('-', $res1['stock_id']);
   


    $year1 = $pur_array[0];
    $year2 = substr($year1, 0, 2);
    $year = '20' . $year2;
    // echo $pur_array[0];
    // echo "0<br>";
    // echo $pur_array[1];
    // echo "<br>";
    // echo $pur_array[2];
    // echo "<br>";
    // echo $pur_array[3];
    // echo "<br>";
    // echo $pur_array[4];
    // echo "<br>";
            $booking_no  = substr($pur_array[1],6, 4);

       
}

if ($booking_no == ''){
    $booking_nos =  'STK-' . $splt_acc_yr . '/'. $month .'/'.   '0001';
   
}

else {
   
     $booking_no += 1;
$booking_nos =  'STK-' . $splt_acc_yr .  '/'.$month.'/' . str_pad($booking_no, 4, '0', STR_PAD_LEFT);

}

// echo $booking_nos;
return $booking_nos;

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
                                  
                                    <h4 class="page-title">Stock Inward Entry</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                       
                    <div class="row">
                            <div class="col-12">
                                
                                        
                                 <div class="row">
                                
                          <div class="">
                                <div class="card">
                                    <div class="card-body">
                                    <!-- <form class="was-validated" autocomplete="off"> -->
                                    <div class="row mb-3">
									
									 
                                    <div class="col-3">
                                    <!-- <label for="simpleinput" class="form-label">Product Type</label>
                                    <select name="cars" id="cars" class="form-control">
                                        <option value="select Product Type">Select Product Type</option>
                                        <option value="Salt">Salt</option>
                                          <option value="Sugar">Sugar</option>
                                          <option value="Oil">Oil</option>
                                          <option value="Rice">Rice</option>
                                        </select>  -->
                                        <label for="simpleinput" class="form-label">Supplier Name</label> 
                                        </div>
                                        <div class="col-3">
                                        <select id="supplier_name" name="supplier_name" class="form-control" placeholder="">
                                                        <?php echo  $supplier_name_options; ?>
                                                    </select>
                                        <!-- <input type="text" id="supplier_name" name="supplier_name" class="form-control" placeholder=" " value='<?php echo $supplier_name;?>'> -->
                                    </div>
                                    
                                    <div class="col-md-3 fm">
                                   
                                   <label for="simpleinput" class="form-label">Stock Inward Entry No</label> 
                                   </div>
                                   <div class="col-3">
                                   <form  method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                   <input type="text" id="stock_id" name="stock_id" class="form-control"  value='<?php if($stock_ids ==''){ echo stock_id();}else{echo $stock_ids;};?>'>
</form>
                               </div>
                                     
                                    </div>
                                    <div class="row mb-3">
                                    <div class="col-md-3 fm">
                                    
                                        <label for="simpleinput" class="form-label">Address</label>
                                        </div>
                                        <div class="col-3">
                                            <textarea class="form-control" id="address" name="address" value="<?php echo $address;?>"><?php echo $address;?></textarea>
                                    <!-- <input type="text" id="address" name="address" value="Erode" class="form-control" placeholder=" "> -->
                                    </div>
                                    <div class="col-md-3 fm">
                                    <label for="simpleinput" class="form-label">Date:</label>
                                    </div>
                                        <div class="col-3">
                                    <input type="date" id="entry_date" name="entry_date" class="form-control" value="<?php echo $entry_date;?>">
                                    
                                    </div>
                          
									  
                                    
</div>
<div class="row">
<div class="col-md-3 fm">
                                    <!-- <label for="simpleinput" class="form-label">Product Type</label>
                                    <select name="cars" id="cars" class="form-control">
                                        <option value="select Product Type">Select Product Type</option>
                                        <option value="Salt">Salt</option>
                                          <option value="Sugar">Sugar</option>
                                          <option value="Oil">Oil</option>
                                          <option value="Rice">Rice</option>
                                        </select>  -->
                                        <label for="simpleinput" class="form-label">Supplier Bill No</label> 
                                        </div>
                                        <div class="col-3">
                                        <input type="text" id="bill_no" name="bill_no" class="form-control" placeholder=" " value='<?php echo $bill_no;?>'>
                                        <input type="hidden" id="main_unique_id" name="main_unique_id" class="form-control" placeholder=" " value='<?php echo $unique_id;?>'>
                                    </div>
                                    <div class="col-md-3 fm">
                                        
                                            <label for="simpleinput" class="form-label">District</label> 
                                    </div>
                                            <div class="col-3">
                                        <select id="district" name="district" class="form-control" placeholder="" onchange='get_zone_name(this.value);'>
                                                            <?php echo $district_name_options; ?>
                                                        </select>
                                            </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        
                                    <div class="col-md-3 fm">
                                     
                                        <label for="simpleinput" class="form-label">Taluk</label>
                                        </div>
                                        <div class="col-3">
                                        <select id="taluk" name="taluk" class="form-control" placeholder="" onchange='get_hostel_name(this.value);'>
                                                    <?php echo $taluk_name_options; ?>
                                                    </select>
                                        </div>
                                        <div class="col-md-3 fm">
                                     
                                        <label for="simpleinput" class="form-label">Hostel Name</label>
                                        </div>
                                        <div class="col-3">
                                        <select id="hostel_name" name="hostel_name" class="form-control" placeholder="" value='<?php echo $hostel_name ?>'>
                                                    <?php echo $hostel_name_options; ?>
                                                    </select>
                                            </div>
                                    <br>
</div>
                                    <!-- <?php include 'stock_sublist.php';?> -->
                                    <div class="table-responsive mb-4">
                                                        <div id="product_details_datatable_wrapper"
                                                            class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-6"></div>
                                                                <div class="col-sm-12 col-md-6"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                <form class="was-validated" autocomplete="off" >
                                   
                                                                    <table id="document_upload_sub_datatable"
                                                                        class="table table-hover table-bordered align-middle mb-0 dataTable no-footer"
                                                                        width="100%" style="width: 100%;">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th class="sorting_disabled text-center"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    S.NO </th>
                                                                                <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Item Name</th>
                                                                                    <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Qty</th>
                                                                                
                                                                                <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Unit</th>
                                                                                <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Rate </th>
                                                                                    <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Amount </th>
                                                                                    <th scope="col" class="sorting_disabled"
                                                                                    rowspan="1" colspan="1"
                                                                                    style="width: 0px;">
                                                                                    Action </th>
                                                                                    
                                                                           
                                                                          
                                                                                
                                                                                
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td>#</td>
                                                                                <td><select  class="form-control" name="item_name" id="item_name" onchange="get_unit_name(this.value)"><?php  echo $product_type_option;  ?></td>
                                                                                <td><input type="text" class="form-control" name="qty" id="qty" onkeyup="get_total()"></td>
                                                                                <td><select name="unit" id="unit"  class="form-control"><?php echo $unit_options;?></select></td>
                                                                                <td><input type="text" class="form-control" name="rate" id="rate" onkeyup="get_total()"></td>
                                                                                <td><input type="text" class="form-control" name="amount" id="amount">
                                                                                <input type="hidden" class="form-control" name="unique_id" id="unique_id">
                                                                                <!-- <input type="hidden" id="stock_id" name="stock_id" class="form-control" placeholder=" " value='<?php echo stock_id();?>'> -->
                                                                                
                                   <input type="hidden" id="stock_id" name="stock_id" class="form-control"  value='<?php if($stock_ids ==''){ echo stock_id();}else{echo $stock_ids;};?>'>

                                                                                
                                                                            </td>


                                                                                <td><button type="button" class="btn btn-primary" onclick="save_data()" id="btn">Add</button></td>
</tr>   
                                                                        </thead>
                                                                        <?php
$page = $_SERVER['PHP_SELF'];
$sec = "2";
?>
                                                                        
         <?php
if($stock_ids == ''){
    $stock_id = stock_id();
}else{
     $stock_id = $stock_ids;
}
          
        
         $table             = "stock_entry_sub";
         $columns        = [
             "@a:=@a+1 s_no",
             "(select product_type From product_type where unique_id=$table.item_name) as item_name",
             "qty",
             "(select unit_measurement as unit From unit_measurement where unique_id=$table.unit) as unit",
            //  "unit",
             "rate",
             "amount",
             "unique_id",
             "id",
             // "'' as tot_qty",
             // "sum(amount) as tot_amount",
 
         ];
         $table_details  = [
             $table,
             $columns
         ];
         $where          = "is_delete = 0 and stock_id='$stock_id'";
         $order_by       = "";
 
        //  if ($_POST['search']['value']) {
        //     $where .= " AND feedback LIKE '".mysql_like($_POST['search']['value'])."' ";
        //  }
         
         // Datatable Searching
         $search         = datatable_searching($search,$columns);
 
         if ($search) {
             if ($where) {
                 $where .= " AND ";
             }
 
             $where .= $search;
         }
 
         $sql_function   = "SQL_CALC_FOUND_ROWS";
 
         $result         = $pdo->select($table_details,$where);
        //  print_r($result);  
         $total_records  = total_records();
 
         if ($result->status) {
 
             $res_array      = $result->data;
 $i=1;
             foreach ($res_array as $key => $value) {
                 // $value['feedback'] = disname($value['feedback']);
                 // $value['description'] = disname($value['description']);
                 // $value['is_active'] = is_active_show($value['is_active']);
                 $id = $value['id'];
 $unique_id = $value['unique_id'];

 
                 $btn_update         ='<i class="uil uil-pen" onclick="get_records('.$id.')"></i>';
                 $btn_delete         = '<i class="uil uil-trash" onclick="get_delete('.$id.')"></i>';
 
                 if ($value['unique_id'] == "5f97fc3257f2525529") {
                     $btn_update         = "";
                     $btn_delete         = "";
                 } 
  $qty += $value['qty'];
 $amount += $value['amount'];
                 $value['unique_id'] = $btn_update . $btn_delete;
                 // $value['tot_qty'] = '';
                //  $data[]             = array_values($value);
 
             
             
         ?>


<tbody >
<td><?php echo $i++;?></td>
<td><?php echo $value['item_name'];?></td>
<td><?php echo $value['qty'];?></td>
<td><?php echo $value['unit'];?></td>
<td><?php echo $value['rate'];?></td>
<td><?php echo $value['amount'];?></td>
<td><?php echo $value['unique_id'];?></td>
                                                                        
</tbody>
<?php } 
 $tot_qty = $qty;
 $tot_amount = $amount;
}?>
<?php
 
?>
 <tr>
 <td></td>
                 <td></td>
                 <td id="totalqty">Total Qty : <br><?php echo $tot_qty;?></td>
                 <td></td>
                 <td></td>
                 <td id="totalamount">Total Amount :  <br><?php echo $tot_amount;?></td>

                 <tr>
                                                                        
                                                                            
                                                                             </table>
                                                                             </form>
                                    
                                                          <div class="row" style="
    margin-left: 54%;
">
                                                            <div class="col-6">
                                                            <input type="hidden" name="total_amount" id="total_amount" value='<?php echo $tot_amount;?>'>
                                                          <label>Discount</label>
</div>
<div class="col-6">
                                                          <input type="text" name="discount" id="discount" value='<?php echo $discount;?>' onmouseout="getdiscountamount()">
                                                          <input type="hidden" name="discount_amount" id="discount_amount" >
                                                          
                                                          </div><br><br>

                                                          <div class="col-4">
                                                          <label>GST%</label>
</div>
<div class="col-2">
<input type="text" name="gst" id="gst" value='<?php echo $gst;?>' onmouseout="getgstamount()">
</div>

<div class="col-6">
                                                          <input type="text" name="gst_val" id="gst_val" value="">
                                                          <input type="hidden" name="gst_amount" id="gst_amount" >
                                                          
                                                          </div>

                                                          <br><br>
                                                          
                                                          <div class="col-6">
                                                          <label>Other Expense</label>
</div>
<div class="col-6">
                                                          <input type="text" name="expense" id="expense" value='<?php echo $expense;?>'  onchange="getotherexpamount()">
                                                          </div>
                                                          <br><br>
                                                          <hr>
                                                          
                                                          <div class="col-6">
                                                          <label>Net Amount:</label>
</div>
<div class="col-6">
                                                          <p id="net_amt"><?php echo $net_total_amount;?></p>
                                                          <input type="hidden" name="net_total_amount" id="net_total_amount" value='<?php echo $net_total_amount;?>'>
                                                          </div>



</div>
<div class="row" style="
    margin-top: -46px;
">
<div class="col-6">
                                                          <label>Bill Attachment Upload</label><br><br>

                                                          <input type="file" name="test_file" id="test_file">
                                                          </div>
</div>
                                                                    <div id="product_details_datatable_processing"
                                                                        class="dataTables_processing card"
                                                                        style="display: none;">
                                                                        <!-- <i
                                                                    class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                                    <span
                                                                    class="sr-only">Loading...</span> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12 col-md-5"></div>
                                                                <div class="col-sm-12 col-md-7"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                           
                                            </div>



















                                      <div class="btns">
                                       <a href="index.php?file=stock_entry/list"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2">Cancel</button></a>
                                       <?php if($unique_id == ''){?>
                                   <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="stock_entry_cu('')">Save</button>
                                   <?php } if($unique_id != ''){?>
                                   <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="stock_entry_cu('')">Update</button>
                                   <?php }?>
                                   </div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                                
                                </div>  

                            
        
                   
                               </div> 
                                     </div> 
                         
                        
                        
        </div>
        </div>
        </div>
        <script>
             $(document).ready(function () {
var table_id = 'document_upload_sub_datatable';
                // sub_list_datatable(table_id,form_name,action);

    });
            
            function get_zone_name() {
		// alert("hii");
		var district = document.getElementById('district').value;
		// alert(district);
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		if (district ) {
			var data = {
				"district": district,
				"action": "get_zone_name"
			}
	
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				success: function (data) {
					// alert(data);
					if (data) {
						$("#taluk").html(data);
					}
				}
			});
		}
	}
	
	
	function get_hostel_name() {
		//  alert("hii");
		var taluk = document.getElementById('taluk').value;
		// alert(taluk);
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		if (taluk) {
			var data = {
				"taluk": taluk,
				"action": "get_hostel_name"
			}
	
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				success: function (data) {
					//  alert(data);
					if (data) {
						$("#hostel_name").html(data);
					}
				}
			});
		}
	}
	
	function get_unit_name() {
		// alert("hii");
		var item_name = document.getElementById('item_name').value;
		// alert(item_name);
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		if (item_name) {
			var data = {
				"item_name": item_name,
				"action": "get_unit_name"
			}
	
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: data,
				success: function (data) {
					// alert(data);
					if (data) {
						$("#unit").html(data);
					}
				}
			});
		}
	}
    function save_data() { // au = add,update
        $('#totalqty').empty();
                    $('#totalamount').empty();
	var internet_status = is_online();
    var unique_id = $("#unique_id").val();

	var is_form = form_validity_check("was-validated");

    if (is_form) {

        var data 	 = $(".was-validated").serialize();
        data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = '';

		

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			// cache: false,
			// contentType: false,
			// processData: false,
			method: 'POST',
			
			
			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				// if (!status) {
				// 	$(".status_sub_add_update_btn").text("Error");
				// 	console.log(error);
				// } else {
					if (msg == "already") {
				// 		//form_reset("periodic_sub_form");
				// 		$("#status_option").val(null).trigger('change');
				// 		$("#status_description").val("");
						sweetalert(msg);

					}
                    if (msg == "create") {
                       
                        document.getElementById('item_name').value='';
                document.getElementById('qty').value='';

                document.getElementById('unit').value='';

                document.getElementById('rate').value='';

                document.getElementById('amount').value='';

                document.getElementById('unique_id').value='';
                    sweetalert(msg);
                    location.reload();
                    // $('#net_amt').empty();
                    // location.reload();
                    // sub_list_datatable("document_upload_sub_datatable");
                    // $('#tot_qty').append(obj.tot_qty);
                    // $('#tot_amount').append(obj.tot_amount);

                    }
                    if (msg == "update") {
                        
                        document.getElementById('item_name').value='';
                document.getElementById('qty').value='';

                document.getElementById('unit').value='';

                document.getElementById('rate').value='';

                document.getElementById('amount').value='';

                document.getElementById('unique_id').value='';
                $('#btn').empty();
                $('#btn').append('Add');
                    sweetalert(msg);
                    location.reload();

                    
                    // sub_list_datatable("document_upload_sub_datatable");
                    // $('#tot_qty').append(obj.tot_qty);
                    // $('#tot_amount').append(obj.tot_amount);
                    
                    }
				// 	$(".status_sub_add_update_btn").removeAttr("disabled", "disabled");
				// 	if (unique_id && msg == "already") {
				// 		$(".status_sub_add_update_btn").text("Update");
				// 		sweetalert("custom", '', '', 'The Complaint has already completed');
				// 	} else if (unique_id && msg == "alreadys") {
				// 		$(".status_sub_add_update_btn").text("Update");
				// 		sweetalert("custom", '', '', 'The Enquiry has already completed');
				// 	}
					
				// 	else {
				// 		$(".status_sub_add_update_btn").text("Add");
				// 		$(".status_sub_add_update_btn").attr("onclick", "status_sub_add_update('')");
				// 		if (msg == 'already') {

				// 			sweetalert("custom", '', '', 'The Complaint has already completed');
				// 		} else {
				// 			sweetalert(msg, url);
				// 		}

				// 	}
					// Init Datatable
					// sub_list_datatable(table_id,form_name,action);

				}

		// 	},
		// 	error: function (data) {
		// 		alert("Network Error");
			// }
		});


	} else {

		sweetalert("custom", '', '', 'Create Sub Details');

		if (status_option == '') {
			document.getElementById('status_option').focus();
		} else if (status_description == '') {
			document.getElementById('status_description').focus();
		}
	}
}

function sub_list_datatable(table_id = "", form_name = "", action = "") {
// alert("hii");

var stock_id = $("#stock_id").val();

var table = $("#" + table_id);
var data = {
    "stock_id": stock_id,
    // "screen_unique_id": screen_unique_id,
    "action": table_id,
};
var ajax_url = sessionStorage.getItem("folder_crud_link");
var datatable = new DataTable(table, {
    destroy: true,
    "searching": false,
    "paging": false,
    "ordering": false,
    "info": false,
    "ajax": {
        url: ajax_url,
        type: "POST",
        data: data,
        
        // success: function (data) {
            
        //     var obj = JSON.parse(data);
		// 		var msg = obj.msg;
        //         var data = obj.data;
		// 		var status = obj.status;
		// 		var error = obj.error;
        //         alert(data);

        //     // call your function here
        // }

    }
   
    

});
}

function get_records(val){
   
//  var data 	 = val;
      var  data 		= "id=" + val + " &action=updatevalues";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = '';

		

		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			
			method: 'POST',
			
			
			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;
                var data = obj.data;
				var status = obj.status;
				var error = obj.error;
                // alert(data.item_name);
                document.getElementById('item_name').value=data.item_name;
                document.getElementById('qty').value=data.qty;

                document.getElementById('unit').value=data.unit;

                document.getElementById('rate').value=data.rate;

                document.getElementById('amount').value=data.amount;

                document.getElementById('unique_id').value=data.unique_id;
                
                $('#btn').empty();
                $('#btn').append('update');

                var item_name = document.getElementById('item_name').value;
                // alert(item_name);
                if(item_name){
                    get_unit_name();
                }



            }
        });


}

function get_delete(val){
   
   //  var data 	 = val;
         var  data 		= "id=" + val + " &action=sub_delete";
   
           var ajax_url = sessionStorage.getItem("folder_crud_link");
           var url = '';
   
           
   
           $.ajax({
               type: "POST",
               url: ajax_url,
               data: data,
               
               method: 'POST',
               
               
               success: function (data) {
   
                   var obj = JSON.parse(data);
                   var msg = obj.msg;
                   var data = obj.data;
                   var status = obj.status;
                   var error = obj.error;
                  
                   if (msg == "success_delete") {
                    sweetalert(msg);
                    location.reload();
                    // sub_list_datatable("document_upload_sub_datatable");
                    }
   
   
   
   
               }
           });
   
   
   }
            function getdiscountamount(){
                
                var actual_price = document.getElementById('total_amount').value;
                // alert(total_amount);
                var discount = document.getElementById('discount').value;
                selling_price = actual_price - (actual_price * (discount / 100));
                document.getElementById('discount_amount').value = selling_price;
            }
            function getgstamount(){
                $('#net_amt').empty();
                var gst_total = '';
                var discount_amount = document.getElementById('discount_amount').value;
               
                var gst = document.getElementById('gst').value;
// alert(gst);
                const tax = discount_amount * (gst / 100);
                // alert(tax);
             gst_total = parseInt(discount_amount) + parseInt(tax);
                // alert(gst_total);
                var gst_val = discount_amount * (gst / 100);
                document.getElementById('gst_val').value = gst_val;

                document.getElementById('gst_amount').value = gst_total;
                document.getElementById('net_total_amount').value = gst_total;
                $('#net_amt').append(gst_total);
            }
            function getotherexpamount(){
                $('#net_amt').empty();
                var expense = document.getElementById('expense').value;
               
                var gst_amount = document.getElementById('gst_amount').value;
// alert(expense);
             var other_expense = parseInt(gst_amount) + parseInt(expense);
            //  alert(other_expense);
                document.getElementById('net_total_amount').value = other_expense;
                $('#net_amt').append(other_expense);
            }
            
            $(document).ready(function () {
        var table_id 	= "document_upload_sub_datatable";
        // go();
        // sub_list_datatable(table_id,form_name,action);
    });
           
            function get_total(){
                var qty = document.getElementById('qty').value;
                var rate = document.getElementById('rate').value;
                var amount = qty*rate;
                document.getElementById('amount').value=amount;
            }

            // save_data()
           
   function stock_entry_cu($unique_id=""){
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    

	var entry_date = document.getElementById('entry_date').value;
    var supplier_name = document.getElementById('supplier_name').value;
	var address = document.getElementById('address').value;
    var bill_no = document.getElementById('bill_no').value;
	var hostel_name = document.getElementById('hostel_name').value;

    var stock_id = document.getElementById('stock_id').value;
	var discount = document.getElementById('discount').value;
    var expense = document.getElementById('expense').value;
    var gst = document.getElementById('gst').value;
	var net_total_amount = document.getElementById('net_total_amount').value;
    var district = document.getElementById('district').value;
	var taluk = document.getElementById('taluk').value;

   
	
    var unique_ids = document.getElementById('unique_id').value;
    var main_unique_id = document.getElementById('main_unique_id').value;

    if(main_unique_id == ''){
        unique_id = unique_ids;
    }else{
        unique_id = main_unique_id;
    }
	
   var data = new FormData();
   var image_s = document.getElementById('test_file');
   
    if (image_s != '') {
		for (var i = 0; i < image_s.files.length; i++) {
			data.append("test_file[]", document.getElementById('test_file').files[i]);
		}
	} else{
        data.append("test_file", '');
    }
	

	

	


    var actions = "main_createupdate";

    
    
	data.append("entry_date", entry_date);
	data.append("supplier_name", supplier_name);
	data.append("address", address);
	data.append("bill_no", bill_no);
	data.append("hostel_name", hostel_name);

    data.append("stock_id", stock_id);
	data.append("discount", discount);
    data.append("expense", expense);
    data.append("gst", gst);
	

	data.append("net_total_amount", net_total_amount);
    data.append("district", district);
	data.append("taluk", taluk);
    data.append("unique_id", unique_id);
    
	data.append("action", actions);

    // var is_form = form_validity_check("was-validated");
    // if (is_form) {

    //     var data 	 = $(".was-validated").serialize();
    //     data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
			url: ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			// beforeSend 	: function() {
			// 	$(".createupdate_btn").attr("disabled","disabled");
			// 	$(".createupdate_btn").text("Loading...");
			// },
			success		: function(data) {
				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;

				if (!status) {
					url 	= '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
				} else {
					if (msg=="already") {
						// Button Change Attribute
						url 		= '';

						$(".createupdate_btn").removeAttr("disabled","disabled");
						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
				}

				sweetalert(msg,url);
			},
			error 		: function(data) {
				alert("Network Error");
			}
		});
}

//    }

            </script>