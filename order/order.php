<?php
require '../login/check.php';
session_start();
require_once "../connect/connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DearWash - Create An Order</title>
    <link rel="stylesheet" href="orderstyle.css">
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
                <li><a href="../profile/profile.php">Profile</a></li>
                <li><a href="#price-section">Price</a></li>
                <li><a href="../track.php">Progres</a></li>
                <li class="active"><a href="../order/order.php" aria-current="page">Create An Order</a></li>
            </ul>
        </nav>
    </header>

    <main class="order-container">
        <div class="service-options">
            <div class="service-card quick-wash">
                <div class="service-icon">
                    <img src="../img/fast.png" alt="Quick Wash Icon">
                </div>
                <h2>Quick Wash & Dry</h2>
                <p>take 1 day but doesn't give the best result and only accept regular clothes, best for laundry in bulk</p>
                <a href="./quick/quick_wash.html" class="service-link">Select</a>
            </div>

            <div class="service-card regular-order">
                <div class="service-icon">
                    <img src="../img/normal.png" alt="Regular Order Icon">
                </div>
                <h2>Regular Order</h2>
                <p>take 3 days but give the best result and more option for any type of clothes</p>
                <a href="regular/regular.html" class="service-link">Select</a>
            </div>
        </div>
        
        <div class="laundry-illustration">
            <img src="../img/laundry1.png" alt="Laundry Service Illustration">
        </div>
    </main>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenu = document.getElementById('mobile-menu');
    const mainNav = document.getElementById('main-nav');
    
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNav.classList.toggle('active');
        });
    }
    
    // Close menu when clicking on a link
    const navLinks = document.querySelectorAll('#main-nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            mainNav.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });
});
</script>
</html>