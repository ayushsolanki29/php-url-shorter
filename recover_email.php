<?php
session_start();
include "php/config.php";
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// Autoload Composer dependencies
require 'vendor/autoload.php';
if (isset($_POST['submit'])) {

  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $_SESSION['email'] = $email;
  $emailquery = "SELECT * FROM users WHERE email='$email'";
  $equery = mysqli_query($conn, $emailquery);
  $emailcount = mysqli_num_rows($equery);

  if ($emailcount) {
    $_SESSION['msg'] = "Email Found";
    $userdata = mysqli_fetch_array($equery);
    $username = $userdata['username'];
    $token = $userdata['token'];

    if (isset($_POST['submit'])) {
      $to_email = $_SESSION['email'];
      if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit; // Stop execution if the email is invalid
      }
      //   $to_email = $_SESSION['email'];
      //   $subject = "Email Recover";
      //   $domain = "http://tiny.desirestore.online";
      //   $templatePath = "mail/forgetpass.html";
      //   $templateContent = file_get_contents($templatePath);

      //   $templateContent = str_replace("{username}", $username, $templateContent);
      //   $templateContent = str_replace("{resetpass_link}", "$domain/reset_password.php?token=$token", $templateContent);

      //   $sender_email = "From:info@desirestore.online";

      //   $mail = new PHPMailer(true); // Create a new PHPMailer instance

      //   try {
      //     // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      //     $mail->isSMTP();                                            //Send using SMTP
      //     $mail->Host       = 'mail.desirestore.online';                     //Set the SMTP server to send through
      //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      //     $mail->Username   = 'info@desirestore.online';                     //SMTP username
      //     $mail->Password   = 'fakepassword2';                               //SMTP password
      //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      //     $mail->Port       = 465;  

      //     // Sender and recipient
      //     $mail->setFrom('info@desirestore.online', 'Desire Store');
      //     $mail->addAddress($to_email);

      //     // Content
      //     $mail->isHTML(true); // Set email format to HTML
      //     $mail->Subject = $subject;
      //     $mail->Body = $templateContent;

      //     // Send the email
      //     $mail->send();
      //     $_SESSION['msg'] = "Check Your Mail to Reset Password in $email";
      //     echo "Email successfully sent to $to_email... ";
      //     header('location:sign_in.php');
      // } catch (Exception $e) {
      //     echo "Email sending failed: " . $mail->ErrorInfo;
      // }
      $_SESSION['msg'] = "Change Your Password";
      header("location: reset_password.php?token=$token");
    } else {
      $_SESSION['msg'] = "Something Went Wrong";
    }
  } else {
    $_SESSION['msg'] = "This Email Not Exist!";
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recover Email</title>
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
          <img src="source/recover_email.jpg" class="img-fluid" alt="Recover Email">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Email Recover</p>
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <p class="bg-info text-white p-2 mt-3 rounded px-1">
              <?php
              if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
              } else {
                echo $_SESSION['msg'] = "Please Type Email Carefully";
              }
              ?>
            </p>
            <div class="d-flex flex-row align-items-center mb-4">
              <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
              <div class="form-outline flex-fill mb-0 password-container">
                <input type="email" id="form1Example13" name="email" class="form-control form-control-lg" value="<?php if (isset($_COOKIE['emailcookie'])) {
                                                                                                                    echo $_COOKIE['emailcookie'];
                                                                                                                  } ?>" required />
                <label class="form-label" for="form3Example4c">Email</label>

              </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Send Mail</button>
            <div class="form-check d-flex justify-content-center mt-3">
              <label class="form-check-label" for="form2Example3">
                Ahh, I remember My password <a href="sign_in.php">Login Again</a>
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