<link href='app-saas.min.css' rel='stylesheet' type='text/css'>
<?php
// Form variables
$hostel_name            = "";
$hostel_id              = "";
$district_name          = "";
$taluk_name             = "";
$special_tahsildar      = "";
$assembly_const         = "";
$parliment_const        = "";
$address                = "";
$hostel_location        = "";
$urban_type             = "";
$corporation            = "";
$municipality           = "";
$town_panchayat         = "";
$block                  = "";
$village_name           = "";
$hostel_type            = "";
$gender_type            = "";
$yob                    = "";
$sanctioned_strength    = "";
$distance_btw_phc       = "";
$phc_name               = "";
$distance_btw_ps        = "";
$ps_name                = "";
$staff_count            = "";
$latitude               = "";
$longitude              = "";
$unique_id              = "";
$is_active              = 1;

include '../../config/dbconfig.php';
//echo $_GET['unique_id'];

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {

        $unique_id  = $_GET['unique_id'];
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "hostel_name";

        $columns    = [
            "hostel_name",
            "hostel_id",
            "(select district_name from district_name where  district_name.unique_id = hostel_name.district_name) as district_name",
            "hostel_name",
            "(select taluk_name from taluk_creation where taluk_creation.unique_id = hostel_name.taluk_name) as taluk_name",
            "special_tahsildar",
            "assembly_const",
            "parliment_const",
            "address",
            "hostel_location",
            "urban_type",
            "corporation",
            "municipality",
            "town_panchayat",
            "block",
            "village_name",
            "(select hostel_type from hostel_type where hostel_type.unique_id = hostel_name.hostel_type) as hostel_type",
            "(select gender_type from hostel_gender_type where hostel_gender_type.unique_id = hostel_name.gender_type) as gender_type",
            "yob",
            "sanctioned_strength",
            "distance_btw_phc",
            "phc_name",
            "distance_btw_ps",
            "ps_name",
            "staff_count",
            "latitude",
            "longitude",
            "is_active",
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values                      = $result_values->data;

            $hostel_name                        = $result_values[0]["hostel_name"];
            $hostel_id                          = $result_values[0]["hostel_id"];
            $district_name                      = $result_values[0]["district_name"];
            $taluk_name                         = $result_values[0]["taluk_name"];
            $special_tahsildar                  = $result_values[0]["special_tahsildar"];
            $assembly_const                     = $result_values[0]["assembly_const"];
            $parliment_const_name               = $result_values[0]["parliment_const"];
            $address                            = $result_values[0]["address"];
            $hostel_location                    = $result_values[0]["hostel_location"];
            $urban_type                         = $result_values[0]["urban_type"];
            $corporation                        = $result_values[0]["corporation"];
            $municipality                       = $result_values[0]["municipality"];
            $town_panchayat                     = $result_values[0]["town_panchayat"];
            $block                              = $result_values[0]["block"];
            $village_name                       = $result_values[0]["village_name"];
            $hostel_type                        = $result_values[0]["hostel_type"];
            $gender_type                        = $result_values[0]["gender_type"];
            $yob                                = $result_values[0]["yob"];
            $sanctioned_strength                = $result_values[0]["sanctioned_strength"];
            $distance_btw_phc                   = $result_values[0]["distance_btw_phc"];
            $phc_name                           = $result_values[0]["phc_name"];
            $distance_btw_ps                    = $result_values[0]["distance_btw_ps"];
            $ps_name                            = $result_values[0]["ps_name"];
            $staff_count                        = $result_values[0]["staff_count"];
            $latitude                           = $result_values[0]["latitude"];
            $longitude                          = $result_values[0]["longitude"];
            $is_active                          = $result_values[0]["is_active"];
            $unique_id                          = $result_values[0]["unique_id"];

        }
    }
}

?>
<style>
	.card-body {
		margin: 20px;
		border: 1px solid #ccc;
		padding: 20px;
	}

	.vendorListHeading {
		background-color: #f3f3f3;
		color: black;
		-webkit-print-color-adjust: exact;
		border: 1px solid #ccc;
	}

	.mt-2.vendorListHeading p {
		margin-bottom: 0px;
		text-align: center;
		padding: 5px;
	}
	
</style>
<div class="card-body">
	<div class="clearfix">
		<div class=" mb-3 mt-2 text-center vendorListHeading2">
			<img src="../../assets/images/ad-logo.png" alt="dark logo" height="50">
		</div>

	</div>
	<div class="row">
		
		<div class="col-sm-12 mb-2">
			<div class=" mt-2 vendorListHeading">
				<p><b>Hostel Details</b></p>
			</div>
		</div><!-- end col -->
		<div class="col-sm-12 ">
			<div class="mt-0 float-sm-left">
				<div class="row">
				   <div class="col-sm-6">
						<p class="font-12">Hostel Name: <strong><?= $hostel_name; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Address: <strong><?= $address; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Hostel District: <strong><?= $district_name; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Hostel Taluk: <strong><?= $taluk_name; ?></strong></p>
					</div>
				</div>
		    </div>
		</div>
		
		<hr>
			
		<div class="col-sm-12 ">
			<div class="mt-0 float-sm-left">
				<div class="row">
					
					<div class="col-sm-6">
						<p class="font-12">Hostel Gender Type: <strong><?= $gender_type; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Hostel Type: <strong><?= $hostel_type; ?></strong></p>
					</div>
					
					<div class="col-sm-6">
						<p class="font-12">Special Tahsildar: <strong><?= $special_tahsildar; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Assembly Constituency: <strong><?= assembly_constituency($assembly_const)[0]['assembly_const_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Parliment Constituency: <strong><?= parliment_constituency($parliment_const_name)[0]['parliament_const_name']; ?></strong></p>
					</div>
					
					<div class="col-sm-6">
						<p class="font-12">Hostel Location: <strong><?= $hostel_location; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Corporation: <strong><?= corporation($corporation)[0]['corporation_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Municipality: <strong><?= municipality($municipality)[0]['municipality_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Town Panchayat: <strong><?= town_panchayat($town_panchayat)[0]['town_panchayat_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Block: <strong><?= block($block)[0]['block_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Village: <strong><?= village_name($village_name)[0]['village_name']; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Year Of Establishment: <strong><?= $yob; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Sanctioned Strength: <strong><?= $sanctioned_strength; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Distance B/W PHP And Hostel: <strong><?= $distance_btw_phc; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">PHC Name: <strong><?= $phc_name; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Distance B/W Police Station and Hostel: <strong><?= $distance_btw_ps; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Police Station Name: <strong><?= $ps_name; ?></strong></p>
					</div>
					<div class="col-sm-6">
						<p class="font-12">Staff Count: <strong><?= $staff_count; ?></strong></p>
					</div>
				</div>	
			</div><!-- end col -->
		</div>
	</div>
</div>
