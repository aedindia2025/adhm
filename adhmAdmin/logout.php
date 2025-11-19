<script>
    alert("Your Session was timed Out! Please Login Again");
</script>

<?php
    session_start();

    include_once 'config/dbconfig.php';

    $user_id =  $_SESSION['sess_user_id'];

    $where = [
        'user_id' => $user_id,
    ];

   // $delete_result = $pdo->delete('active_sessions', $where);

    
    session_destroy();

    header("Location: index.php");
?>

