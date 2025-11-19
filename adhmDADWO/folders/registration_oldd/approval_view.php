<?php
session_start();

// Step 1: Check Authentication Status
if (!isset($_SESSION['user_id'])) {
    // Redirect unauthorized users to the login page
    header('Location: login.php');
    exit;
}

// Step 2: Secure File Access (optional)
// Implement authorization checks here if necessary

// Step 3: Fetch Unique ID
if (isset($_GET['unique_id'])) {
    $unique_id = $_GET['unique_id'];
    // Process the unique ID as needed
    // For example, retrieve the corresponding PDF file and display it
    // Make sure to implement appropriate security checks here
} else {
    // Handle case where unique ID is not provided
    echo 'Error: Unique ID is missing.';
}

?>    <!-- <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
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

$s1_unique_id = $_GET['unique_id'];

if ($_GET['unique_id']) {
    // echo $_GET["unique_id"];

    $unique_id = $_GET['unique_id'];
    // $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
    // $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

    // $unique_id  = $get_uni_id;

    $where = [
        'unique_id' => $unique_id,
    ];

    $table_s2 = 'std_app_s2';

    $table = 'std_app_s';

    $columns = [
        '(select aname from aadhar where aadhar.s1_unique_id = std_app_s.unique_id) as std_name',
        '(select mobile_no from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as mobile_no',
        // "(select class from std_app_emis_s3  where std_app_emis_s3.s1_unique_id = $table_1.s1_unique_id ) as class",
        '(select dob from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as dob',
        '(select gender from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as gender',
        '(select father_name from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as father_name',
        'std_app_no',
        'entry_date',
        "academic_year",
        "(select applied_date from batch_creation where batch_creation.s1_unique_id = $table.unique_id) as applied_date",
        "hostel_1 as std_hostel_name",
        "hostel_district_1 as std_hostel_district",
        "hostel_taluk_1 as std_hostel_taluk",
        "(select mother_name from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_mother_name",
        "(select father_qual from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_father_qual",
        "(select mother_qual from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_mother_qual",
        "(select father_occu from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_father_occu",
        "(select mother_occu from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_mother_occu",
        "(select single_parent from std_app_s6 where std_app_s6.s1_unique_id = $table.unique_id)as std_single_parent",
        '(select address from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as address',
        'unique_id',
        '(select age from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as age',
    ];

    $table_details = [
        $table,
        $columns,
    ];

    $result_values = $pdo->select($table_details, $where);
// print_r($result_values);
    if ($result_values->status) {
        $result_values = $result_values->data;

        $std_academic_year = academic_year($result_values[0]['academic_year'])[0]['amc_year'];
        $applied_date = $result_values[0]['applied_date'];
        $student_app_no = $result_values[0]['std_app_no'];
        $stduent_name = $result_values[0]['std_name'];
        $mobile_no = $result_values[0]['mobile_no'];
        $address = $result_values[0]['address'];

        // $std_father_name        = $result_values[0]["father_name"];
        $std_mother_name = $result_values[0]['std_mother_name'];
        $std_mother_qual = $result_values[0]['std_mother_qual'];
        $std_father_qual = $result_values[0]['std_father_qual'];
        $std_father_occu = $result_values[0]['std_father_occu'];
        $std_mother_occu = $result_values[0]['std_mother_occu'];
        $std_single_parent = $result_values[0]['std_single_parent'];
        $std_gender = $result_values[0]['gender'];

        $stdu_dob = $result_values[0]['dob'];
        $stdu_age = $result_values[0]['age'];

        $stdu_father_name = $result_values[0]['father_name'];


        if ($std_gender == '65584660e85afd2400') {
            $std_gender = 'Male';
        } else {
            $std_gender = 'Female';
        }

        $std_hostel_name = hostel_name($result_values[0]['std_hostel_name'])[0]['hostel_name'];
        $std_hostel_district = district_name($result_values[0]['std_hostel_district'])[0]['district_name'];
        $std_hostel_taluk = taluk_name($result_values[0]['std_hostel_taluk'])[0]['taluk_name'];
    }

    // hostel information

    $where1 = [
        's1_unique_id' => $unique_id,
    ];

    $table1 = 'std_app_s7';

    $columns1 = [
        'priority',
    ];

    $table_details1 = [
        $table1,
        $columns1,
    ];

    $result_values1 = $pdo->select($table_details1, $where1);
    // print_r($result_values1);die();

    if ($result_values1->status) {
        $result_values1 = $result_values1->data;

        $hostel_priority = $result_values1[0]['priority'];
    }

    // adhaar

    $where_adh = [
        's1_unique_id' => $unique_id,
    ];

    $table_adh = 'aadhar';

    $columns_adh = [
        'pro_image',
    ];

    $table_details_adh = [
        $table_adh,
        $columns_adh,
    ];

    $result_values_adh = $pdo->select($table_details_adh, $where_adh);
    // print_r($result_values_adh);die();

    if ($result_values_adh->status) {
        $result_values_adh = $result_values_adh->data;

        $pro_image = $result_values_adh[0]['pro_image'];

        $image_src_adhaar = 'data:image/jpeg;base64,'.$pro_image;
    }

    // personal-info

    $where2 = [
        's1_unique_id' => $unique_id,
    ];

    $table2 = 'std_app_s5';

    $columns2 = [
        'caste_name',
        'sub_caste_name',
        'c_no',
        'i_no',
        'income_level',
        "c_name",
        "i_name",
        'c_father_name',
        'c_mother_name',
        'i_father_name',
        'i_mother_name',
        'f_income_source',
        'm_income_source',
        'i_file_name',
        'c_file_name',
        'community_pdf',
        'income_pdf',
        'diffabled',
        "(select physically_challenged from physically_challenged where physically_challenged.unique_id = $table2.category) as category",
        'idnumber',
        'disability_percent',
        "(select dob from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as dob",
        "(select age from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as age",
        "(select blood_group from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as blood_group",
        "(select email_id from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as email_id",
        "(select religion from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as religion",
        "(select mother_tongue from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as mother_tongue",
        "(select refugee from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as refugee",
        "(select orphan from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as orphan",
        "(select first_graduate from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as first_graduate",
        "(select father_no from std_app_s6 where s1_unique_id = $table2.s1_unique_id)as father_no",
    ];

    $table_details2 = [
        $table2,
        $columns2,
    ];

    $result_values2 = $pdo->select($table_details2, $where2);

    if ($result_values2->status) {
        $result_values2 = $result_values2->data;

        $caste_name = $result_values2[0]['caste_name'];

        $sub_caste_name = $result_values2[0]['sub_caste_name'];
        $caste_no = $result_values2[0]['c_no'];
        $income_no = $result_values2[0]['i_no'];

        $student_dob = $result_values2[0]['dob'];

        $student_age = $result_values2[0]['age'];

        $income_level = $result_values2[0]['income_level'];

        $community_student_name = $result_values2[0]['c_name'];

        $income_student_name = $result_values2[0]['i_name'];

        $diffabled = $result_values2[0]['diffabled'];
        $c_father_name = $result_values2[0]['c_father_name'];
        $c_mother_name = $result_values2[0]['c_mother_name'];
        $i_father_name = $result_values2[0]['c_father_name'];
        $i_mother_name = $result_values2[0]['i_mother_name'];

        $diffabled_categ = $result_values2[0]['category'];
        $diffabled_idnum = $result_values2[0]['idnumber'];
        $diffabled_percent = $result_values2[0]['disability_percent'];
        $student_blood_group = $result_values2[0]['blood_group'];
        $std_email_id = $result_values2[0]['email_id'];
        $std_religion = $result_values2[0]['religion'];
        $std_mother_tongue = $result_values2[0]['mother_tongue'];
        $std_refugee = $result_values2[0]['refugee'];
        $std_orphan = $result_values2[0]['orphan'];
        $std_first_graduate = $result_values2[0]['first_graduate'];
        $std_father_num = $result_values2[0]['father_no'];
        $father_income_source = $result_values2[0]['f_income_source'];
        $mother_income_source = $result_values2[0]['m_income_source'];
        $income_file = $result_values2[0]['i_file_name'];
        $community_files = $result_values2[0]['c_file_name'];

        $community_pdf = $result_values2[0]['community_pdf'];

        $income_pdf = $result_values2[0]['income_pdf'];

        $std_income_file = image_view($income_file);

        $std_community_files = image_view($community_files);

        //    if($community_files){

        //     $std_community_pdf = $std_community_files;

        //    }
        //    else{

        //     $std_community_pdf =  $community_pdf;

        //    }
    }
}

// s6

$where_s6 = [
    's1_unique_id' => $unique_id,
];

$table_s6 = 'std_app_s6';

$columns_s6 = [
    'father_name as std_father_name',
    'mother_name as std_mother_name',
    'father_qual as std_father_qual',
    'mother_qual as std_mother_qual',
    'father_occu as std_father_occu',
    'mother_occu as std_mother_occu',
    'single_parent as std_single_parent',
    'dob ',
    'age',
    'blood_group',
    'email_id',
    'religion',
    'mother_tongue',
    'refugee',
    'orphan',
    'first_graduate',
    'father_no',
];

$table_details_s6 = [
    $table_s6,
    $columns_s6,
];

$result_values_s6 = $pdo->select($table_details_s6, $where_s6);
// print_r($result_values_s6);die();

if ($result_values_s6->status) {
    $result_values_s6 = $result_values_s6->data;

    $std_father_name_s6 = $result_values_s6[0]['std_father_name'];
    $std_mother_name_s6 = $result_values_s6[0]['std_mother_name'];
    $std_mother_qual_s6 = $result_values_s6[0]['std_mother_qual'];
    $std_father_qual_s6 = $result_values_s6[0]['std_father_qual'];
    $std_father_occu_s6 = $result_values_s6[0]['std_father_occu'];
    $std_mother_occu_s6 = $result_values_s6[0]['std_mother_occu'];
    $std_single_parent_s6 = $result_values_s6[0]['std_single_parent'];

    $student_dob_s6 = $result_values_s6[0]['dob'];

    $student_age_s6 = $result_values_s6[0]['age'];

    // $diffabled             = $result_values_s6[0]["diffabled"];
    $student_blood_group_s6 = $result_values_s6[0]['blood_group'];
    $std_email_id_s6 = $result_values_s6[0]['email_id'];
    $std_religion_s6 = $result_values_s6[0]['religion'];
    $std_mother_tongue_s6 = $result_values_s6[0]['mother_tongue'];
    $std_refugee_s6 = $result_values_s6[0]['refugee'];
    $std_orphan_s6 = $result_values_s6[0]['orphan'];
    $std_first_graduate_s6 = $result_values_s6[0]['first_graduate'];
    $std_father_num_s6 = $result_values_s6[0]['father_no'];
}

// school/college

$where3 = [
    'unique_id' => $unique_id,
];

$table3 = 'std_app_s';

$columns3 = [
    "(select class from std_app_emis_s3 where std_app_emis_s3.s1_unique_id =$table3.unique_id)as std_class",
    "(select school_name from std_app_emis_s3 where std_app_emis_s3.s1_unique_id =$table3.unique_id)as std_school_name",
    "(select school_district from std_app_emis_s3 where std_app_emis_s3.s1_unique_id =$table3.unique_id)as std_school_district",
    "(select medium from std_app_emis_s3 where std_app_emis_s3.s1_unique_id =$table3.unique_id)as std_medium",
    "(select school_block from std_app_emis_s3 where std_app_emis_s3.s1_unique_id =$table3.unique_id)as std_school_block",
    'application_type',
    // "(select priority from std_app_s7 where std_app_s7.s1_unique_id = $table3.unique_id)as hostel_priority",
    'student_type',
];

$table_details3 = [
    $table3,
    $columns3,
];

$result_values3 = $pdo->select($table_details3, $where3);

if ($result_values3->status) {
    $result_values3 = $result_values3->data;

    $student_class = $result_values3[0]['std_class'];
    $student_school_name = $result_values3[0]['std_school_name'];
    $student_school_district = $result_values3[0]['std_school_district'];
    $student_medium = $result_values3[0]['std_medium'];
    $student_school_block = $result_values3[0]['std_school_block'];

    $std_application_type = $result_values3[0]['application_type'];

    if ($std_application_type == 1) {
        $std_application_type = 'New';
    } elseif ($std_application_type == 2) {
        $std_application_type = 'Renewal';
    }

    $student_type = $result_values3[0]['student_type'];
}

$where4 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table4 = 'std_app_p4';

$columns = [
    'is_renewal',
    'emis_no',
    'umis_no',
    'std_dob',
    'age',
    'blood_group',
    'gender',
    'email_id',
    'religion',
    'mother_tongue',
    'community_cer_no',
    'std_caste',
    'std_sub_caste',
    'contact_no_type',
    'contact_no',
    'income_cer_no',
    'annual_income',
    'remarks',
    'physically_challenge',
    'phy_category',
    'phy_percentage',
    'phy_id_no',
    'srilankan_refugees',
    'orphan',
    'single_parent',
    'first_graduate',
    'graduate_no',
];

$table_details4 = [
    $table4,
    $columns,
];

$result_values4 = $pdo->select($table_details4, $where4);

if ($result_values4->status) {
    $result_values4 = $result_values4->data;
    $is_renewal = $result_values4[0]['is_renewal'];
    $emis_no = $result_values4[0]['emis_no'];
    $umis_no = $result_values4[0]['umis_no'];
    $std_dob = $result_values4[0]['std_dob'];
    $age = $result_values4[0]['age'];
    $blood_group = $result_values4[0]['blood_group'];
    $gender = $result_values4[0]['gender'];
    $email_id = $result_values4[0]['email_id'];
    $religion = $result_values4[0]['religion'];
    $mother_tongue = $result_values4[0]['mother_tongue'];
    $community_cer_no = $result_values4[0]['community_cer_no'];
    $std_caste = $result_values4[0]['std_caste'];
    $std_sub_caste = $result_values4[0]['std_sub_caste'];
    $contact_no_type = $result_values4[0]['contact_no_type'];
    $contact_no = $result_values4[0]['contact_no'];
    $income_cer_no = $result_values4[0]['income_cer_no'];
    // $annual_income      = $result_values4[0]["annual_income"];
    $remarks = $result_values4[0]['remarks'];
    $physically_challenge = $result_values4[0]['physically_challenge'];
    $phy_category = $result_values4[0]['phy_category'];
    $phy_percentage = $result_values4[0]['phy_percentage'];
    $phy_id_no = $result_values4[0]['phy_id_no'];
    $srilankan_refugees = $result_values4[0]['srilankan_refugees'];
    $orphan = $result_values4[0]['orphan'];
    $single_parent = $result_values4[0]['single_parent'];
    $first_graduate = $result_values4[0]['first_graduate'];
    $graduate_no = $result_values4[0]['graduate_no'];

    switch ($is_renewal) {
        case 1:
            $is_renewal = 'Fresh';
            break;
        case 2:
            $is_renewal = 'Renewal';
            break;
    }
    switch ($gender) {
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

$where5 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table5 = 'std_app_p5';

$columns = [
    'std_school_name',
    'std_class',
    'std_group',
    'std_medium',
    'scl_std_scholarship_no',
    'std_stream',
    'std_university',
    'std_college_name',
    'std_degree',
    'std_subject',
    'std_studying_year',
    'clg_std_medium',
    'clg_std_scholarship_no	',
];
$table_details5 = [
    $table5,
    $columns,
];

$result_values5 = $pdo->select($table_details5, $where5);
// print_r($result_values5);
if ($result_values5->status) {
    $result_values5 = $result_values5->data;
    $std_school_name = $result_values5[0]['std_school_name'];
    $std_class = $result_values5[0]['std_class'];
    $std_group = $result_values5[0]['std_group'];
    $std_medium = $result_values5[0]['std_medium'];
    $scl_std_scholarship_no = $result_values5[0]['scl_std_scholarship_no'];
    $std_stream = $result_values5[0]['std_stream'];
    $std_university = $result_values5[0]['std_university'];
    $std_college_name = $result_values5[0]['std_college_name'];
    $std_degree = $result_values5[0]['std_degree'];
    $std_subject = $result_values5[0]['std_subject'];
    $std_studying_year = $result_values5[0]['std_studying_year'];
    $clg_std_medium = $result_values5[0]['clg_std_medium'];
    $clg_std_scholarship_no = $result_values5[0]['clg_std_scholarship_no'];

    switch ($std_studying_year) {
        case 1:
            $std_studying_year = '1st Year';
            break;
        case 2:
            $std_studying_year = '2nd Year';
            break;
        case 3:
            $std_studying_year = '3rd Year';
            break;
        case 4:
            $std_studying_year = '4th Year';
            break;
        case 5:
            $std_studying_year = '5th Year';
            break;
    }
}

// noumis_no
$where6 = [
    's1_unique_id' => $unique_id,
];

$table6 = 'std_app_umis_s4';

$columns = [
    'umis_no',
    'umis_name',
    'umis_yoa',
    'umis_dob',
    'umis_yos',
    'umis_clg_name',
    'umis_clg_add',
    'umis_std_degree',
    'umis_std_course',
    'year_studying',
    'no_umis_college',
    'no_umis_name',
    'no_umis_stream',
    // "(select course_name from course_creation where course_creation.unique_id = $table6.no_umis_course)as no_umis_course",
    "(select CourseName from umis_course where umis_course.Id = $table6.no_umis_course) as no_umis_degree",
    "(select district_name from district_name where district_name.unique_id =$table6.no_umis_clg_district)as no_umis_clg_district",
    ' no_umis_pincode',
    ' no_umis_yoa',
    ' no_umis_yos',
    'got_addmission',
];

$table_details6 = [
    $table6,
    $columns,
];

$result_values6 = $pdo->select($table_details6, $where6);

// print_r( $result_values6);

if ($result_values6->status) {
    $result_values6 = $result_values6->data;

    $student_clg_name = $result_values6[0]['umis_clg_name'];
    $student_degree_name = $result_values6[0]['umis_std_degree'];
    $student_course_name = $result_values6[0]['umis_std_course'];

    $student_studying_year = $result_values6[0]['year_studying'];

    $student_clg_address = $result_values6[0]['umis_clg_add'];

    $clg_umis_no = $result_values6[0]['umis_no'];

    $umis_admission_type = $result_values6[0]['got_addmission'];
    $no_umis_clg = $result_values6[0]['no_umis_college'];
    $no_umis_name = $result_values6[0]['no_umis_name'];
    $no_umis_course = $result_values6[0]['no_umis_stream'];
    $no_umis_degree = $result_values6[0]['no_umis_degree'];
    $no_umis_clg_district = $result_values6[0]['no_umis_clg_district'];
    $no_umis_pincode = $result_values6[0]['no_umis_pincode'];
    $no_umis_yoa = $result_values6[0]['no_umis_yoa'];
    $no_umis_yos = $result_values6[0]['no_umis_yos'];
}

$where7 = [
    'unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table7 = 'std_app_p7';

$columns = [
    'door_no',
    'area_name',
    'landmark',
    'district_name',
    'taluk_name',
    'village_name',
    'pincode',
];

$table_details7 = [
    $table7,
    $columns,
];

$result_values7 = $pdo->select($table_details7, $where7);

if ($result_values7->status) {
    $result_values7 = $result_values7->data;
    $door_no = $result_values7[0]['door_no'];
    $area_name = $result_values7[0]['area_name'];
    $landmark = $result_values7[0]['landmark'];
    $district_name = $result_values7[0]['district_name'];
    $taluk_name = $result_values7[0]['taluk_name'];
    $village_name = $result_values7[0]['village_name'];
    $pincode = $result_values7[0]['pincode'];
}

$where8 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table8 = 'std_app_p8';

$columns = [
    'hos_dis_home',
    'hos_dis_insti',
];

$table_details8 = [
    $table8,
    $columns,
];

$result_values8 = $pdo->select($table_details8, $where8);

if ($result_values8->status) {
    $result_values8 = $result_values8->data;
    $hos_dis_home = $result_values8[0]['hos_dis_home'];
    $hos_dis_insti = $result_values8[0]['hos_dis_insti'];
}

$where9 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table9 = 'std_app_p9';

$columns = [
    'aadhar_no',
    'ration_card_no',
];

$table_details9 = [
    $table9,
    $columns,
];

$result_values9 = $pdo->select($table_details9, $where9);

if ($result_values9->status) {
    $result_values9 = $result_values9->data;
    $aadhar_no = $result_values9[0]['aadhar_no'];
    $ration_card_no = $result_values9[0]['ration_card_no'];
}

$where10 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table10 = 'std_app_p10';

$columns = [
    'bank_name',
    'bank_acc_no',
    'branch_name',
    'ifsc_code',
];

$table_details10 = [
    $table10,
    $columns,
];

$result_values10 = $pdo->select($table_details10, $where10);

if ($result_values10->status) {
    $result_values10 = $result_values10->data;
    $bank_name = $result_values10[0]['bank_name'];
    $bank_acc_no = $result_values10[0]['bank_acc_no'];
    $branch_name = $result_values10[0]['branch_name'];
    $ifsc_code = $result_values10[0]['ifsc_code'];
}

$where11 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table11 = 'std_app_p11';

$columns = [
    'father_name',
    'father_occuption',
    'father_qualification',
    'father_mob_no',
    'mother_name',
    'mother_occupation',
    'mother_qualification',
    'mother_mob_no',
    'guardian_name',
    'guardian_occuption',
    'guardian_qualification',
    'guardian_mob_no',
];

$table_details11 = [
    $table11,
    $columns,
];

$result_values11 = $pdo->select($table_details11, $where11);

if ($result_values11->status) {
    $result_values11 = $result_values11->data;
    $father_name = $result_values11[0]['father_name'];
    $father_qualification = $result_values11[0]['father_qualification'];
    $father_occuption = $result_values11[0]['father_occuption'];
    $father_mob_no = $result_values11[0]['father_mob_no'];
    $mother_name = $result_values11[0]['mother_name'];
    $mother_occupation = $result_values11[0]['mother_occupation'];
    $mother_qualification = $result_values11[0]['mother_qualification'];
    $mother_mob_no = $result_values11[0]['mother_mob_no'];
    $guardian_name = $result_values11[0]['guardian_name'];
    $guardian_occuption = $result_values11[0]['guardian_occuption'];
    $guardian_qualification = $result_values11[0]['guardian_qualification'];
    $guardian_mob_no = $result_values11[0]['guardian_mob_no'];
}

$where11 = [
    'p1_unique_id' => $p1_unique_id,
    'is_delete' => '0',
];

$table11 = 'std_app_p12';

$columns = [
    'aadhar_file',
    'bonafide_file',
    'bank_passbook_file',
];

$table_details11 = [
    $table11,
    $columns,
];

$result_values11 = $pdo->select($table_details11, $where11);

if ($result_values11->status) {
    $result_values11 = $result_values11->data;
    $aadhar_file = $result_values11[0]['aadhar_file'];
    $bonafide_file = $result_values11[0]['bonafide_file'];
    $bank_passbook_file = $result_values11[0]['bank_passbook_file'];

    $aadhar = image_view($aadhar_file);
    $bonafide = image_view($bonafide_file);
    $bank_passbook = image_view($bank_passbook_file);
}

?>



    <?php include 'header.php'; ?>
    <link href='../../assets/css/app-saas.min.css' rel='stylesheet' type='text/css'>
    <style>
        .card-body {
            margin: 20px;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .vendorListHeading {
            background-color: #f3f3f3;
            color: black;
            -webkit-print-color-adjust: exact;
            border: 1px solid #ccc;
        }

        .vendorListHeading p {
            margin-bottom: 0px;
            text-align: center;
            padding: 5px;
        }
    </style>
    <div class="card-body">
        <div class="clearfix">
            <div class=" mb-3 mt-1 text-center vendorListHeading2">
                <img src="../../../assets/images/ad-logo.png" alt="dark logo" height="50">
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12 mb-2">
                <div class=" mt-1 vendorListHeading">
                    <p><b>Aadhaar Confirmation</b></p>

                </div>
            </div><!-- end col -->
            <div class="col-sm-12 ">
                <div class="mt-0 float-sm-left">
                    <div class="row">
                        <div class="col-sm-3">



                        <img src="<?php echo $image_src_adhaar; ?>" class=" avatar-lg img-thumbnail"
													alt="profile-image">
                            <!-- <img src="../../../student_img/<?php echo $std_img; ?>" width="100" height="100"> -->
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="font-12">Student Name: <strong><?php echo $stduent_name; ?></strong></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="font-12">DOB: <strong><?php echo $stdu_dob; ?></strong></p>
                                </div>

                                <div class="col-sm-6">
                                    <p class="font-12">Mobile Number: <strong><?php echo $mobile_no; ?></strong></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="font-12">Age: <strong><?php echo $stdu_age; ?></strong></p>
                                </div>

                                <div class="col-sm-6">
                                    <p class="font-12">Gender: <strong><?php echo $std_gender; ?></strong></p>
                                </div>

                                <div class="col-sm-6">
                                    <p class="font-12">Address: <strong><?php echo $address; ?></strong></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="font-12">Father Name: <strong><?php echo $stdu_father_name; ?></strong></p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="font-12">Application Number: <strong><?php echo $student_app_no; ?></strong></p>
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
                            <p class="font-12">Priority: <strong><?php echo $hostel_priority; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Hostel District: <strong><?php echo $std_hostel_district; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12"> Hostel Taluk: <strong><?php echo $std_hostel_taluk; ?></strong></p>
                        </div>
                        <!-- <div class="col-sm-4">
													<p class="font-12">Hostel Gender Type:  <strong><?php echo $gender_type; ?></strong></p>
													</div> -->
                        <!-- <div class="col-sm-4">
													<p class="font-12">Hostel Type:  <strong><?php echo $host_type; ?></strong></p>
													</div> -->
                        <div class="col-sm-4">
                            <p class="font-12">Hostel Name: <strong><?php echo $std_hostel_name; ?></strong></p>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>



            <div class="col-sm-12 mb-2">
                <div class=" mt-1 vendorListHeading">
                    <p><b> Personal Info</b></p>

                </div>
            </div><!-- end col -->
            <div class="col-sm-12 ">
                <div class="mt-0 float-sm-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <p class="font-12">Fresh or renewal: <strong><?php echo $std_application_type; ?></strong></p>
                        </div>
                        <?php if ($hostel_type == '65f00a259436412348') { ?>
                            <div class="col-sm-4">
                                <p class="font-12">EMIS No: <strong><?php echo $emis_no; ?></strong></p>
                            </div>
                        <?php } else { ?>
                            <!-- <div class="col-sm-4">
													<p class="font-12">UMIS No:  <strong><?php echo $umis_no; ?></strong></p>
													</div> -->
                        <?php } ?>
                        <div class="col-sm-4">
                            <p class="font-12"> D.O.B: <strong><?php echo $student_dob_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12"> Age: <strong><?php echo $student_age_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Blood Group: <strong><?php echo $student_blood_group_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Gender : <strong><?php echo $std_gender; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12"> Email Id : <strong><?php echo $std_email_id_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Religion : <strong><?php echo $std_religion_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Mother Tongue : <strong><?php echo $std_mother_tongue_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Community Certificate No : <strong><?php echo $caste_no; ?></strong></p>
                        </div>
                        <?php if ($std_orphan == 'NO') { ?>
                            <div class="col-sm-4">
                                <p class="font-12">Father Name: <strong><?php echo $std_father_name_s6; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">Mother Name: <strong><?php echo $std_mother_name; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">Father Qualification: <strong><?php echo $std_father_qual; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">Mother Qualification: <strong><?php echo $std_mother_qual; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">Father Occupation: <strong><?php echo $std_father_occu; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">Mother Occupation: <strong><?php echo $std_mother_occu; ?></strong></p>
                            </div>
                        <?php } ?>

                      
                        <div class="col-sm-4">
                            <p class="font-12"> Parent Number : <strong><?php echo $std_father_num_s6; ?></strong></p>
                        </div>
                       
                        <!-- <div class="col-sm-4">
				<p class="font-12"> Remarks :  <strong><?php echo $remarks; ?></strong></p>
				</div> -->
                        <div class="col-sm-4">
                            <p class="font-12"> Physically challenged : <strong><?php echo $diffabled; ?></strong></p>
                        </div>
                        <?php if ($diffabled == 'Yes') { ?>
                            <div class="col-sm-4">


                                <p class="font-12"> ID Card No : <strong><?php echo $diffabled_idnum; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12"> Challenged Category : <strong><?php echo $diffabled_categ; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12"> Challenged Percentage : <strong><?php echo $diffabled_percent; ?></strong></p>
                            </div>
                        <?php } ?>
                        <div class="col-sm-4">
                            <p class="font-12"> Srilankan Refugees: <strong><?php echo $std_refugee_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                            <p class="font-12">Orphanages: <strong><?php echo $std_orphan_s6; ?></strong></p>
                        </div>
                        <div class="col-sm-4">
                                <p class="font-12">Single Parent Child: <strong><?php echo $std_single_parent_s6; ?></strong></p>
                            </div>
                        <?php if ($std_orphan_s6 == 'NO') { ?>
                            <div class="col-sm-4">
                                <p class="font-12">Single Parent Child: <strong><?php echo $std_single_parent; ?></strong></p>
                            </div>
                        <?php } ?>
                        <div class="col-sm-4">
                            <p class="font-12">First Graduate: <strong><?php echo $std_first_graduate_s6; ?></strong></p>
                        </div>
                        <?php if ($first_graduate == 'YES') { ?>
                            <div class="col-sm-4">
                                <p class="font-12">Graduate No: <strong><?php echo $graduate_no; ?></strong></p>
                            </div>
                        <?php } ?>
                    </div>
                </div><!-- end col -->
            </div>

            <div class="col-sm-12 mb-2">
                <div class=" mt-1 vendorListHeading">
                    <p><b> Education Details</b></p>

                </div>
            </div><!-- end col -->
            <div class="col-sm-12 ">
                <div class="mt-0 float-sm-left">
                    <div class="row">
                        <?php if ($student_type == '65f00a259436412348') { ?>
                            <div class="col-sm-4">
                                <p class="font-12">School Name : <strong><?php echo $student_school_name; ?></strong></p>
                            </div>
                            <!-- student_school_name -->
                            <div class="col-sm-4">
                                <p class="font-12"> Class : <strong><?php echo $student_class; ?></strong></p>
                            </div>
                            <?php if ($std_class == '11' || $std_class == '12') { ?>
                                <div class="col-sm-4">
                                    <p class="font-12">Group : <strong><?php echo group_name($std_group)[0]['subject_name']; ?></strong></p>
                                </div>
                            <?php } ?>

                            <div class="col-sm-4">
                                <p class="font-12">Medium : <strong><?php echo $student_medium; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">School District: <strong><?php echo $student_school_district; ?></strong></p>
                            </div>
                            <div class="col-sm-4">
                                <p class="font-12">School Block: <strong><?php echo $student_school_block; ?></strong></p>
                            </div>
                    </div>
                <?php } else { ?>

                    <?php
                if ($umis_admission_type === 'YES' || $umis_admission_type === 'Yes') {
                    ?>



                    <!-- <div class="col-sm-4">
                                                    <p class="font-12">Stream :  <strong><?php echo stream_type($std_stream)[0]['stream_type']; ?></strong></p>
													</div> -->
                    <!-- <div class="col-sm-4">
													<p class="font-12">University :  <strong><?php echo university_name($std_university)[0]['university_name']; ?></strong></p>
													</div> -->
                    <div class="col-sm-4">
                        <p class="font-12"> College name: <strong><?php echo $student_clg_name; ?></strong></p>
                    </div>
                    <!-- <?php echo course_name($std_degree)[0]['course_name']; ?> -->
                    <div class="col-sm-4">
                        <p class="font-12">Degree: <strong><?php echo $student_degree_name; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Course: <strong><?php echo $student_course_name; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Studying year: <strong><?php echo $student_studying_year; ?></strong></p>
                    </div>
                    <!-- medium_type($clg_std_medium)[0]['medium_type'] -->
                    <div class="col-sm-4">
                        <p class="font-12">College Address: <strong><?php echo $student_clg_address; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">UMIS NO: <strong><?php echo $clg_umis_no; ?></strong></p>
                    </div>
            

              <?php } else { ?>
              
                <div class="col-sm-4">
                        <p class="font-12"> Student name: <strong><?php echo $no_umis_name; ?></strong></p>
                    </div>
               <div class="col-sm-4">
                        <p class="font-12"> College name: <strong><?php echo $no_umis_clg; ?></strong></p>
                    </div>
                    <!-- <?php echo course_name($std_degree)[0]['course_name']; ?> -->
                    <div class="col-sm-4">
                        <p class="font-12">Degree: <strong><?php echo $no_umis_degree; ?></strong></p>
                    </div>
                    <div class="col-sm-4">

                    <!-- <div class="col-sm-4"> -->

                            <?php

                            if ($no_umis_course == 1) {
                                $no_umis_course = 'ITI';
                            } elseif ($no_umis_course == 2) {
                                $no_umis_course = 'Diploma';
                            } elseif ($no_umis_course == 3) {
                                $no_umis_course = 'UG';
                            } elseif ($no_umis_course == 4) {
                                $no_umis_course = 'PG';
                            } elseif ($no_umis_course == 5) {
                                $no_umis_course = 'PHD';
                            } ?>

                        <p class="font-12">Course: <strong><?php echo $no_umis_course; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Studying year: <strong><?php echo $student_studying_year; ?></strong></p>
                    </div>
                    <!-- medium_type($clg_std_medium)[0]['medium_type'] -->
                    <div class="col-sm-4">
                        <p class="font-12">College District: <strong><?php echo $no_umis_clg_district; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Got Admission: <strong><?php echo $umis_admission_type; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Admission Year: <strong><?php echo $no_umis_yoa; ?></strong></p>
                    </div>

              <?php } ?>

              <?php } ?>



                </div>
            
            </div><!-- end col -->
        </div>

        <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Last Studied Details</b></p>
													
                                                </div>
                                            </div> -->
        <!-- end col -->
        <!-- <?php if ($hostel_type != '65f00a259436412348') { ?>
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                <?php echo school_name($last_std_scl_name)[0]['school_name']; ?>
                                                    <p class="font-12">School name :  <strong></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Class  :  <strong><?php echo $last_std_class; ?></strong></p>
													</div>
                                                    <?php if ($last_std_class == '11' || $last_std_class == '12') { ?>
													<div class="col-sm-4">
													<p class="font-12">Group :  <strong><?php echo group_name($last_std_group)[0]['subject_name']; ?></strong></p>
													</div>
                                                    <?php } ?>
													<div class="col-sm-4">
													<p class="font-12"> Medium:  <strong><?php echo medium_type($last_std_medium)[0]['medium_type']; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Institution Address:  <strong><?php echo $last_std_scl_add; ?></strong></p>
													</div>
													
                                                </div>
                                            </div>
											</div> -->
    <?php } else { ?>
        <div class="col-sm-12 ">
            <div class="mt-0 float-sm-left">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="font-12">Stream : <strong><?php echo stream_type($last_std_stream)[0]['stream_type']; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">University : <strong><?php echo university_name($last_std_university)[0]['university_name']; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12"> College name: <strong><?php echo college_name($last_std_college_name)[0]['college_name']; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Course: <strong><?php echo course_name($last_std_degree)[0]['course_name']; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Subject: <strong><?php echo $last_std_subject; ?></strong></p>
                    </div>

                    <div class="col-sm-4">
                        <p class="font-12">Medium : <strong><?php echo medium_type($last_clg_std_medium)[0]['medium_type']; ?></strong></p>
                    </div>
                    <div class="col-sm-4">
                        <p class="font-12">Institution Address: <strong><?php echo $last_clg_address; ?></strong></p>

                    </div>
                </div>
            </div>
        </div>


    <?php } ?>

    <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Address</b></p>
													
                                                </div>
                                            </div>
											<div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Door no :  <strong><?php echo $door_no; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Area  :  <strong><?php echo $area_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Landmark:  <strong><?php echo $landmark; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">District:  <strong><?php echo $district_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Taluk :  <strong><?php echo $taluk_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Village:  <strong><?php echo $village_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Pincode:  <strong><?php echo $pincode; ?></strong></p>
													</div>
													
                                                </div>
                                            </div>
											</div> -->

    <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Distance Details - in Kms</b></p>
													
                                                </div> -->
    <!-- </div> -->
    <!-- end col -->
    <!-- <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12">Distance From Hostel To Home :  <strong><?php echo $hos_dis_home; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Distance From Hostel To Institute   :  <strong><?php echo $hos_dis_insti; ?></strong></p>
													</div>
																									
                                                </div> -->
    <!-- </div> -->
    <!-- end col -->
    <!-- </div> -->


    <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b> Identification Details</b></p>
													
                                                </div>
                                            </div> -->
    <!-- end col -->
    <!-- <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Aadhaar Number :  <strong><?php echo $aadhar_no; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Ration Card No   :  <strong><?php echo $ration_card_no; ?></strong></p>
													</div>
																									
                                                </div>
                                            </div>
                                            <end col -->
    <!-- </div>  -->

    <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Bank Details</b></p>
													
                                                </div> -->
    <!-- </div> -->
    <!-- end col -->
    <!-- <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Bank Name:  <strong><?php echo $bank_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">Bank Account No: <strong><?php echo $bank_acc_no; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Branch Name:  <strong><?php echo $branch_name; ?></strong></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">IFSC CODE  :  <strong><?php echo $ifsc_code; ?></strong></p>
													</div>
																									
                                                </div>
                                            </div>
                                            < end col -->
    <!-- </div>  -->




    </div>

    <div class="col-sm-12 mb-2">
        <div class=" mt-1 vendorListHeading">
            <p><b>Community and income Certificate</b></p>

        </div>
    </div><!-- end col -->
    <div class="col-sm-12 mb-2 ">
        <div class="mt-0 float-sm-left">

            <div class="row">

                <div class="col-sm-4">
                    <b>
                        <p class="font-12"> Community Certificate No:<strong><?php echo $caste_no; ?></strong></p>
                    </b>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-4">
                    <p class="font-12">Full Name : <strong><?php echo $community_student_name; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Caste Name : <strong><?php echo $caste_name; ?></strong></p>
                </div>

                <div class="col-sm-4">
                    <p class="font-12"> Sub Caste Name : <strong><?php echo $sub_caste_name; ?></strong></p>
                </div>
                <div class="col-sm-4">


                <?php if ($community_files) { ?>

                    <p class="font-12"> Community Certificate: <strong><?php echo $std_community_files; ?></strong></p>
                    <?php } else { ?>

                    <p class="font-12"> Community Certificate: <strong><a href="<?php echo $community_pdf; ?>"><img src="../../assets/images/pdf.png" width="30%" height="36%"></a></strong></p>

                    <?php } ?>

                    <!-- <p class="font-12"> Community Certificate: <strong><a href="<?php echo $community_pdf; ?>"><img src="../../assets/images/pdf.png" width="30%" height="36%"></a></strong></p> -->
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Father Name : <strong><?php echo $c_father_name; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Mother Name : <strong><?php echo $i_mother_name; ?></strong></p>
                </div>

            </div>
            <!-- income-certificate -->

            <div class="row">

                <div class="col-sm-4">
                    <b>
                        <p class="font-12"> Income Certificate No : <strong><?php echo $income_no; ?></strong></p>
                    </b>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4">
                    <p class="font-12">Full Name : <strong><?php echo $income_student_name; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12"> Father Income Source: <strong><?php echo $father_income_source; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Mother Income Source: <strong><?php echo $mother_income_source; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Income Level: <strong><?php echo $income_level; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Father Name : <strong><?php echo $i_father_name; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12">Mother Name: <strong><?php echo $i_mother_name; ?></strong></p>
                </div>
                <div class="col-sm-4">
                    <p class="font-12"> Income Certificate: <strong><a href="<?php echo $income_pdf; ?>"><img src="../../assets/images/pdf.png" width="30%" height="36%"></a></strong></p>
                </div>
 
            </div>



        </div><!-- end col -->
    </div>

    <!-- <div class="col-sm-12 mb-2">
                                                <div class=" mt-1 vendorListHeading">
                                                    <p><b>Document Upload</b></p>
													
                                                </div>
                                            </div> -->
    <!-- end col -->
    <!-- <div class="col-sm-12 ">
                                                <div class="mt-0 float-sm-left">
												<div class="row">
												<div class="col-sm-4">
                                                    <p class="font-12"> Aadhaar :  <?php echo $aadhar; ?></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12">TC / Bonafide : <?php echo $bonafide; ?></p>
													</div>
													<div class="col-sm-4">
													<p class="font-12"> Bank Passbook :  <?php echo $bank_passbook; ?></p>
													</div> -->


    <!-- </div> -->
    <!-- </div> -->
    <!-- end col -->
    <!-- </div> -->



    </div>
    </div>

    </div>

    </div>
    </div>

    <?php include 'footer.php'; ?>
    <?php
    function image_view($doc_file_name = '')
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
                $image_view .= '<a href="javascript:print_view(\'/'.$doc_file_name.'\')"><img src="../../../uploads/'.$doc_file_name.'"  width="20%" ></a>';
                // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
            } elseif ($cfile_name[1] == 'pdf') {
                $image_view .= '<a href="javascript:print_pdf(\'/'.$doc_file_name.'\')"><img src="../../../pdf.png"   width="20%" style="margin-left: 15px;" ></a>';
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

        
function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../../../uploads' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf(file_name) {
	var pdfUrl = "../../../uploads/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "../../../uploads/" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

        // function print_pdf(file_name) {

        //     onmouseover = window.open('../../../uploads/' + file_name, '_blank', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
        // }

        // function print_view(file_name) {
        //     onmouseover = window.open('../../../uploads/' + file_name, '_blank', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
        // }
    </script>