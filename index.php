<?php
session_start();
include "php/config.php";
$new_url = "";
if (isset($_GET)) {
    foreach ($_GET as $key => $val) {
        $u = mysqli_real_escape_string($conn, $key);
        $new_url = str_replace('/', '', $u);
    }
    $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
    if (mysqli_num_rows($sql) > 0) {
        $sqlP = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
        if ($sqlP) {
            $full_url = mysqli_fetch_assoc($sql);
            header("Location:" . $full_url['full_url']);
        }
    }
}
?>
<?php
if (isset($_GET['admin']) ) {
    $admin_in_pass = $_GET['admin'];
    if( $admin_in_pass == $admin_pass){
        echo "<script>alert('Access Granted!!');</script>";
        $token = bin2hex(random_bytes(15));
        $_SESSION["admin"] =  $token;
        $href_admin = "admin.php?login=true&password=$token" ;
        echo "<script>window.location.href = '$href_admin';</script>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <meta charset="UTF-8">
    <title> Swift Link: Your Fast, Editable URL Shortener</title>
    <link rel="stylesheet" href="source/style.css">
    <link rel="stylesheet" href="source/navbar.css">
    <?php include 'pages/meta.html'; ?>
    <!-- Iconsout Link for Icons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
</head>
<style>
     @media (width < 420px) {
        .btn-mob:hover{
    background-color: var( --secoundry-color);
    
  }
     }
      .notification{
    position: fixed;
    bottom: 20px;
    left: 10px;
    width: max-content;
    padding: 10px 15px;
    border-radius: 4px;
    background-color: #141619;
    color: #f6f5f9;
    box-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);
    transform: translateY(30px);
    opacity: 0;
    visibility:hidden;
    animation: fade-in 4s linear forwards;
    z-index: 1000;
  }
.notification a{
    color: white;
}
  .notification-progress{
    position: absolute;
    left: 5px;
    bottom: 5px;
    width: calc(100% - 10px);
    transform: scaleX(0);
    height: 3px;
    transform-origin: left;
    background-image: linear-gradient( to right , #539bdb, #3250bf);
    border-radius: inherit;
    animation:load 3s 0.25s linear forwards;
  }@keyframes fade-in {
    5%{
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
  
    90%{
      opacity: 1;
      transform: translateY(0);
    }
  }
  @keyframes load {
    to{
      transform: scaleX(1);
    }
  }
        .table {
            width: 100%;
            border-collapse: collapse;

        }

        .table td,
        .table th {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }

        .table th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .table td {
            word-break: break-all;
            /* Add this line */
        }

        @media (max-width: 500px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                text-align: right;
                position: relative;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
            }

            .action-icon {
                font-size: 24px;
                padding: 0 12px;
            }
        }

        .action-icon {
            font-size: 20px;
            margin: 2px;

        }
    </style>
<body>
<?php include 'pages/navbar.html' ?>
<?php
if (isset($_SESSION['$username'])) {
    ?>
    <div class="notification">
    <a href="profile.php"><p class="text-white">  <?php echo  "Welcome ". $_SESSION['$username'] ?> </p></a>
        <div class="notification-progress"></div>
      </div> 
      <?php
}
?>

    <div class="hero">
        <div class="left">

            <h3>Hello, <span class="user">
                    <?php echo isset($_SESSION['$username']) ? $_SESSION['$username'] : "user" ?>
                </span></h3>
            <h1>Transforming Long URLs into <span id="home"></span>.</h1>
            <p>subscribe for More Details.</p>
        </div>

    </div>
    <div class="container">
        <div class="wrapper">
            <form action="#" autocomplete="off">
                <input type="text" spellcheck="false" name="full_url" placeholder="Enter or paste a long url" autofocus required>
                <i class="url-icon uil uil-link"></i>
                <button>Shorten</button>
            </form>

            <?php
            if (isset($_SESSION['user_id'])) {
                $user_id = ($_SESSION['user_id']);
            } else {
                $user_id = "";
            }
            $sql2 = mysqli_query($conn, "SELECT * FROM url WHERE user_id = '$user_id' ORDER BY id DESC");
            if (mysqli_num_rows($sql2) > 0) {
                ?>
                <div class="statistics">
                    <?php
                    $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url WHERE user_id = '$user_id'");
                    $res = mysqli_fetch_assoc($sql3);

                    $sql4 = mysqli_query($conn, "SELECT clicks FROM url WHERE user_id = '$user_id'");
                    $total = 0;
                    while ($count = mysqli_fetch_assoc($sql4)) {
                        $total = $count['clicks'] + $total;
                    }
                    ?>
                    <span>Total Links: <span>
                            <?php echo end($res) ?>
                        </span> & Total Clicks: <span>
                            <?php echo $total ?>
                        </span></span>
                    <a href="php/delete.php?delete=all">Clear All</a>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Shorten URL</th>
                            <th>Original URL</th>
                            <th>Clicks</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($sql2)) {
                            ?>
                            <tr>
                                <td data-label="Shorten URL">
                                    <a href="<?php echo $row['shorten_url'] ?>" target="_blank">
                                        <?php
                                        if ($domain . strlen($row['shorten_url']) > 50) {
                                            echo $domain . "/" . substr($row['shorten_url'], 0, 50) . '...';
                                        } else {
                                            echo $domain . "/" . $row['shorten_url'];
                                        }

                                        ?>
                                    </a>
                                </td>
                                <td data-label="Original URL">
                                    <a href="<?php echo $row['full_url'] ?>" target="_blank">
                                        <?php

                                        if (strlen($row['full_url']) > 60) {
                                            echo substr($row['full_url'], 0, 60) . '...';
                                        } else {
                                            echo $row['full_url'];
                                        }
                                        ?>
                                    </a>
                                </td>
                                <td data-label="Clicks">
                                    <?php echo $row['clicks'] ?>
                                </td>
                                <td data-label="Action"><a href="php/delete.php?id=<?php echo $row['shorten_url'] ?>"><i
                                            class="action-icon uil uil-trash-alt"></i></a>
                                    <a id="shareBtn" style="cursor:pointer"><i class="action-icon uil uil-share-alt"></i></a>
                                </td>
                                <script>
                                    const shareButton = document.getElementById("shareBtn");
                                    const message = "Checkout This Short Link";

                                    shareButton.addEventListener("click", () => {
                                        if (navigator.share) {
                                            navigator.share({
                                                title: window.document.title,
                                                text: message,
                                                url: "<?php echo 'https://'.$domain.'/'.$row['shorten_url'] ?>",
                                            })
                                                .catch(error => {
                                                    alert("Sending Failed");
                                                    console.error("Sharing failed:", error);
                                                });
                                        } else {
                                            if (confirm("Sorry, your browser or device does not support sharing URLs. Do you want to copy the URL to your clipboard instead?")) {
                                                const copyText = message;

                                                // Create a temporary textarea element
                                                const textarea = document.createElement("textarea");
                                                textarea.value = copyText;
                                                document.body.appendChild(textarea);

                                                // Select and copy the text
                                                textarea.select();
                                                document.execCommand("copy");

                                                // Remove the temporary textarea
                                                document.body.removeChild(textarea);

                                                // Alert the user
                                                alert("Link Copied");
                                            }
                                        }
                                  }
                                    );

                                </script>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <?php
            }
            ?>
        </div>
    </div>


    <div class="blur-effect"></div>
    <div class="popup-box">
        <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you
            saved it.</div>
        <form action="#" autocomplete="off">
            <label>Edit your shorten url</label>
            <input type="text" class="shorten-url" spellcheck="false" required>
            <i class="copy-icon uil uil-copy-alt"></i>
            <button>Save</button>
        </form>
    </div>


    <?php include 'pages/footer.html' ?>
</body>

</html>