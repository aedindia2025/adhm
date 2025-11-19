    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->
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
	// $p1_unique_id = $_GET['unique_id'];

	if ($_GET["unique_id"]) {

        // echo $_GET["unique_id"];
		$unique_id = $_GET["unique_id"];
        

        $where1 = [
            "unique_id" => $unique_id
        ];

        $table1 = "std_app_p1";

        $columns1 = [
            "(select hostel_name from hostel_name where hostel_name.unique_id = std_app_p1.hostel_name) as hostel_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = std_app_p1.hostel_taluk) as hostel_taluk",
            "(select district_name from district_name where  district_name.unique_id = std_app_p1.hostel_district) as hostel_district",
            "(select amc_year from academic_year_creation where academic_year_creation.unique_id = std_app_p1.academic_year) as academic_year",
            "batch_cr_date",
            "std_app_no",
            "std_name",
            "std_mobile_no",
            "created"
            // "count(id) as count"
            //  
        ];

        $table_details1 = [
            $table1,
            $columns1
        ];

        $result_values1 = $pdo->select($table_details1, $where1);
        // print_r($result_values1);

        if ($result_values1->status) {

            $result_values1 = $result_values1->data;

            $hostel_name = $result_values1[0]["hostel_name"];
            $hostel_taluk = $result_values1[0]["hostel_taluk"];
            $hostel_district = $result_values1[0]["hostel_district"];
            $academic_year = $result_values1[0]["academic_year"];
            $batch_cr_date = $result_values1[0]["batch_cr_date"];
            $std_app_no = $result_values1[0]["std_app_no"];
            $std_name = $result_values1[0]["std_name"];
            $std_mobile_no = $result_values1[0]["std_mobile_no"];
            $created = $result_values1[0]["created"];
           
        }

        $where2 = [
            "p1_unique_id" => $unique_id
        ];

		$table2      =  "std_app_p2";

    $columns    = [
        "hostel_type",
        "priority",
        "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = std_app_p2.hostel_gender_type) as gender_type",
    ];

    $table_details2   = [
        $table2,
        $columns
    ]; 

    $result_values2  = $pdo->select($table_details2,$where2);
    // print_r($result_values2);

    if ($result_values2->status) {

        $result_values2      = $result_values2->data;
        $hostel_type      = $result_values2[0]["hostel_type"];
        $host_type = hostel_type_name($hostel_type)[0]['hostel_type'];
        $priority      = $result_values2[0]["priority"];
        $gender_type      = $result_values2[0]["gender_type"];
        switch($priority){
            case 1:
                $priority = '1st Priority';
                break;
                case 2:
                    $priority = '2nd Priority';
                    break;
                    case 3:
                        $priority = '3rd Priority';
                        break;    
        }
		
    }

	$where3 = [
        "p1_unique_id" => $unique_id
    ];

	$table3      =  "std_app_p3";

    $columns    = [
        "std_img"
    ];

    $table_details3   = [
        $table3,
        $columns
    ]; 

    $result_values3  = $pdo->select($table_details3,$where3);

    if ($result_values3->status) {

        $result_values3      = $result_values3->data;
        $std_img      = $result_values3[0]["std_img"];
    }

    $where4     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table4      =  "std_app_p4";

    $columns    = [
        "is_renewal",
        "emis_no",
        "umis_no",
        "std_dob",
        "age",
        "blood_group",
        "gender",
        "email_id",
        "religion",
        "mother_tongue",
        "community_cer_no",
        "std_caste",
        "std_sub_caste",
        "contact_no_type",
        "contact_no",
        "income_cer_no",
        "annual_income",
        "remarks",
        "physically_challenge",
        "phy_category",
        "phy_percentage",
        "phy_id_no",
        "srilankan_refugees",
        "orphan",
        "single_parent",
        "first_graduate",
        "graduate_no",
        
    ];

    $table_details4   = [
        $table4,
        $columns
    ]; 

    $result_values4  = $pdo->select($table_details4,$where4);

    if ($result_values4->status) {

        $result_values4      = $result_values4->data;
        $is_renewal      = $result_values4[0]["is_renewal"];
        $emis_no      = $result_values4[0]["emis_no"];
        $umis_no      = $result_values4[0]["umis_no"];
        $std_dob      = $result_values4[0]["std_dob"];
        $age      = $result_values4[0]["age"];
        $blood_group	      = $result_values4[0]["blood_group"];
        $gender      = $result_values4[0]["gender"];
        $email_id      = $result_values4[0]["email_id"];
        $religion      = $result_values4[0]["religion"];
        $mother_tongue      = $result_values4[0]["mother_tongue"];
        $community_cer_no      = $result_values4[0]["community_cer_no"];
        $std_caste      = $result_values4[0]["std_caste"];
        $std_sub_caste      = $result_values4[0]["std_sub_caste"];
        $contact_no_type      = $result_values4[0]["contact_no_type"];
        $contact_no      = $result_values4[0]["contact_no"];
        $income_cer_no      = $result_values4[0]["income_cer_no"];
        $annual_income      = $result_values4[0]["annual_income"];
        $remarks      = $result_values4[0]["remarks"];
        $physically_challenge      = $result_values4[0]["physically_challenge"];
        $phy_category      = $result_values4[0]["phy_category"];
        $phy_percentage      = $result_values4[0]["phy_percentage"];
        $phy_id_no      = $result_values4[0]["phy_id_no"];
        $srilankan_refugees      = $result_values4[0]["srilankan_refugees"];
        $orphan      = $result_values4[0]["orphan"];
        $single_parent      = $result_values4[0]["single_parent"];
        $first_graduate      = $result_values4[0]["first_graduate"];
        $graduate_no      = $result_values4[0]["graduate_no"];

		switch($is_renewal){
			case 1:
				$is_renewal = 'Fresh';
				break;
			case 2:
				$is_renewal = 'Renewal';
				break;
		}
        switch($gender){
			case 1:
				$gender = 'Male';
				break;
			case 2:
				$gender = 'Female';
				break;
                case 3:
                    $gender = 'Transgender';
                    break;
		}
    }

    $where5     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table5      =  "std_app_p5";

    $columns    = [
        "std_school_name",
        "std_class",
        "std_group",
        "std_medium",
        "scl_std_scholarship_no",
        "std_stream",
        "std_university",
        "std_college_name",
        "std_degree",
        "std_subject",
        "std_studying_year",
        "clg_std_medium",
        "clg_std_scholarship_no	",
        
    ];
    $table_details5   = [
        $table5,
        $columns
    ]; 

    $result_values5  = $pdo->select($table_details5,$where5);
// print_r($result_values5);
    if ($result_values5->status) {

        $result_values5      = $result_values5->data;
        $std_school_name      = $result_values5[0]["std_school_name"];
        $std_class      = $result_values5[0]["std_class"];
        $std_group      = $result_values5[0]["std_group"];
        $std_medium      = $result_values5[0]["std_medium"];
        $scl_std_scholarship_no      = $result_values5[0]["scl_std_scholarship_no"];
        $std_stream      = $result_values5[0]["std_stream"];
        $std_university      = $result_values5[0]["std_university"];
        $std_college_name      = $result_values5[0]["std_college_name"];
        $std_degree      = $result_values5[0]["std_degree"];
        $std_subject      = $result_values5[0]["std_subject"];
        $std_studying_year      = $result_values5[0]["std_studying_year"];
        $clg_std_medium      = $result_values5[0]["clg_std_medium"];
        $clg_std_scholarship_no      = $result_values5[0]["clg_std_scholarship_no"];


        switch($std_studying_year){
            case 1:
                $std_studying_year = "1st Year";
                break;
                case 2:
                    $std_studying_year = "2nd Year";
                    break;
                    case 3:
                        $std_studying_year = "3rd Year";
                        break;
                        case 4:
                            $std_studying_year = "4th Year";
                            break;
                            case 5:
                                $std_studying_year = "5th Year";
                                break;
            
        }
        
    }

    $where6     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table6      =  "std_app_p6";

    $columns    = [
        "last_std_scl_name",
        "last_scl_district",
        "last_std_class",
        "last_std_group",
        "last_std_medium",
        "scl_std_scholarship_no",
        "last_std_scl_add",
        "last_clg_district",
        "last_std_stream",
        "last_std_university",
        "last_std_college_name",
        "last_std_degree",
        "last_std_subject",
        "last_std_studying_year",
        "last_clg_std_medium",
        "clg_std_scholarship_no",
        "last_clg_address",
        
    ];

    $table_details6   = [
        $table6,
        $columns
    ]; 

    $result_values6  = $pdo->select($table_details6,$where6);

    if ($result_values6->status) {

        $result_values6      = $result_values6->data;
        $last_std_scl_name      = $result_values6[0]["last_std_scl_name"];
        $last_scl_district      = $result_values6[0]["last_scl_district"];
        $last_std_class      = $result_values6[0]["last_std_class"];
        $last_std_group      = $result_values6[0]["last_std_group"];
        $last_std_medium      = $result_values6[0]["last_std_medium"];
        // $scl_std_scholarship_no      = $result_values6[0]["scl_std_scholarship_no"];
        $last_std_scl_add      = $result_values6[0]["last_std_scl_add"];
        $last_clg_district      = $result_values6[0]["last_clg_district"];
        $last_std_stream      = $result_values6[0]["last_std_stream"];
        $last_std_university      = $result_values6[0]["last_std_university"];
        $last_std_college_name      = $result_values6[0]["last_std_college_name"];
        $last_std_degree      = $result_values6[0]["last_std_degree"];
        $last_std_subject      = $result_values6[0]["last_std_subject"];
        $last_std_studying_year      = $result_values6[0]["last_std_studying_year"];
        $last_clg_std_medium      = $result_values6[0]["last_clg_std_medium"];
        // $clg_std_scholarship_no      = $result_values6[0]["clg_std_scholarship_no"];
        $last_clg_address      = $result_values6[0]["last_clg_address"];
        
       
    }

    $where7     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table7      =  "std_app_p7";

    $columns    = [
        "door_no",
        "area_name",
        "landmark",
        "district_name",
        "taluk_name",
        "village_name",
        "pincode",

    ];

    $table_details7   = [
        $table7,
        $columns
    ];

    $result_values7  = $pdo->select($table_details7,$where7);
    if ($result_values7->status) {

        $result_values7      = $result_values7->data;
        $door_no      = $result_values7[0]["door_no"];
        $area_name      = $result_values7[0]["area_name"];
        $landmark      = $result_values7[0]["landmark"];
        $district_name      = $result_values7[0]["district_name"];
        $taluk_name      = $result_values7[0]["taluk_name"];
        $village_name      = $result_values7[0]["village_name"];
        $pincode      = $result_values7[0]["pincode"];
    }

    $where8     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table8      =  "std_app_p8";

    $columns    = [
        "hos_dis_home",
        "hos_dis_insti"
    ];

    $table_details8   = [
        $table8,
        $columns
    ]; 

    $result_values8  = $pdo->select($table_details8,$where8);

    if ($result_values8->status) {

        $result_values8      = $result_values8->data;
        $hos_dis_home      = $result_values8[0]["hos_dis_home"];
        $hos_dis_insti      = $result_values8[0]["hos_dis_insti"];
    }

    $where9     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table9      =  "std_app_p9";

    $columns    = [
        "aadhar_no",
        "ration_card_no"
    ];

    $table_details9   = [
        $table9,
        $columns
    ]; 

    $result_values9  = $pdo->select($table_details9,$where9);

    if ($result_values9->status) {

        $result_values9      = $result_values9->data;
        $aadhar_no           = $result_values9[0]["aadhar_no"];
        $ration_card_no      = $result_values9[0]["ration_card_no"];
    }

    $where10     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table10      =  "std_app_p10";

    $columns    = [
        "bank_name",
        "bank_acc_no",
        "branch_name",
        "ifsc_code",
    ];

    $table_details10   = [
        $table10,
        $columns
    ]; 

    $result_values10  = $pdo->select($table_details10,$where10);

    if ($result_values10->status) {

        $result_values10      = $result_values10->data;
        $bank_name           = $result_values10[0]["bank_name"];
        $bank_acc_no      = $result_values10[0]["bank_acc_no"];
        $branch_name      = $result_values10[0]["branch_name"];
        $ifsc_code      = $result_values10[0]["ifsc_code"];
    }


    $where11     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table11      =  "std_app_p11";

    $columns    = [
        "father_name",
        "father_occuption",
        "father_qualification",
        "father_mob_no",
        "mother_name",
        "mother_occupation",
        "mother_qualification",
        "mother_mob_no",
        "guardian_name",
        "guardian_occuption",
        "guardian_qualification",
        "guardian_mob_no",
    ];

    $table_details11   = [
        $table11,
        $columns
    ]; 

    $result_values11  = $pdo->select($table_details11,$where11);

    if ($result_values11->status) {

        $result_values11      = $result_values11->data;
        $father_name           = $result_values11[0]["father_name"];
        $father_qualification      = $result_values11[0]["father_qualification"];
        $father_occuption      = $result_values11[0]["father_occuption"];
        $father_mob_no      = $result_values11[0]["father_mob_no"];
        $mother_name      = $result_values11[0]["mother_name"];
        $mother_occupation      = $result_values11[0]["mother_occupation"];
        $mother_qualification      = $result_values11[0]["mother_qualification"];
        $mother_mob_no      = $result_values11[0]["mother_mob_no"];
        $guardian_name      = $result_values11[0]["guardian_name"];
        $guardian_occuption      = $result_values11[0]["guardian_occuption"];
        $guardian_qualification      = $result_values11[0]["guardian_qualification"];
        $guardian_mob_no      = $result_values11[0]["guardian_mob_no"];
    }

    $where11     = [
        "p1_unique_id" => $p1_unique_id,
        "is_delete" => '0',
    ];

    $table11      =  "std_app_p12";

    $columns    = [
        "aadhar_file",
        "bonafide_file",
        "bank_passbook_file"
    ];

    $table_details11   = [
        $table11,
        $columns
    ]; 

    $result_values11  = $pdo->select($table_details11,$where11);

    if ($result_values11->status) {

        $result_values11      = $result_values11->data;
        $aadhar_file           = $result_values11[0]["aadhar_file"];
        $bonafide_file      = $result_values11[0]["bonafide_file"];
        $bank_passbook_file      = $result_values11[0]["bank_passbook_file"];

        $aadhar = image_view($aadhar_file);
        $bonafide = image_view($bonafide_file);
        $bank_passbook = image_view($bank_passbook_file);
        
    }

}
    
	?>

<?php 
// include 'header.php' 
?>
<style>

body {
    
    font-family: 'Poppins', sans-serif;
}
.card-body {
    padding: 20px;
}
.vendorListHeading {
    background-color: #f3f3f3;
    color: black;
    -webkit-print-color-adjust: exact;
}
.mt-2.vendorListHeading p {
    margin-bottom: 0px;
    text-align: center;
    padding: 5px;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<div class="card-body">
<div class="clearfix">
                                            <div class=" mb-3 mt-1 text-center vendorListHeading2" >
                                                <img src="../../../assets/images/ad-logo.png" alt="dark logo" height="50">
                                            </div>
                                           
                                        </div>
<div class="row">
                                            <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Application Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
                                            <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-3">
													
													<img src="../../../student_img/<?=$std_img;?>" width="100" height="100">
													</div>
												<div class="col-sm-9">
												<div class="row">
												<div class="col-sm-6">
                                                    <p class="font-12">Student Name:  <strong><?=$std_name;?></strong></p>
													</div>
													<div class="col-sm-6">
													<p class="font-12">Academic Year:  <strong><?=$academic_year;?></strong></p>
													</div>
													
													<div class="col-sm-6">
													<p class="font-12">Mobile Number:  <strong><?=$std_mobile_no;?></strong></p>
													</div>
													<div class="col-sm-6">
													<p class="font-12">Application no:  <strong><?php echo $std_app_no;?></strong></p>
													</div>
													<div class="col-sm-12">
													<p class="font-12">Applied date and time:  <strong><?php echo $created;?></strong></p>
													</div>
													</div>
													</div>
													
													
                                                </div>
                                            </div><!-- end col -->
											</div>
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Hostel Information</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Priority:  <strong><?php echo $priority;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Hostel District:  <strong><?=$hostel_district;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Hostel Taluk:  <strong><?=$hostel_taluk;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Hostel Gender Type:  <strong><?php echo $gender_type; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Hostel Type:  <strong><?=$host_type;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Hostel Name:  <strong><?=$hostel_name;?></strong></p>
													</div>
                                                </div>
                                            </div><!-- end col -->
											</div>
											
												
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading" >
                                                    <p><b> Personal Info</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Fresh or renewal:  <strong><?php echo $is_renewal;?></strong></p>
													</div>
                                                    <?php if($hostel_type == "65f00a259436412348"){?>
													<div class="col-sm-4">
													<p class="font-12">EMIS No:  <strong><?php echo $emis_no;?></strong></p>
													</div>
                                                    <?php }else{ ?>
                                                        <div class="col-sm-4">
													<p class="font-12">UMIS No:  <strong><?php echo $umis_no;?></strong></p>
													</div>
                                                        <?php }?>
													<div class="col-sm-4">
													<p class="font-12">  D.O.B:  <strong><?=$std_dob;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Age:  <strong><?=$age;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Blood Group:  <strong><?=$blood_group;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Gender :  <strong><?=$gender;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Email Id :  <strong><?=$email_id;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Religion  :  <strong><?=$religion;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Mother Tongue :  <strong><?=$mother_tongue;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Community Certificate No :  <strong><?=$community_cer_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Caste :  <strong><?=$std_caste;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Sub Caste :  <strong><?=$std_sub_caste;?></strong></p>
													</div>
													<!-- <div class="col-sm-4">
													<p class="font-12">Contact No Type :  <strong></strong></p>
													</div> -->
													<div class="col-sm-4">
													<p class="font-12"> Parent or Guardian No :  <strong><?=$contact_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Income Certificate No :  <strong><?=$income_cer_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">  Annual Income :  <strong><?=$annual_income;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Remarks :  <strong><?=$remarks;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Physically challenged :  <strong><?=$physically_challenge;?></strong></p>
													</div>
                                                    <?php if($physically_challenge == 'YES'){?>
                                                    <div class="col-sm-4">
													<p class="font-12"> ID Card No :  <strong><?php echo $phy_id_no;?></strong></p>
													</div>
                                                    <div class="col-sm-4">
													<p class="font-12"> Challenged Category :  <strong><?=$phy_category;?></strong></p>
													</div>
                                                    <div class="col-sm-4">
													<p class="font-12"> Challenged Percentage :  <strong><?=$phy_percentage;?></strong></p>
													</div>
                                                    <?php }?>
													<div class="col-sm-4">
													<p class="font-12"> Srilankan Refugees:  <strong><?=$srilankan_refugees;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Orphanages:  <strong><?=$orphan;?></strong></p>
													</div>
                                                    <?php if($orphan == 'NO'){?>
													<div class="col-sm-4">
													<p class="font-12">Single Parent Child:  <strong><?php echo $single_parent;?></strong></p>
													</div>
                                                    <?php }?>
													<div class="col-sm-4">
													<p class="font-12">First Graduate:  <strong><?=$first_graduate;?></strong></p>
													</div>
                                                    <?php if($first_graduate == 'YES'){?>
                                                    <div class="col-sm-4">
													<p class="font-12">Graduate No:  <strong><?=$graduate_no;?></strong></p>
													</div>
                                                    <?php }?>
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Institution Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
                                                <?php if($hostel_type == "65f00a259436412348"){ ?>
                                                <div class="col-sm-4">
                                                    <p class="font-12">School Name :  <strong><?php echo school_name($std_school_name)[0]['school_name'];?></strong></p>
													</div>
													
													<div class="col-sm-4">
													<p class="font-12"> Class :  <strong><?=$std_class?></strong></p>
													</div>
                                                    <?php if($std_class == '11' || $std_class == '12'){?>
													<div class="col-sm-4">
													<p class="font-12">Group :  <strong><?=group_name($std_group)[0]['subject_name'];?></strong></p>
													</div>
                                                    <?php }?>
													
													<div class="col-sm-4">
													<p class="font-12">Medium : <strong><?=medium_type($std_medium)[0]['medium_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Scholarship No:  <strong><?=$scl_std_scholarship_no?></strong></p>
													</div>
                                                </div>
                                                <?php }else{?>

												<div class="col-sm-4">
                                                    <p class="font-12">Stream :  <strong><?php echo stream_type($std_stream)[0]['stream_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">University :  <strong><?php echo university_name($std_university)[0]['university_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> College name:  <strong><?php echo college_name($std_college_name)[0]['college_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Course:  <strong><?php echo course_name($std_degree)[0]['course_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Subject:  <strong><?php echo $std_subject;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Studying year:  <strong><?php echo $std_studying_year;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Medium : <strong><?php echo medium_type($clg_std_medium)[0]['medium_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Scholarship No:  <strong><?php echo $clg_std_scholarship_no;?></strong></p>
													</div>
                                                </div>
                                                <?php }?>
                                            </div><!-- end col -->
											</div>
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Last Studied Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
                                            <?php if($hostel_type != "65f00a53eef3015995"){ ?>
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">School name :  <strong><?=school_name($last_std_scl_name)[0]['school_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Class  :  <strong><?=$last_std_class?></strong></p>
													</div>
                                                    <?php if($last_std_class == '11' || $last_std_class == '12'){?>
													<div class="col-sm-4">
													<p class="font-12">Group :  <strong><?=group_name($last_std_group)[0]['subject_name'];?></strong></p>
													</div>
                                                    <?php }?>
													<div class="col-sm-4">
													<p class="font-12"> Medium:  <strong><?=medium_type($last_std_medium)[0]['medium_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Institution Address:  <strong><?=$last_std_scl_add;?></strong></p>
													</div>
													
                                                </div>
                                            </div><!-- end col -->
											</div>
                                            <?php }else{?>
                                                <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Stream :  <strong><?php echo stream_type($last_std_stream)[0]['stream_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">University :  <strong><?php echo university_name($last_std_university)[0]['university_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> College name:  <strong><?php echo college_name($last_std_college_name)[0]['college_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Course:  <strong><?php echo course_name($last_std_degree)[0]['course_name'];?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Subject:  <strong><?php echo $last_std_subject;?></strong></p>
													</div>
													<!-- <div class="col-sm-4">
													<p class="font-12">Studying year:  <strong><?php echo $last_std_studying_year;?></strong></p>
													</div> -->
													<div class="col-sm-4">
													<p class="font-12">Medium : <strong><?php echo medium_type($last_clg_std_medium)[0]['medium_type'];?></strong></p>
													</div>
													<div class="col-sm-4">
                                                    <p class="font-12">Institution Address:  <strong><?=$last_clg_address;?></strong></p>

													</div>
                                                </div>
                                            </div><!-- end col -->
											</div>


                                                <?php }?>
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Address</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Door no :  <strong><?=$door_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Area  :  <strong><?=$area_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Landmark:  <strong><?=$landmark;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">District:  <strong><?=$district_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Taluk :  <strong><?=$taluk_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Village:  <strong><?=$village_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Pincode:  <strong><?=$pincode;?></strong></p>
													</div>
													
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Distance Details - in Kms</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Distance From Hostel To Home :  <strong><?=$hos_dis_home;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Distance From Hostel To Institute   :  <strong><?=$hos_dis_insti;?></strong></p>
													</div>
																									
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Identification Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Aadhaar Number :  <strong><?=$aadhar_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Ration Card No   :  <strong><?=$ration_card_no;?></strong></p>
													</div>
																									
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Bank Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Bank Name:  <strong><?=$bank_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Bank Account No: <strong><?=$bank_acc_no;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Branch Name:  <strong><?=$branch_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">IFSC CODE  :  <strong><?=$ifsc_code;?></strong></p>
													</div>
																									
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											
											
</div>
</div>

<div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Family Details</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
                                                    <?php if($orphan == 'NO'){?>
												<div class="row">

												<div class="col-sm-4">
                                                    <p class="font-12"> Father Name :  <strong><?=$father_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Father Occupation : <strong><?=$father_occuption;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Father Qualification :  <strong><?=$father_qualification;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Father Mobile No  :  <strong><?=$father_mob_no;?></strong></p>
													</div>
																									
                                                </div><br>
                                                <div class="row">

												<div class="col-sm-4">
                                                    <p class="font-12"> Mother Name :  <strong><?=$mother_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Mother Occupation : <strong><?=$mother_occupation;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Mother Qualification :  <strong><?=$mother_qualification;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Mother Mobile No  :  <strong><?=$mother_mob_no;?></strong></p>
													</div>
																									
                                                </div><br>
                                                <?php }?>
                                                <div class="row">

												<div class="col-sm-4">
                                                    <p class="font-12"> Guardian Name :  <strong><?=$guardian_name;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Guardian Occupation : <strong><?=$guardian_occuption;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Guardian Qualification :  <strong><?=$guardian_qualification;?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Guardian Mobile No  :  <strong><?=$guardian_mob_no;?></strong></p>
													</div>
																									
                                                </div>

                                            </div><!-- end col -->
											</div>

                                            <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Document Upload</b></p>
													
                                                </div>
                                            </div><!-- end col -->
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Aadhaar :  <?php echo $aadhar;?></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">TC / Bonafide : <?php echo $bonafide;?></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Bank Passbook :  <?php echo $bank_passbook;?></p>
													</div>
													
																									
                                                </div>
                                            </div><!-- end col -->
											</div>
											
											
											
</div>
</div>
											
											
											
</div>
</div>
										
<?php include 'footer.php' ?>
<?php
function image_view($doc_file_name = "")
{
    // echo $doc_file_name;
    // $file_names = explode(',', $doc_file_name);
    // $image_view = '';

    // if ($doc_file_name) {
    //     foreach ($file_names as $file_key => $doc_file_name) {
    //         if ($file_key != 0) {
    //             if ($file_key % 4 != 0) {
    //                 $image_view .= "&nbsp";
    //             } else {
    //                 $image_view .= "<br><br>";
    //             }
    //         }

            $cfile_name = explode('.', $doc_file_name);

            if ($doc_file_name) {

                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    // echo "dd";
                    $image_view .= '<a href="javascript:print_view(\'/' . $doc_file_name . '\')"><img src="../../../doc_upload/' . $doc_file_name . '"  width="20%" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' . $doc_file_name . '\')"><img src="../../../pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
                } 
                // else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/excel.png"  height="30px" width="30px" ></a>';
                // } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                //     $image_view .= '<a href="javascript:print(\'/' . $doc_file_name . '\')"><img src="uploads/word.png"  height="30px" width="30px" ></a>';
                // }
            }
            return $image_view;
        }
    // }

   
// }
?>
<script>
    function print_pdf(file_name)
	{
		
		 onmouseover=window.open('../../../doc_upload/' + file_name, '_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	}
    
    function print_view(file_name)
    {
       onmouseover= window.open('../../../doc_upload/'+file_name,'_blank','height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    } 
    </script>

