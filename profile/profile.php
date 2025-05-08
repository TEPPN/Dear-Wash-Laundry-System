<?php
session_start();

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Dear Wash</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../img/logo.png" alt="DearWash Logo">
        </div>
        <div class="menu-toggle" id="mobile-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav" id="main-nav">
            <ul>
                <li><a href="../landing-page/landing_page.php">HOME</a></li>
                <li class="active"><a href="profile.php" aria-current="page">Profile</a></li>
                <li><a href="../landing-page/landing_page.php">Price</a></li>
                <li><a href="progress.php">Progres</a></li>
                <li><a href="../order/order.php">Create An Order</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">

        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="../img/avatar.png" alt="User Avatar">
                </div>
                <div class="profile-info">
                    <h1 class="user-name"><?php echo $user_name; ?></h1>
                    <p class="user-id"><?php echo $user_id; ?></p>
                    

                    <div class="user-description">
                        Deskripsi User
                        <p>Mahasiswa USU</p>
                    </div>
                </div>
                <div class="logout-button">
                    <a href="../login/logout.php">
                        <button>Log Out</button>
                    </a>
                </div>
            </div>

            <div class="laundry-illustration">
                <!-- Background illustration is added via CSS -->
            </div>
        </div>
    </div>
</body>

</html>