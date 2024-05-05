<?php
session_start();
include "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Autoload Composer dependencies
require '../vendor/autoload.php';

if (isset($_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
    $selectId = "SELECT * FROM users WHERE id='$userid'";
    $query = mysqli_query($conn, $selectId);
    $user_profile = mysqli_fetch_assoc($query);
    $username = $user_profile['username'];
    $userid = $user_profile['id'];
    $useremail = $user_profile['email'];
    $userprofile = $user_profile['profile'];
    $userbio = "I'm New User Of Swift URLs";


} else {
    $_SESSION['msg'] = "Please Login For See Your Profile";
    header('location:sign_in.php');
}
if (isset($_POST['Update'])) {
    $up_username = $_POST['username'];
    $up_useremail = $_POST['email'];
    $up_userpassword = $_POST['password'];
    $up_usercpassword = $_POST['cpassword'];
    $up_userbio = $_POST['about'];

    $_SESSION['email'] = $up_useremail;
    $enqPass = password_hash($up_userpassword, PASSWORD_BCRYPT);
    $enqCpass = password_hash($up_usercpassword, PASSWORD_BCRYPT);

    $emailquery = "SELECT * FROM users WHERE email='$up_useremail'";
    $equery = mysqli_query($conn, $emailquery);
    $emailCount = mysqli_num_rows($equery);
    $usernamequery = "SELECT * FROM users WHERE username='$up_username'";
    $uquery = mysqli_query($conn, $usernamequery);
    $usernameCount = mysqli_num_rows($uquery);


    if ($usernameCount > 0 && $username !== $up_username) {
        // User is trying to keep an existing username
        $_SESSION['profile-msg'] = "Username Already Exists!";
    } else if ($emailCount > 0 && $useremail !== $up_useremail) {
        // User is trying to keep an existing email
        $_SESSION['profile-msg'] = "Email Already Exists!";
    } else {
        if ($up_userpassword === $up_usercpassword) {
            $update = "UPDATE users SET username='$up_username', email='$up_useremail',profile='$userprofile',bio='$up_userbio' where user_id='$userid'";
            $iqurey = mysqli_query($conn, $update);
            if ($iqurey) {
                $to_email = $_SESSION['email'];
                if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
                    echo "Invalid email address.";
                    exit;
                }
                $subject = "Email Activation";
                $domain = "https://tiny.desirestore.online";
                $templatePath = "mail/activation.html";
                $templateContent = file_get_contents($templatePath);

                $templateContent = str_replace("{username}", $username, $templateContent);
                $templateContent = str_replace("{activation_link}", "$domain/activate.php?token=$token", $templateContent);

                $sender_email = "From:info@desirestore.online";

                $mail = new PHPMailer(true); // Create a new PHPMailer instance

                try {
                    // Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP(); //Send using SMTP
                    $mail->Host = 'mail.desirestore.online'; //Set the SMTP server to send through
                    $mail->SMTPAuth = true; //Enable SMTP authentication
                    $mail->Username = 'info@desirestore.online'; //SMTP username
                    $mail->Password = 'fakepassword2'; //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                    $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    // Sender and recipient
                    $mail->setFrom('info@desirestore.online', 'Desire Store');
                    $mail->addAddress($to_email);

                    // Content
                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body = $templateContent;

                    // Send the email
                    $mail->send();
                    $_SESSION['msg'] = "Check Your Mail to Activate Your Account $email";
                    echo "Email successfully sent to $to_email... ";

                } catch (Exception $e) {
                    echo "Email sending failed: " . $mail->ErrorInfo;
                }
            } else {
                echo "<script>alert('Something went wrong!');</script>";
            }
        } else {
            echo "<script>alert('Form not submitted.');</script>";
        }
    }
}



if (isset($_POST['change'])) {

$file = $_FILES['profile'];
$filename = $file['name'];
$filepath = $file['tmp_name'];
$fileerror = $file['error'];

$file_ext = explode('.', $filename);
$file_ext_check = strtolower(end($file_ext));
$file_name_check = ltrim($file_ext_check, '.');
$valid_file_ext = array('png', 'jpg', 'jpeg');

$new_filename = $username . '.' . $file_ext_check;

if ($fileerror == 0) {
    if (in_array($file_ext_check, $valid_file_ext)) {
        $destfile = 'source/Profile/' . $new_filename;
        move_uploaded_file($filepath, $destfile);
        $update = "UPDATE `users` SET profile='$destfile' where id='$userid'";
        $result = mysqli_query($conn, $update);
        if ($result) {
            header("Location: update-profile.php");
            $_SESSION['profile-msg'] = "Profile pic uploaded";
        } else {
            $_SESSION['profile-msg'] = "Upload Faild";
        }
    } else {
        $_SESSION['profile-msg'] = "only supported Extension is JPG,PNG,JPEG. ";
    }
} else {
    $_SESSION['profile-msg'] = "Something went wrong ";
}
}
?>