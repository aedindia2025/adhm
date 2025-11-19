<?php
function batch_no($academic_year)
{
    // $date = date("Y");
    // $st_date = substr($date, 4);
    
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

     $acmc_year = academic_year($acc_year)[0]['amc_year'];
    $a = str_split($acmc_year);
    
     $splt_acc_yr = $a[0].$a[1].$a[2].$a[3];
     $hostel_id = '65584660e85as2403119'; 
     $hos_id = substr($hostel_id,);
    
    


    // echo "SELECT * FROM apply_application_form where application_no LIKE 'APN%' order by id desc";echo "<br>";
    $stmt = $conn->query("SELECT max(batch_no) as batch_no FROM batch_creation where is_delete = '0' and hostel_name = '".$hostel_id."' order by id desc");
    // $bill = $stmt->fetch();
    // $res_array = $bill['id'];
    // $result = $res_array + 1;


    // if($res1=$stmt->fetch($stmt))
    if ($res1 = $stmt->fetch()) {
        if($res1['batch_no'] != ''){

        
        $pur_array = explode("-",$res1['batch_no']);
       

        //  echo $pur_array[1];
      
            $booking_no  = $pur_array[1];
        }
        // else{
        //     $booking_no  = '';
        // }
       
    }
    //  $booking_nos = 'APN-' . $splt_acc_yr .'-' . ''. $splt_dis .'-' .'' .$splt_zone.'-' .'' .$splt_host.'-' .'-0001';
    if ($booking_no == ''){
        // echo "ff";
        $booking_nos = 'BAT-'.'0001';
    }
    // else if ($year != date("Y")){
    //     $booking_nos = 'APN-'.date('Y'). $splt_acc_yr . ''.$splt_dis.'' .$splt_zone.'' .$splt_host.'-0001';
    // }
    else {
        $booking_no += 1;
        
    $booking_nos = 'BAT-'.str_pad($booking_no, 4, '0', STR_PAD_LEFT);
    }

    return $booking_nos;
}