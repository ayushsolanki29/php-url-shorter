<?php
session_start();
include 'php/config.php';
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $updateq = "update users set status='active' where token='$token'"; 
    $query = mysqli_query($conn,$updateq);
  
    if ($query) {
        if (isset($_SESSION['msg'])) {
            
           $_SESSION['msg']="Account Verifed Sucessfull";
           if (isset($_GET['admin'])) {
            header('location:admin.php');
            exit;
        }
              header('location:sign_in.php');   
        }else{
            $_SESSION['msg'] = "You are Logged out.";
            header('location:sign_in.php');
        }
    }else{
       
        $_SESSION['msg'] = "Account Verification unsucessfull";
        header('location:sign_up.php');

    }
}

?>