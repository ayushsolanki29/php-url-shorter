<?php 
    $domain = "http://localhost/TinyURL/"; 
    $host = "localhost";
    $user = "root"; //Database username
    $pass = ""; //Database password
     $db = "url_shorter"; //Database name

    // $domain = "tiny.000.pe";
    // $host = "localhost"; // Use the IP address as the host name
    // $user = "id21654500_ayush2";
    // $pass = "Ayush2901@";
    // $db = "id21654500_url"; // Replace with your actual database name

    $conn = mysqli_connect($host, $user, $pass, $db);
    if(!$conn){
        echo "Database connection error".mysqli_connect_error();
    }
?>
<?php
$admin_pass = "admin";
date_default_timezone_set("Asia/Kolkata"); 
$current_hour = date("h");
$current_minute = date("i");
$last_two_digits_hour = substr($current_hour, -2);
$last_two_digits_minute = substr($current_minute, -2);
$admin_pass .= $last_two_digits_hour . $last_two_digits_minute;

$admin_pass; 
?>