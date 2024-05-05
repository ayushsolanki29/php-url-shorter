<?php
session_start();
include "php/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Autoload Composer dependencies
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {

  $username = mysqli_real_escape_string($conn, $_POST["username"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $password = mysqli_real_escape_string($conn, $_POST["password"]);
  $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

  $enqPass = password_hash($password, PASSWORD_BCRYPT);
  $enqCpass = password_hash($cpassword, PASSWORD_BCRYPT);

  $_SESSION['email'] = $email;

  $token = bin2hex(random_bytes(15));

  $emailquery = "SELECT * FROM users WHERE email='$email'";
  $equery = mysqli_query($conn, $emailquery);
  $emailcount = mysqli_num_rows($equery);

  $usernamequery = "SELECT * FROM users WHERE username='$username'";
  $uquery = mysqli_query($conn, $usernamequery);
  $usernameCount = mysqli_num_rows($uquery);
  $userprofile = "source/Profile/alt-pic.jpg";
  $userbio = "Hii There im New User Of desire Stores new Product";
  if ($usernameCount > 0) {
    $_SESSION['msg']  ="Username already exists!";
  } else if ($emailcount > 0) {
    $_SESSION['msg']  ="Email Already exists!";
  } else {
    if (!filter_var( $email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['msg']  ="Invalid Email";
      exit;
    } else if ($password === $cpassword) {
      $insertquery = "INSERT INTO users(username, email, password, cpassword,token,status,profile,bio) VALUES ('$username','$email','$enqPass','$enqCpass','$token','active','$userprofile','$userbio')";
      $iqurey = mysqli_query($conn, $insertquery);
      // if ($iqurey) {
      //   $to_email = $_SESSION['email'];


      //   $subject = "Email Activation";
      //   $domain = "https://tiny.desirestore.online";
      //   $templatePath = "mail/activation.html";
      //   $templateContent = file_get_contents($templatePath);

      //   $templateContent = str_replace("{username}", $username, $templateContent);
      //   $templateContent = str_replace("{activation_link}", "$domain/activate.php?token=$token", $templateContent);

      //   $sender_email = "From:info@desirestore.online";

      //   $mail = new PHPMailer(true); // Create a new PHPMailer instance

      //   try {
      //     // Server settings
      //     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      //     $mail->isSMTP(); //Send using SMTP
      //     $mail->Host = 'mail.desirestore.online'; //Set the SMTP server to send through
      //     $mail->SMTPAuth = true; //Enable SMTP authentication
      //     $mail->Username = 'info@desirestore.online'; //SMTP username
      //     $mail->Password = 'fakepassword2'; //SMTP password
      //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
      //     $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //     // Sender and recipient
      //     $mail->setFrom('info@desirestore.online', 'Desire Store');
      //     $mail->addAddress($to_email);

      //     // Content
      //     $mail->isHTML(true); // Set email format to HTML
      //     $mail->Subject = $subject;
      //     $mail->Body = $templateContent;

      //     // Send the email
      //     $mail->send();
      //     $_SESSION['msg'] = "Check Your Mail to Activate Your Account $email";
      //     echo "Email successfully sent to $to_email... ";
      //     header('location:sign_in.php');
      //   } catch (Exception $e) {
      //     echo "Email sending failed: " . $mail->ErrorInfo;
      //   }
      // } else {
      //   $_SESSION['msg']  ="Registion Faild.Try Again";
      // }
      $_SESSION['msg'] = "Budget constraints have led to a temporary suspension of our email system. We appreciate your understanding.";
      echo "Email successfully sent to $to_email... ";
      header('location:sign_in.php');
    } else {
      $_SESSION['msg']  ="Password Not Match!";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up</title>
  <?php include 'pages/links.html'; ?>
  <?php include 'pages/meta.html'; ?>
  <link rel="stylesheet" href="source/navbar.css">
</head>

<body>
  <?php include 'pages/navbar.html'; ?>

  <section style="background-color: #eee;">
    <div class="container">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                  <form class="mx-1 mx-md-4" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
                 
              <?php
              if (isset($_SESSION['msg'])) {
                ?>
                <p class="bg-success text-white p-2 mt-3 rounded px-1">
                <?php
                echo $_SESSION['msg'];

              } else {
                
              }
              ?>
            </p>
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="text" id="form3Example1c" name="username" class="form-control" />
                        <label class="form-label" for="form3Example1c">Your Username</label>
                      </div>
                    </div>

                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="email" id="form3Example3c" name="email" class="form-control" />
                        <label class="form-label" for="form3Example3c">Your Email</label>
                      </div>
                    </div>

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

                    <div class="form-check d-flex justify-content-center mb-5">
                      <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                      <label class="form-check-label" for="form2Example3">
                        I agree all statements in <a href="#!">Terms of service</a>
                      </label>
                    </div>

                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                      <button type="submit" name="submit" class="btn btn-primary btn-lg">Register</button>
                    </div>

                  </form>
                  <div class="form-check d-flex justify-content-center mb-5">
                    <label class="form-check-label" for="form2Example3">
                      Already have an Account? <a href="sign_in.php">Login</a>
                    </label>
                  </div>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                  <img src="source/draw1.png" class="img-fluid" alt="Sample image">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php include 'pages/footer.html'; ?>
  <script type="text/javascript" src="source/password.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js">
  </script>

</body>

</html>