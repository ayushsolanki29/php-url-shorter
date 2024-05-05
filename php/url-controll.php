<?php
include "config.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You are Not Logged in Your account Please Login !";
    exit;
}

$full_url = $_POST['full_url'];
$user_id = $_SESSION['user_id'];

if (!empty($full_url) && filter_var($full_url, FILTER_VALIDATE_URL)) {
    $ran_url = substr(md5(microtime()), rand(0, 26), 5);
    
    $insertQuery = "INSERT INTO url (full_url, shorten_url, clicks, user_id) 
                    VALUES (?, ?, 0, ?)";
    
    $selectQuery = "SELECT shorten_url FROM url WHERE shorten_url = ?";

    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssi", $full_url, $ran_url, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        $stmtSelect = mysqli_prepare($conn, $selectQuery);
        mysqli_stmt_bind_param($stmtSelect, "s", $ran_url);
        mysqli_stmt_execute($stmtSelect);
        mysqli_stmt_bind_result($stmtSelect, $shorten_url);
        mysqli_stmt_fetch($stmtSelect);
        mysqli_stmt_close($stmtSelect);
        
        echo $shorten_url;
    } else {
        echo "Something went wrong. Please generate again!";
    }
} else {
    echo "$full_url - This is not a valid URL!";
}
?>
