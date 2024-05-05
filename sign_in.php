<?php
session_start();
include "php/config.php";

if (isset($_COOKIE['emailcookie']) && isset($_COOKIE['passwordcookie'])) {
  $rememberedEmail = $_COOKIE['emailcookie'];
  $rememberedPassword = $_COOKIE['passwordcookie'];
} else {
  $rememberedEmail = '';
  $rememberedPassword = '';
}

if (isset($_POST['submit'])) {
  $username = $_POST["email"];
  $password = $_POST["password"];
  $rememberMe = isset($_POST['rememberme']);

  $email_search = "SELECT * FROM users WHERE email='$username' AND status='active' ";
  $query = mysqli_query($conn, $email_search);
  $email_count = mysqli_num_rows($query);
  if ($email_count) {
    $email_pass = mysqli_fetch_assoc($query);
    $db_pass = $email_pass['password'];
    $_SESSION['$username'] = $email_pass['username'];
    $pass_decode = password_verify($password, $db_pass);
    if ($pass_decode) {
      if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
        $insertQuery = "INSERT INTO rememberme_tokens (user_id, token, expiration) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        if (!$stmt) {
            die("Error: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sss", $email_pass['id'], $token, $expiration);
        if (mysqli_stmt_execute($stmt)) {
            setcookie('rememberme', $token, strtotime('+30 days'));
        } else {
            echo "<script>alert(\"There was an error while logging in.\")</script>";
        }
    }
  
      $_SESSION['user_id'] = $email_pass['id'];
      header("Location: index.php");
      exit; 
    } else {
      $_SESSION['msg'] = "Password incorrect!!";
    }
  } else {
    $_SESSION['msg'] = "Email not Registered, Please Register this Email " . $username;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
          <img src="source/login.png" class="img-fluid" alt="Phone image">
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign in</p>
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <p class="bg-success text-white p-2 mt-3 rounded px-1">
              <?php
              if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];

              } else {
                echo $_SESSION['msg'] = "You are Logout! Please Login again.";
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

           
            <div class="d-flex flex-row align-items-center mb-4">
  <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
  <div class="form-outline flex-fill mb-0 password-container">
  <input type="password" id="password" name="password" class="form-control form-control-lg" value="<?php if (isset($_COOKIE['emailcookie'])) {
                  echo $_COOKIE['passwordcookie'];
                } ?>" autocomplete="true" required />
    <label class="form-label" for="password">Password</label>
    <i class="fas fa-eye fa-lg toggle-password" id="eyeIcon"></i>
  </div>
</div>
<div class="d-flex flex-row align-items-center mb-4 d-none">
              <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
              <div class="form-outline flex-fill mb-0 password-container">
                <input type="password" id="cpassword" name="cpassword" class="form-control" />
                <label class="form-label" for="form3Example4c">Confirm Password</label>
              </div>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-4">

              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="rememberme" id="form1Example3" checked />
                <label class="form-check-label" for="form1Example3"> Remember me </label>
              </div>
              <a href="recover_email.php">Forgot password?</a>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
            <div class="form-check d-flex justify-content-center mt-3">
              <label class="form-check-label" for="form2Example3">
                Don't have an Account ? <a href="sign_up.php">Register</a>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const passwordInput = document.querySelector("#password");
    const eyeIcon = document.getElementById("eyeIcon");
    
    eyeIcon.addEventListener("click", function() {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
      }
    });
  });
</script>
</body>

</html>