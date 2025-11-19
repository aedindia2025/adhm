<?php


$host = "localhost";
$username = "root";
$password = "4/rb5sO2s3TpL4gu";
$databasename = "adi_dravidar";

$mysqli = new mysqli($host, $username, $password, $databasename);

include 'config/dbconfig.php';



$query = $mysqli->prepare("select s1_unique_id,c_no from std_app_s5 where c_no IS NOT NULL LIMIT 1");
$query->execute();
                
// Fetch the result
$result = $query->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {

$s1_unique_id = $row['s1_unique_id'];
    $communityno = $row['c_no'];
echo $s1_unique_id;
echo $communityno;die();
    $source = "AD welfare";
    $service_code = "REV-101";

    // API endpoint
    $url = 'https://tnedistrict.tn.gov.in/eda/getEsevaiResponse';

    // Create XML request
    $request = new SimpleXMLElement('<REQUEST></REQUEST>');
    $request->addChild('SOURCE', $source);
    $request->addChild('CERTIFICATENO', $communityno);
    $request->addChild('SERVICECODE', $service_code);
    $data_xml = $request->asXML();

    // Set headers
    $headers = array('Content-Type: application/xml');

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_xml);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT:!DH');
    curl_setopt($ch, CURLOPT_SSL_OPTIONS, CURLSSLOPT_ALLOW_BEAST);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($ch, CURLOPT_SSL_ENABLE_ALPN, false);

    // Execute API request
    $response = curl_exec($ch);
    echo $response;die();
    if (curl_errno($ch)) {
        echo json_encode(["status" => false, "error" => curl_error($ch)]);
        exit;
    }
    curl_close($ch);

    // Parse XML response
    $xml = simplexml_load_string($response);
    if (!$xml) {
        echo json_encode(["status" => false, "error" => "Invalid XML response"]);
        exit;
    }

    // Check if there is a message (means no record found)
    if (isset($xml->MSG)) {
        echo json_encode(["status" => false, "error" => "No record found"]);
        exit;
    }

    // Extract data
    $applicantName = isset($xml->APPLICANTNAME) ? (string)$xml->APPLICANTNAME : null;
    $applicantCaste = isset($xml->CAST) ? (string)$xml->CAST : null;
    $applicantFather = isset($xml->FATHERHUSNAME) ? (string)$xml->FATHERHUSNAME : null;
    $applicantCommunity = isset($xml->COMMUNITY) ? (string)$xml->COMMUNITY : null;
    $applicantAttachment = isset($xml->OUTPUTPDF) ? (string)$xml->OUTPUTPDF : null;
    $applicantAddress = isset($xml->ADDRESS) ? (string)$xml->ADDRESS : null;
    $applicantVillage = isset($xml->VILLTOWN) ? (string)$xml->VILLTOWN : null;
    $applicantTaluk = isset($xml->TALUK) ? (string)$xml->TALUK : null;
    $applicantDistrict = isset($xml->DISTRICT) ? (string)$xml->DISTRICT : null;
    $applicantPincode = isset($xml->PINCODE) ? (string)$xml->PINCODE : null;
    $applicantGender = isset($xml->GENDER) ? (string)$xml->GENDER : null;
    $applicantReligion = isset($xml->RELIGION) ? (string)$xml->RELIGION : null;
    $applicantSerial = isset($xml->SERIAL_NO) ? (string)$xml->SERIAL_NO : null;
    $applicantAuthority = isset($xml->ISSUINGAUTHORITY) ? (string)$xml->ISSUINGAUTHORITY : null;
    $applicantIssueDate = isset($xml->DATEOFISSUE) ? (string)$xml->DATEOFISSUE : null;
    $applicantExpiryDate = isset($xml->DATEOFEXPIRY) ? (string)$xml->DATEOFEXPIRY : null;
    $applicantCertificateNo = isset($xml->CERTIFICATENO) ? (string)$xml->CERTIFICATENO : null;
    $applicantAttachmentFile = isset($xml->ATTACHEMENT) ? (string)$xml->ATTACHEMENT : null;

  

    $stmt = $mysqli->prepare("INSERT INTO `community_certificate`(`unique_id`, `s1_unique_id`, `entry_date`, `applicant_name`, `father_name`, `address`, `village_town`, `taluk_name`, `district`, `pincode`, `gender`, `religion`, `community`, `caste`, `serial_no`, `issuing_authority`, `date_issue`, `date_expiry`, `certificate_no`, `attachment`, `output_pdf`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssssssssssssss",
        unique_id($prefix),
        $s1_unique_id,
        date('Y-m-d'),
        $applicantName,
        $applicantFather,
        $applicantAddress,
        $applicantVillage,
        $applicantTaluk,
        $applicantDistrict,
        $applicantPincode,
        $applicantGender,
        $applicantReligion,
        $applicantCommunity,
        $applicantCaste,
        $applicantSerial,
        $applicantAuthority,
        $applicantIssueDate,
        $applicantExpiryDate,
        $applicantCertificateNo,
        $applicantAttachmentFile,
        $applicantAttachment
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => true, "message" => "Data inserted successfully"]);
    } else {
        echo json_encode(["status" => false, "error" => "Failed to insert data"]);
    }

    $stmt->close();
    $mysqli->close();

}
}
   
