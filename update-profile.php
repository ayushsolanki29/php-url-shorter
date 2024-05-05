<?php
session_start();
include "php/config.php";

if (isset($_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
    $selectId = "SELECT * FROM users WHERE id='$userid'";
    $query = mysqli_query($conn, $selectId);
    $user_profile = mysqli_fetch_assoc($query);
    $username = $user_profile['username'];
    $userid = $user_profile['id'];
    $useremail = $user_profile['email'];
    $userprofile = $user_profile['profile'];
    $userbio =  $user_profile['bio'];

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
    
        $_SESSION['profile-msg'] = "Username Already Exists!";
    } else if ($emailCount > 0 && $useremail !== $up_useremail) {
        // User is trying to keep an existing email
        $_SESSION['profile-msg'] = "Email Already Exists!";
    } else {
        if ($up_userpassword === $up_usercpassword) {
            if (!filter_var($up_useremail, FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address.";
                exit;
            }else{
                $update = "UPDATE users SET username='$up_username', email='$up_useremail', profile='$userprofile', bio='$up_userbio' WHERE id='$userid'";
                $query = mysqli_query($conn, $update);
                if ($query) {
                    $_SESSION['profile-msg'] = "Profile Updated Successfull";
                    $_SESSION['$username'] = $up_username;
                    setcookie('emailcookie','',time()-86400);
                    setcookie('passwordcookie','',time()-86400);;
                    header("Location:profile.php");
                } else {
                    $_SESSION['profile-msg'] = "Profile Not Updated Something Went Wrong";
                }
            }
          
        } else {
            $_SESSION['profile-msg'] = "Password Not Match";
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_SESSION['$username']) ? $_SESSION['$username'] : "" ?>'s Update Profile</title>
    <style>
        body {
            background: #eee;
        }

        .header-pro {
            justify-content: center;
            text-align: center;
        }

        .card {
            border: none;

            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
        }

        .card:before {

            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background-color: #E1BEE7;
            transform: scaleY(1);
            transition: all 0.5s;
            transform-origin: bottom
        }

        .card:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background-color: #8E24AA;
            transform: scaleY(0);
            transition: all 0.5s;
            transform-origin: bottom
        }

        .card:hover::after {
            transform: scaleY(1);
        }


        .fonts {
            font-size: 11px;
        }

        .social-list {
            display: flex;
            list-style: none;
            justify-content: center;
            padding: 0;
        }

        .social-list li {
            padding: 10px;
            color: #8E24AA;
            font-size: 19px;
        }


        .buttons button:nth-child(1) {
            border: 1px solid #8E24AA !important;
            color: #8E24AA;
            height: 40px;
        }

        .buttons button:nth-child(1):hover {
            border: 1px solid #8E24AA !important;
            color: #fff;
            height: 40px;
            background-color: #8E24AA;
        }

        .buttons button:nth-child(2) {
            border: 1px solid #8E24AA !important;
            background-color: #8E24AA;
            color: #fff;
            height: 40px;
        }
    </style>
    <?php include 'pages/links.html'; ?>
    <link rel="stylesheet" href="source/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include 'pages/meta.html'; ?>
</head>

<body>
    <?php include 'pages/navbar.html'; ?>

    <?php
    ?>

    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card p-3 py-4">
                    <div class="row d-flex header-pro justify-content-between align-items-center">
                        <div class="col pb-4">
                            <span class="bg-info p-1 px-4 rounded text-white">User ID:
                                <?php echo $userid; ?>
                            </span>
                        </div>
                        <div class="position-relative">
                            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"
                                enctype="multipart/form-data" method="post">
                                <div class="col text-center ">
                                    <img src="<?php echo $userprofile; ?>" width="100" class="rounded-circle"
                                        alt="<?php echo $username; ?>">
                                        <label class="form-label  mt-2" for="customFile">Choose Your Profile Picture</label>
<input type="file" class="form-control mt-3" name="profile" id="customFile" />
                                    <br>
                                    <button name="change" type="submit"
                                        class="bg-secondary p-1 px-4 rounded text-white">Upload</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <p class="bg-success text-white p-2 mt-3 text-center rounded px-1">
                        <?php
                        if (isset($_SESSION['profile-msg'])) {
                            echo $_SESSION['profile-msg'];
                        } else {
                            echo $_SESSION['profile-msg'] = "Update Your Profile";
                        }
                        ?>
                    </p>
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="text-center mt-3">

                            <div class="d-flex flex-row align-items-center mb-4">
                                <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                <div class="form-outline flex-fill mb-0 password-container">
                                    <input type="text" id="form1Example13" name="username"
                                        value="<?php echo $username; ?>" class="form-control form-control-lg"
                                        required />
                                    <label class="form-label" for="form1Example13">Username</label>
                                </div>
                            </div>

                            <div class="d-flex flex-row align-items-center mb-4">
                                <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                <div class="form-outline flex-fill mb-0 password-container">
                                    <input type="email" id="form1Example13" name="email"
                                        value="<?php echo $useremail; ?>" class="form-control form-control-lg"
                                        required />
                                    <label class="form-label" for="form1Example13">Email</label>
                                </div>
                            </div>

                            <div class="d-flex flex-row align-items-center mb-4">
                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                <div class="form-outline flex-fill mb-0 password-container">
                                    <input type="password" id="password"  name="password"
                                        class="form-control form-control-lg" value="<?php if (isset($_COOKIE['emailcookie'])) {
                                            echo $_COOKIE['passwordcookie'];
                                        } ?>" autocomplete="true" required />
                                    <label class="form-label" for="password">Password</label>
                                    <i class="fas fa-eye fa-lg toggle-password" id="eyeIcon"></i>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center mb-4">
                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                <div class="form-outline flex-fill mb-0 password-container">
                                    <input type="password"id="cpassword" name="cpassword" value="<?php if (isset($_COOKIE['emailcookie'])) {
                                        echo $_COOKIE['passwordcookie'];
                                    } ?>" class="form-control" />
                                    <label class="form-label" for="form3Example4c">Confirm Password</label>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center mb-4">
                                <i class="fas fa-pen fa-lg me-3 fa-fw"></i>
                                <div class="form-outline flex-fill mb-0 password-container">
                                    <input type="text" id="form1Example13" name="about" value="<?php echo $userbio; ?>"
                                        class="form-control form-control-lg" required />
                                    <label class="form-label" for="form1Example13">About You</label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                <button type="submit" name="Update" class="btn btn-primary btn-lg">Update
                                    Profile</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php include 'pages/footer.html'; ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passwordInput = document.querySelector("#password");
            const eyeIcon = document.getElementById("eyeIcon");

            eyeIcon.addEventListener("click", function () {
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