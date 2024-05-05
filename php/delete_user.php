<?php
include "config.php";

$userid = $_GET['delete-user'];

// Fetch URLs associated with the user
$selecturls = "SELECT id FROM url WHERE user_id = $userid";
$urlsquery = mysqli_query($conn, $selecturls);

if (!$urlsquery) {
    echo "Error fetching URLs: " . mysqli_error($conn);
    exit;
}

// Delete associated URLs first
while ($urlData = mysqli_fetch_array($urlsquery)) {
    $urlid = $urlData['id'];
    $urldelete = "DELETE FROM url WHERE id = $urlid";
    $urlquery = mysqli_query($conn, $urldelete);

    if (!$urlquery) {
        echo "Error deleting URL: " . mysqli_error($conn);
        exit;
    }
}

// Delete user after the URLs are deleted
$userdelete = "DELETE FROM users WHERE id = $userid";
$userquery = mysqli_query($conn, $userdelete);

if (!$userquery) {
    echo "Error deleting user: " . mysqli_error($conn);
    exit;
}

header('location:../admin.php');

?>
