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
                <li><a href="../track/track.php">Progres</a></li>
                <li><a href="../order/order.php">Create An Order</a></li>
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
                <a href="../order/order.php" class="cta-button">Go To Order →</a>
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
            
            <div class="price-table">
                <div class="price-header"></div>
                
                <!-- Regular price items 1-3 -->
                <div class="price-row">
                    <div class="price-item">1. Complete Wash (Cuci Kering + Setrika)</div>
                    <div class="price-value">6k/kg</div>
                </div>
                <div class="price-row">
                    <div class="price-item">2. Bed Sheet (Sprei + Sarung Bantal)</div>
                    <div class="price-value">7k/kg</div>
                </div>
                <div class="price-row">
                    <div class="price-item">3. Ironing atau Setrika saja (Segala Jenis)</div>
                    <div class="price-value">4k/kg</div>
                </div>
                
                <!-- Expandable price items 4-6 -->
                <div class="price-row expandable-row" onclick="toggleExpandable('premium-services')">
                    <div class="price-item">4. Dry Cleaning <span class="expand-icon">+</span></div>
                    <div class="price-value"></div>
                </div>
                <div id="premium-services" class="expandable-content">
                    <div class="price-row sub-item">
                        <div class="price-item">• Suit</div>
                        <div class="price-value">20k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Suit Pants</div>
                        <div class="price-value">10k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Suits</div>
                        <div class="price-value">30k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Dress</div>
                        <div class="price-value">30k/pc</div>
                    </div>
                </div>
                
                <div class="price-row expandable-row" onclick="toggleExpandable('express-services')">
                    <div class="price-item">5. Bed Cover <span class="expand-icon">+</span></div>
                    <div class="price-value"></div>
                </div>
                <div id="express-services" class="expandable-content">
                    <div class="price-row sub-item">
                        <div class="price-item">• S</div>
                        <div class="price-value">20k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• M</div>
                        <div class="price-value">25k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• L</div>
                        <div class="price-value">30k/pc</div>
                    </div>
                </div>
                
                <div class="price-row expandable-row" onclick="toggleExpandable('special-items')">
                    <div class="price-item">6. Wash Seperate <span class="expand-icon">+</span></div>
                    <div class="price-value"></div>
                </div>
                <div id="special-items" class="expandable-content">
                    <div class="price-row sub-item">
                        <div class="price-item">• Underwear</div>
                        <div class="price-value">2k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Sock</div>
                        <div class="price-value">2k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Towel</div>
                        <div class="price-value">10k/pc</div>
                    </div>
                    <div class="price-row sub-item">
                        <div class="price-item">• Jacket</div>
                        <div class="price-value">5k/pc</div>
                    </div>
                </div>
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
        
        // Toggle expandable price list items
        function toggleExpandable(id) {
            const content = document.getElementById(id);
            const expandIcon = event.currentTarget.querySelector('.expand-icon');
            
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                content.style.opacity = "0";
                expandIcon.textContent = "+";
                setTimeout(() => {
                    content.style.display = "none";
                }, 300);
            } else {
                content.style.display = "block";
                setTimeout(() => {
                    content.style.maxHeight = content.scrollHeight + "px";
                    content.style.opacity = "1";
                    expandIcon.textContent = "-";
                }, 10);
            }
        }
    </script>
</body>
</html>