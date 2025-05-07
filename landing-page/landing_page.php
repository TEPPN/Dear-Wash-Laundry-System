<?php
// Mulai session jika diperlukan untuk manajemen login user
session_start();

// Variabel untuk menyimpan data dari database jika diperlukan
$pageTitle = "Laundry DearWash - Layanan Cuci Kering & Setrika";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DearWash - Layanan laundry terbaik dengan kualitas terjamin">
    <link rel="stylesheet" href="styleshome.css">
    <title><?php echo $pageTitle; ?></title>
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
                <li class="active"><a href="#" aria-current="page">HOME</a></li>
                <li><a href="../profile/profile.php">Profile</a></li>
                <li><a href="#price-section">Price</a></li>
                <li><a href="progress.php">Progres</a></li>
                <li><a href="create_order.php">Create An Order</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1>LAUNDRY<span>DearWash</span></h1>
                <p>Selamat datang di Laundry Cuci Kering Kelompok Arisu</p>
                <p>yang sangat wah, dijamin wangi muach</p>
                <p>muach aww</p>
                <a href="create_order.php" class="cta-button">Go To Order →</a>
            </div>
            <div class="hero-image">
                <img src="../img/laundry1.png" alt="Laundry Illustration">
            </div>
        </section>
        
        <section class="services-section">
            <div class="container">
                <h2>BELIEVE YOUR CLOTHES</h2>
                <p>Percayakan pakaian kamu pada kami, dengan website ini, kemudahan menanti didepan mata, kualitas terjamin</p>
                
                <div class="services-container">
                    <div class="service">
                        <div class="service-icon">
                            <img src="../img/wewash.png" alt="Wash Icon">
                        </div>
                        <h3>We Wash</h3>
                    </div>
                    
                    <div class="service">
                        <div class="service-icon">
                            <img src="../img/wedry.png" alt="Dry Icon">
                        </div>
                        <h3>We Dry</h3>
                    </div>
                    
                    <div class="service">
                        <div class="service-icon">
                            <img src="../img/wedeliver.png" alt="Deliver Icon">
                        </div>
                        <h3>We Deliver</h3>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="price-section" id="price-section">
            <h2>PRICE LIST</h2>
            
            <?php
            // Anda bisa mengambil data harga dari database jika diperlukan
            $priceList = [
                ['id' => 1, 'name' => 'Complete Wash (Cuci Kering + Setrika)', 'price' => '6k/kg'],
                ['id' => 2, 'name' => 'Bed Cover (Sprei + Sarung Bantal)', 'price' => '7k/kg'],
                ['id' => 3, 'name' => 'Ironing atau Setrika saja (Segala Jenis)', 'price' => '4k/kg'],
                ['id' => 4, 'name' => 'Pakaian Express (Siap dalam 5 jam)', 'price' => '10k/kg'],
                ['id' => 5, 'name' => 'Sepatu (Dicuci dan Dikeringkan)', 'price' => '15k/pasang'],
                ['id' => 6, 'name' => 'Selimut Tebal', 'price' => '12k/pc'],
                ['id' => 7, 'name' => 'Dry Clean (Jaket/Jas)', 'price' => '20k/pc'],
                ['id' => 8, 'name' => 'Karpet Kecil (< 2m²)', 'price' => '25k/pc'],
                ['id' => 9, 'name' => 'Karpet Besar (> 2m²)', 'price' => '40k/pc'],
                ['id' => 10, 'name' => 'Pakaian Premium', 'price' => '19k/kg']
            ];
            ?>
            
            <div class="price-table">
                <div class="price-header"></div>
                
                <?php foreach($priceList as $item): ?>
                <div class="price-row">
                    <div class="price-item"><?php echo $item['id'] . '. ' . $item['name']; ?></div>
                    <div class="price-value"><?php echo $item['price']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    
    <footer>
        <p>Contact Us :</p>
        <p>+62 838 0095 3199 | <a href="mailto:laundrywash@gmail.com">laundrywash@gmail.com</a> | <a href="https://www.laundrywash.com" target="_blank">www.laundrywash.com</a></p>
    </footer>
    
    <button class="scroll-top" id="scroll-to-top" aria-label="Scroll to top">↑</button>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu').addEventListener('click', function() {
            document.getElementById('main-nav').classList.toggle('mobile-menu');
            document.getElementById('main-nav').classList.toggle('active');
        });
        
        // Scroll to top button
        const scrollToTopButton = document.getElementById('scroll-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopButton.classList.add('visible');
            } else {
                scrollToTopButton.classList.remove('visible');
            }
        });
        
        scrollToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>