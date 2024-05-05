<?php
session_start();
include 'php/config.php';
if (isset($_SESSION['user_id'])) {
    $userid = $_SESSION['user_id'];
    $selectId = "SELECT * FROM users WHERE id='$userid'";
    $query = mysqli_query($conn, $selectId);
    $user_profile = mysqli_fetch_assoc($query);
    $username = $user_profile['username'];
    $userid = $user_profile['id'];
    $useremail = $user_profile['email'];
    $userprofile = $user_profile['profile'];
    $userbio = $user_profile['bio'];
    }else{
        $_SESSION['msg'] = "Please Login For See Your Profile";
        header('location:sign_in.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_SESSION['$username']) ? $_SESSION['$username'] : "" ?>'s Profile</title>
</head>
<style>
    body {
        background: #eee;
    }
.header-pro{
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
        font-size: 14px;
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
    @media screen and (max-width:450px){
        .delete-account{
            display: none;
        }
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
    $update_profile_id ="";
    $update_profile_id = isset($_GET['user-id']);
        ?>
      <div class="container mt-5">
      <p class="bg-success text-white p-2 mt-3 text-center rounded px-1">
                        <?php
                        if (isset($_SESSION['profile-msg'])) {
                            echo $_SESSION['profile-msg'];
                        } else {
                            echo $_SESSION['profile-msg'] = "Your Profile";
                        }
                        ?>
                    </p>
        <div class="row d-flex justify-content-center">
            <div class="col-md-7">
                <div class="card p-3 py-4">
                    <div class="text-center">
                        <img src="<?php echo $userprofile?>" width="100"  alt="<?php echo $username?>"class="rounded-circle">
                   
                    </div>
            
                    <div class="text-center mt-3">
                        <span class="bg-secondary p-1 px-4 rounded text-white">ID :
                            <?php echo $userid; ?>
                        </span>
                        <h5 class="mt-2 mb-0">
                            <?php echo $username; ?>
                        </h5>
                        <span>
                            <?php echo $useremail; ?>
                        </span>
                        <div class="px-4 mt-1">
                            <p class="fonts"> <?php echo $userbio; ?></p>
                        </div>
                       
                        <form action="update-profile.php" method="post">
                        <div class="buttons">

                            <button type="button" class="btn btn-outline-primary px-4">Feed Back</button>
                            <button type="submit" class="btn btn-outline-primary px-4 ms-3">Edit Profile</button>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
<div class="float-end p-3 delete-account">
    <a href="">Delete Your Account</a>
</div>

    <?php include 'pages/footer.html'; ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js">
    </script>
</body>
</html>