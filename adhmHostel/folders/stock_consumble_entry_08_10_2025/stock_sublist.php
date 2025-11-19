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
                                                                                <input type="hidden" id="stock_id" name="stock_id" class="form-control" placeholder=" " value='<?php echo stock_id();?>'>
                                                                                
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
 
         if ($_POST['search']['value']) {
            $where .= " AND feedback LIKE '".mysql_like($_POST['search']['value'])."' ";
         }
         
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
 $tot_qty += $value['qty'];
 $tot_amount += $value['amount'];
                 $btn_update         ='<i class="uil uil-pen" onclick="get_records('.$id.')"></i>';
                 $btn_delete         = '<i class="uil uil-trash" onclick="get_delete('.$id.')"></i>';
 
                 if ($value['unique_id'] == "5f97fc3257f2525529") {
                     $btn_update         = "";
                     $btn_delete         = "";
                 } 
 
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
<?php } }?>
 <tr>
 <td></td>
                 <td></td>
                 <td>Total Qty : <br><?php echo $tot_qty;?></td>
                 <td></td>
                 <td></td>
                 <td>Total Amount :  <br><?php echo $tot_amount;?></td>

                 <tr>
                                                                        
                                                                            
                                                                             </table>
                                                                             </form>
                                                                             <script>
                                                                                 
                                                                                </script>