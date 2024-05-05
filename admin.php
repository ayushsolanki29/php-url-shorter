<?php
session_start();
include "php/config.php";

// Security check
if (isset($_GET['login'])) {
    if ($_GET['login'] == "true") {
        $admin_get_password = $_GET['password'];
        if ($admin_get_password == $_SESSION["admin"]) {
            echo "<script type='text/javascript'>alert('Welcome Admin');</script>";
            $_SESSION["admin_active"] = "true";
        } else {
            header("location: index.php");
            exit;
        }
    } else {
        header("location: index.php");
        exit;
    }
} else {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .table th,
        .table td {
            text-align: center;
        }

        .user-card .card {
            margin-bottom: 20px;
        }

        .btn-manage {
            color: #fff;
            background-color: #28a745;
            border: none;
        }

        @media (max-width: 767px) {

            .user-card .card,
            .url-card .card {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <?php
            function get_total_clicks($conn)
            {
                $sql = "SELECT SUM(clicks) as totalClicks FROM url";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['totalClicks'];
                } else {
                    return 0;
                }
            }

            function get_total_urls($conn)
            {
                $sql = "SELECT COUNT(*) as totalUrls FROM url";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['totalUrls'];
                } else {
                    return 0;
                }
            }
            function get_user_count($conn)
            {
                $sql = "SELECT COUNT(*) as userCount FROM users";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    return $row['userCount'];
                } else {
                    return 0;
                }
            }

            $totalUsers = get_user_count($conn);
            $totalClicks = get_total_clicks($conn);
            $totalUrls = get_total_urls($conn);
            ?>

            <div class="col-md-4">
                <div class="card  mt-2">
                    <?php
                    $totalUsers = get_user_count($conn);
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Number of Users</h5>
                        <p class="card-text">
                            <?php echo $totalUsers; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card  mt-2">
                    <?php
                    $totalClicks = get_total_clicks($conn);
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Total Clicks</h5>
                        <p class="card-text">
                            <?php echo $totalClicks; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card  mt-2">
                    <?php
                    $totalUrls = get_total_urls($conn);
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Total URLs</h5>
                        <p class="card-text">
                            <?php echo $totalUrls; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <h2>User URLs and Click Statistics</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Profile Pic</th>
                        <th>Clicks</th>
                        <th colspan="2">Manage</th>
                    </tr>
                </thead>
                <?php
                $selectUsersWithUrls = "SELECT users.id, users.username, users.email, users.token, users.profile, url.clicks FROM users LEFT JOIN url ON users.id = url.user_id";
                $userQuery = mysqli_query($conn, $selectUsersWithUrls);

                $users = [];

                if ($userQuery) {
                    while ($result = mysqli_fetch_assoc($userQuery)) {
                        $userId = $result['id'];
                        if (!isset($users[$userId])) {
                            $users[$userId] = $result;
                        }
                    }
                }
                ?>
                <tbody>
                    <?php
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['id']}</td>";
                        echo "<td>{$user['username']}</td>";
                        echo "<td>{$user['email']}</td>";
                        echo "<td><img src='{$user['profile']}' alt='Profile' width='50'></td>";
                        echo "<td>{$user['clicks']}</td>";
                        $token = $user['token'];
                        echo "<td class='edit'>
                        <a href='" . $domain . "activate.php?token=" . $token . "&admin'>
                            " . (isset($status) ? $status : 'active') . "
                        </a>
                      </td>";

                        echo "<td class='del'>
                        <a href='javascript:void(0);' onclick='confirmDelete({$user['id']})'>
                            Delete
                        </a>
                      </td>";


                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <script>
                    function confirmDelete(userId, urlId) {
                        alert('This action will permanently delete the user and all associated URLs.');
                        var result = confirm("Are you sure you want to proceed with the deletion?");
                        if (result) {
                            window.location.href = `php/delete_user.php?delete-user=${userId}&delete-url=${urlId}`;
                        }
                    }
                </script>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>