<?php include 'header.php'; ?>
<?php
// $cookie_options = array(
//     'expires' => time() + 60*60*24*30,
//     'path' => '/',
//     'domain' => '', // leading dot for compatibility or use subdomain
//     'secure' => true, // or false
//     'httponly' => false, // or false
//     'samesite' => 'Strict' // None || Lax || Strict
//   );
  
//   setcookie('cors-cookie', 'my-site-cookie', $cookie_options);

$url = $_SERVER['REQUEST_URI'];

// Parse the URL using parse_url
$parsedUrl = parse_url($url);

// Extract the path from the parsed URL
$path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

// Remove the leading slash from the path (if present)
$path = ltrim($path, '/');

// Explode the path into an array of segments
$pathSegments = explode('/', $path);

// Get the second segment (assuming the folder name is the second segment)
if (isset($pathSegments[1])) {
    // Get the folder name as the second segment
    $folderName = $pathSegments[1];

    // echo $folderName;

    // Check if the session variable is set and not empty
    if (isset($_SESSION['root_folderName']) && $_SESSION['root_folderName'] !== '') {
        // Check if the folder name and session root folder name are not equal
        if ($_SESSION['root_folderName'] !== $folderName) {
            // session_destroy();
            // Redirect to logout.php or handle the mismatch case
            $logoutUrl = '../'.$_SESSION['root_folderName'].'/logout.php';

            // Redirect the user to the logout URL with a header
            header("Location: $logoutUrl");
            exit;
        }
    }
} else {
    // Handle the case where there is no second segment in the URL if needed
}
?>

<?php
include 'config/dbconfig.php';

$table = 'content_management';

$columns = [
    'ambedkar_quotes',
    'thirukkural',
    'cm_image',
    'ambedkar_image',
];

$table_details = [
    $table,
    $columns,
];

$where = 'is_delete = 0 and is_active = 1';
$result_values = $pdo->select($table_details, $where);

if ($result_values->status) {
    $result_values = $result_values->data;
    $ambedkar_quotes = $result_values[0]['ambedkar_quotes'];
    $thirukkural = $result_values[0]['thirukkural'];
    $ambedkar_image = $result_values[0]['ambedkar_image'];
    $cm_image = $result_values[0]['cm_image'];
}

    // Splitting thirukkural into words
    $words = preg_split('/\s+/', $thirukkural);

    // Forming two lines with specified word counts
    $kuralline1 = implode(' ', array_slice($words, 0, 4));

    $kuralline2 = implode(' ', array_slice($words, 4, 3));
// $active_status_options   = active_status($is_active);

// echo $ambedkar_image;

?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Anek+Tamil:wght@100..800&family=Arima:wght@100..700&family=Kavivanar&display=swap"
    rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<style>
    article {
        max-width: 96.5%;
        margin: 0px 0px 0px 13px;
        overflow: hidden;
        position: relative;
        padding: 20px 0px;
        line-height: 1px;
    }

    section.early {
        background: #f1f1f1a6;
        border-radius: 50px;
		border: 1px solid #ede8e8;
    }

    section.early p {
        margin-bottom: 0px;
        font-size: 16px;
    }

    .example-left {
        white-space: nowrap;
        position: absolute;

    }

    .example-left {
        -webkit-animation: mymove 8s linear infinite;
        /* Safari 4.0 - 8.0 */
        white-space: nowrap;
        animation: mymove 8s linear infinite alternate;
    }

    /* Safari 4.0 - 8.0 */
    @-webkit-keyframes mymove {
        from {
            left: 0;
        }

        to {
            left: -140px;
        }
    }

    @keyframes mymove {
        from {
            left: 0;
        }

        to {
            left: -140px;
        }
    }

    span.button-news {
        z-index: 999999;
        position: relative;
        padding: 7px 10px;
        border-radius: 50px;
        background: linear-gradient(to right, #25bff9, #0890c3);
        color: #fff;
        font-weight: bolder;
        font-size: 12px;
        text-transform: uppercase;
    }


    .ic-top h3 {
        font-weight: 700;
        color: #fff;
        margin-top: 0px;
        font-size: 17px;
        text-transform: uppercase;
        border-bottom: 1px dashed #ccc;
        width: 60%;
        text-align: center;
        display: inline-block;
    }

    .quote img {
        width: 135px;
        text-align: center;
        border-radius: 50%;
        height: 139px;
        border: 2px solid #fff;
        padding: 3px;
    }

    .quote {
        background: linear-gradient(to right, #25bff9, #0890c3);
        text-align: center;
        padding: 17px;
        border-radius: 7px;
        height: 100%;

    }

    .inner-caption p {
        font-optical-sizing: auto;
        font-weight: 600;
        font-style: normal;
        font-family: "Anek Tamil", sans-serif;
        line-height: 21px;
        margin-bottom: 0px;
        color: #fff;
        font-size: 13px;
    }

    .blue-bg {


        background-size: contain;
        background-position: right;
    }

    .inner-caption {
        margin-top: 18px;
    }

    .cm img {
        width: 100%;
        border: 2px solid #0d98cc;
        padding: 3px;
        border-radius: 6px;
    }

    .cm {
        text-align: end;
    }

    .heder-part {
        background: #e0f6ff;
        padding: 30px 30px 20px;
        -webkit-box-shadow: 0 0 10px rgb(231 227 227 / 42%);
        background-image: url(img/new-form2.jpg);
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }

    ul.new li {
        margin: 8px;
        width: 100%;
    }

    ul.new {
        list-style: none;
        display: flex;
        width: 100%;
        padding-left: 0px;
        margin-bottom: 0px;
    }

    .box {
        background: #fbf9f9;
        box-shadow: 0px 0px 35px 0px rgba(154, 161, 171, 0.15);
        text-align: center;
        padding: 20px 10px;
        height: 100%;
        border: 1px solid #ccc;
        border-radius: 2px;
    }

    .box:hover {
        background: #f1f1f1;
    }

    a.a-btn {
        background: linear-gradient(to right, #25bff9, #0890c3);
        padding: 5px 22px;
        color: #fff;
        font-weight: 500;
        font-size: 15px;
        border-radius: 5px;
        font-family: "Poppins", sans-serif;
    }

    a.a-btn:hover {
        background: linear-gradient(to right, #0890c3, #25bff9);
    }

    .box h5 {
        color: #3c3a3a;
        padding: 0px 0px 10px;
        font-size: 15px;
        font-weight: 600;
        font-family: "Poppins", sans-serif;
        margin-top: 0px;
    }

    .box h5 span {
        color: #7c7b7b;
        font-size: 13px;
    }

    .container-fluid {
        max-width: 1240px;
    }

    .spe-h5 {
        margin-top: 6px !important;
        margin-bottom: 19px;
    }

    .box i {
        font-size: 45px;

        color: #845adf;

    }

    .box.c-1 i {

        color: #23b7e5;
    }

    .box.c-2 i {

        color: #26bf94;
    }

    .box.c-3 i {

        color: #f5b849;
    }

    .box.c-4 i {

        color: #e791bc;
    }

    .overall a button {
        font-size: 17px;
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        color: #0b95c8;
    }

    .app-1 img {
        width: 49%;
        margin-bottom: 8px;
    }

    .overall a button i {
        font-size: 22px;
        vertical-align: middle;
    }

    .app-1 {
        background: #ffffff;
        border-radius: 4px;
        border: 1px solid #0d98cc5e;
        box-shadow: 0px 0px 35px 0px rgba(154, 161, 171, 0.15);
    }
	p.a-quote {
    font-size: 15px;
    font-family: "Poppins", sans-serif;
}
.d img{
	width: 42%;
    margin-top: 9px;
}
</style>
<div class="container-fluid">
    <div class=" mt-2">
        <div class="row ">
            <div class="col-md-4">
                <div class="ad-logo">
                    <a href="index.php"><img src="img/ad-logo.png"></a>
                </div>
            </div>
            <div class="col-md-8 align-self-center text-end ">
                <section class="early">
                    <article>
                        <p class="example-left" style="color : red">To apply for hostel click the Student Application
                            tab below.</p>
                        <span class="button-news">Latest News</span>
                    </article>

                </section>

            </div>
        </div>

        <div class="heder-part mt-2">

            <div class="row">
                <div class="col-md-5 mt-mb">
                    <div class="quote">
                        <div class="row">
                            <div class="col-md-4">
                            <?php
                                if ($_SESSION['staff_image'] != '') {
                                    // If $_SESSION['staff_image'] is set and not empty, display the image
                                    echo '<img src="adhm/adhmAdmin/uploads/image_uplode/ambethkar/'.$ambedkar_image.'" alt="user-image" width="32" class="rounded-circle">';
                                } else {
                                    // If $_SESSION['staff_image'] is not set or empty, display the commented code
                                    echo ' <img src="img/AM-1.jpg">';
                                }
?>
                                
                            </div>
                            <div class="col-md-8 align-self-center">
                                <div class="tb-caption text-center">
                                    <div class="inner-caption">
                                        <div class="ic-top">
                                            <h3>Dr. அம்பேத்கர்</h3>
                                        </div>
                                        <p class="a-quote"><?php echo $ambedkar_quotes; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 mt-mb">
                    <div class="quote">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="img/AM-2.jpg">
                            </div>
                            <div class="col-md-8 align-self-center">
                                <div class="tb-caption text-center">
                                    <div class="inner-caption">
                                        <div class="ic-top">

                                            <h3>திருக்குறள்</h3>
                                        </div>
                                        <p><?php echo $kuralline1; ?><br><?php echo $kuralline2; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 cm align-self-center">
                    <img src="img/cm.jpg">
                </div>

            </div>



            <div class="row mt-3">
                <div class="col-md-12">
                    <ul class="new">
                        <li>
                            <div class="box">
                                <i class="mdi mdi-school-outline"></i>
                                <h5 class="spe-h5">Student </h5>
                                <a href="adhmStudent/index.php" class="a-btn">Login</a>
                            </div>

                        </li>
                        <li>
                            <div class="box c-1">
                                <i class="mdi mdi-office-building-marker-outline"></i>
                                <h5 class="spe-h5">Hostel Warden</h5>
                                <a href="adhmHostel/index.php" class="a-btn">Login</a>
                            </div>

                        </li>
                        <li>
                            <div class="box c-2">
                                <i class="mdi mdi-account-tie-hat-outline"></i>
                                <h5 class="spe-h5">Special Tahsildar </h5>
                                <a href="adhmSt/index.php" class="a-btn">Login</a>
                            </div>

                        </li>
                        <li>
                            <div class="box c-3">
                                <i class="mdi mdi-map-check-outline"></i>
                                <h5>DADWO <br><span>Officer District Level</span> </h5>
                                <a href="adhmDADWO/index.php" class="a-btn">Login</a>
                            </div>

                        </li>
                        <li>
                            <div class="box c-4">
                                <i class="mdi mdi-account-group-outline"></i>
                                <h5>HO <br><span>Director &amp; Dept. Secretariat</span></h5>
                                <a href="adhmAdmin/index.php" class="a-btn">Login</a>
                            </div>

                        </li>

                    </ul>
                </div>
            </div>




        </div>

    </div>
    <div class="overall">
        <div class="row text-center mt-3 mb-3">
            <div class="col-md-3 mt-mb">
                <a href="find_reg_no.php">
                    <div class="app-1 d">
                        <img src="img/find-reg2.png"><br>
                        <button type="button" class="btn "> <span> Know Your Student Login ID</span> </button>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mt-mb"> 
                <a href="form_otp.php"> 
                <!-- <a href="#"> -->
                    <div class="app-1">
                        <img src="img/Application-1.png"><br>
                        <button type="button" class="btn "> <span> Student Application</span> </button>
                    </div>
                </a>
            </div>
	     <div class="col-md-3">
                <a href="app_download.php">
                    <div class="app-1">
                        <img src="img/Application-3.png"><br>
                        <button type="button" class="btn" > <span> Application Download</span> </button>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mt-mb">
                <a href="app_status.php">
                    <div class="app-1">
                        <img src="img/Application-2.png"><br>
                        <button type="button" class="btn "> <span> Application Status</span> </button>
                    </div>
                </a>
            </div>
			 
           
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>

// document.addEventListener('contextmenu', function(event) {
//     event.preventDefault();
//               });

//               document.onkeydown = function(e)
//     {
//         if(event.keyCode == 123)
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
//         {
//             return false;
//         }
//     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
//     {
//       return false;
//     }
//    }
    function openExternalWindow() {

        window.open('external_page.php', '_blank', 'width=1000,height=700');
    }
</script>
