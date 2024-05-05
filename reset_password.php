<?php
session_start();
include "php/config.php";
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// Autoload Composer dependencies
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
  if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

    if ($password === $cpassword) {
      $enqPass = password_hash($password, PASSWORD_BCRYPT);

      $updateq = "UPDATE users SET password = '$enqPass' WHERE token = '$token'";
      $iqurey = mysqli_query($conn, $updateq);

      if ($iqurey) {
        $email_search = "SELECT * FROM users WHERE token = '$token'";
        $query = mysqli_query($conn, $email_search);

        if ($query) {
          $email_count = mysqli_num_rows($query);

          if ($email_count) {
            $user_mail = mysqli_fetch_assoc($query);
            $_SESSION['email'] = $user_mail['email'];

            $to_email = $_SESSION['email'];
            if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
              echo "Invalid email address.";
              exit;
            }

            // $subject = "Password Updated Successfully";
            // $domain = "http://tiny.desirestore.online";
            // $templatePath = "mail/Success.html";
            // $templateContent = file_get_contents($templatePath);

            // // Replace placeholders in the email template
            // $templateContent = str_replace("{username}", $user_mail['username'], $templateContent);
            // $templateContent = str_replace("{activation_link}", "$domain/sign_in.php", $templateContent);

            // $mail = new PHPMailer(true);

            // try {
            //   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            //   $mail->isSMTP(); //Send using SMTP
            //   $mail->Host = 'mail.desirestore.online'; //Set the SMTP server to send through
            //   $mail->SMTPAuth = true; //Enable SMTP authentication
            //   $mail->Username = 'info@desirestore.online'; //SMTP username
            //   $mail->Password = 'fakepassword2'; //SMTP password
            //   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            //   $mail->Port = 465;

            //   // Sender and recipient
            //   $mail->setFrom('info@desirestore.online', 'Desire Store');
            //   $mail->addAddress($to_email);

            //   // Email content
            //   $mail->isHTML(true);
            //   $mail->Subject = $subject;
            //   $mail->Body = $templateContent;

            //   // Send the email
            //   $mail->send();
            //   echo "Email successfully sent to $to_email... ";
            //   header('location:sign_in.php');
            // } catch (Exception $e) {
            //   echo "Email sending failed: " . $mail->ErrorInfo;

            // }
            header('location:index.php');
          } else {
            $_SESSION['passmsg'] = "User not found with the provided token.";
          
          }
        } else {
          echo "Query error: " . mysqli_error($conn);
        
        }
      } else {
        $_SESSION['passmsg'] = "Something went wrong while updating the password!";
      }
    } else {
      $_SESSION['passmsg'] = "Password is not Matching!!";
      
    }
  } else {
    $_SESSION['msg'] = "Token Not Found! Try Again";
    header('location:recover_email.php');
    
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset</title>
  <?php include 'pages/links.html'; ?>
  <link rel="stylesheet" href="source/navbar.css">
  <?php include 'pages/meta.html'; ?>
</head>

<body>
  <?php include 'pages/navbar.html'; ?>
  <section>
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-7 col-xl-6">
          <img src="source/forget_pass.jpg" class="img-fluid" alt="Recover Email">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Password Reset</p>
          <form method="post">
            <p class="bg-warning text-white px-1">
              <?php
              if (isset($_SESSION['passmsg'])) {
                echo $_SESSION['passmsg'];
              } else {

              }
              ?>
            </p>
            <div class="d-flex flex-row align-items-center mb-4">
              <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
              <div class="form-outline flex-fill mb-0 password-container">
                <input type="password" id="password" name="password" class="form-control" />
                <label class="form-label" for="form3Example4c">Password</label>
                <i class="fas fa-eye fa-lg toggle-password" id="eyeOpen"></i>
                <i class="fas fa-eye-slash fa-lg toggle-password" style="display:none" id="eyeClose"></i>
              </div>
            </div>



            <div class="d-flex flex-row align-items-center mb-4">
              <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
              <div class="form-outline flex-fill mb-0 password-container">
                <input type="password" id="cpassword" name="cpassword" class="form-control" />
                <label class="form-label" for="form3Example4c">Confirm Password</label>
              </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Reset Password</button>
            <div class="form-check d-flex justify-content-center mt-3">
              <label class="form-check-label" for="form2Example3">
                Don't Want to Change Password <a href="sign_in.php">Login Again</a>
              </label>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php include 'pages/footer.html'; ?>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js">
  </script>
  
</body>

</html>