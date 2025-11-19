
<!DOCTYPE html>
<html lang="en">
<?php include 'config/dbconfig.php'; ?>
<?php //include 'config/common_fun.php';?>

<?php
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
    $folderName = $pathSegments[0];

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
    // Handle the case where there is no second segment in the URL if needed
}
?>

<?php 
  $user_id      = "";
  if (isset($_SESSION['user_id'])) {
    $user_id      = $_SESSION['user_id'];

    if (isset($_SESSION['LAST_ACTIVITY']) && ((time() - $_SESSION['LAST_ACTIVITY']) > 3600)) {
        // last request was more than 1 hour ago
        //session_unset();     // unset $_SESSION variable for the run-time 
        //session_destroy();   // destroy session data in storage
    ?>
    
    <?php
        header("Location: logout.php");
    }

    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  }
?>

<?php 
    
  if (session_id() AND ($user_id)) { 
      // permission_check();
  ?>
  <?php include 'inc/header.php' ?>


<body>


  <?php include 'body.php'; ?>

</body>

 <?php
    } else {
    // LOGIN PAGE 
    $folder_name_org = "login";
?>

    <?php include "folders/login/login.php";?>

 <?php
    }

?>


</html>
<script type="text/javascript" src="<?php echo 'folders/login/login.js'?>"></script>
<script>
    document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
              });

              document.onkeydown = function(e)
    {
        if(event.keyCode == 123)
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
        {
            return false;
        }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
    {
      return false;
    }
    }
</script>
      <!-- Main Content -->
 </html>