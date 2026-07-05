<?php
session_start();
require_once 'config/database.php';
$menus = mysqli_query($koneksi, "SELECT * FROM menus WHERE status = 'available' ORDER BY category, name");

function slugCategory($cat) {
    $map = ['Ayam'=>'ayam','Mie'=>'mie','Cemilan/Dessert'=>'cemilan','Minuman'=>'minuman'];
    return $map[$cat] ?? strtolower($cat);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="Pesan makanan favoritmu langsung dari meja">
   <title>Kedai Himajas - Pemesanan Makanan</title>
   <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
   <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
   <link href="assets/css/aos.css" rel="stylesheet"/>
   <link href="assets/css/swiper-bundle.min.css" rel="stylesheet"/>
   <link rel="stylesheet" href="assets/css/all.min.css"/>
   <link rel="stylesheet" href="assets/css/style.css"/>
   <!-- Override tema hijau elegan -->
   <link rel="stylesheet" href="assets/css/custom.css"/>
</head>
<body>


   <div id="topbar">
      <div class="container">
         <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="top-contact d-flex flex-wrap">
               <span><i class="fas fa-phone-alt"></i>+62 812-0000-0000</span>
               <span><i class="fas fa-map-marker-alt"></i>Sarijadi, Kec. Sukasari, Kota Bandung, Jawa Barat</span>
            </div>
            <div class="d-flex align-items-center gap-3">
               <span class="ttag"><i class="fas fa-leaf me-1"></i>Pesan Langsung dari Meja</span>
            </div>
         </div>
      </div>
   </div>

   <nav class="navbar navbar-expand-lg" id="nav">
      <div class="container">
         <a class="navbar-brand" href="index.php">
            <div class="blogo">
               <div class="bico"><i class="fas fa-leaf"></i></div>  
               <div>
                  <div class="bname">Kedai<span>Himajas</span></div>
                  <div class="bsub">Pemesanan Makanan</div>
               </div>
            </div>
         </a>
         <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <i class="fas fa-bars" style="color:var(--primary);font-size:1.35rem;"></i>
         </button>
         <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav mx-auto">
               <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
               <li class="nav-item"><a class="nav-link" href="#category">Kategori</a></li>
               <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
               <li class="nav-item"><a class="nav-link" href="#hours">Jam Buka</a></li>
               <li class="nav-item"><a class="nav-link" href="#contact-section">Kontak</a></li>
            </ul>
            <?php if (isset($_SESSION['id'])): ?>
               <div class="d-flex align-items-center gap-2">
                  <span style="font-size:.85rem;font-weight:600;color:var(--primary);">
                     <i class="fas fa-user-check me-1"></i><?= htmlspecialchars($_SESSION['name']) ?>
                  </span>
                  <a href="auth/logout.php" class="nav-icon-btn" title="Logout">
                     <i class="fas fa-sign-out-alt"></i>
                  </a>
               </div>
            <?php else: ?>
               <button class="nav-icon-btn" id="btnOpenAuth" title="Login / Daftar">
                  <i class="fas fa-user"></i>
               </button>
            <?php endif; ?>
               </a>
               <a href="checkout.php" class="nav-icon-btn" title="Keranjang & Checkout">
                  <i class="fas fa-shopping-cart"></i>
                  <span class="cart-badge" id="cartCount">0</span>
               </a>
            </div>
         </div>
      </div>
   </nav>

   <section id="hero">
      <div class="hs hs1"></div>
      <div class="hs hs2"></div>
      <div class="hbgtxt">MAKAN</div>
      <div class="container">
         <div class="row align-items-center g-5" style="min-height:80vh;">
            <div class="col-lg-6">
               <div class="hbadge">
                  <div class="hbi"><i class="fas fa-star"></i></div>
                  <span>Pesan Cepat Langsung dari Meja</span>
               </div>
               <h1 class="htitle">Pesan <span class="hl">Makanan Favorit</span><br/>Tanpa Ribet</h1>
               <p class="hdesc">Pilih menu, masukkan ke keranjang, isi data pemesanan — pesananmu langsung kami terima. Cepat, simpel, dan segar setiap saat.</p>
               <div class="d-flex flex-wrap gap-3 mb-2">
                  <a href="#menu" class="btn-red"><i class="fas fa-utensils"></i>Lihat Menu</a>
               </div>
               <div class="hstats d-flex gap-3 flex-wrap mt-4">
                  <div class="hstat"><span class="snum">4<em>kategori</em></span><small>Ayam, Mie, Cemilan, Minuman</small></div>
                  <div class="sdiv"></div>
                  <div class="hstat"><span class="snum">100<em>%</em></span><small>Pesan Tanpa Antri</small></div>
               </div>
            </div>
            <div class="col-lg-6">
               <div style="position:relative;text-align:center;">
                  <div class="hcircle">
                     <img src="assets/img/hero/banner-img.jpg" alt="Hero Makanan"/>
                  </div>
                  <div class="fcard fc1">
                     <div class="fcoi r"><i class="fas fa-fire"></i></div>
                     <div><span class="fcnum">Menu Baru</span><span class="fcsm">tiap minggu</span></div>
                  </div>
                  <div class="fcard fc3">
                     <div class="fcoi g"><i class="fas fa-clock"></i></div>
                     <div><span class="fcnum">15 menit</span><span class="fcsm">rata-rata saji</span></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section id="category">
      <div class="container">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Kategori Menu</span>
            <h2 class="stitle">Pilih <span>Kategori</span></h2>
            <div class="sline"></div>
            <p class="sdesc mx-auto" style="max-width:480px;">Temukan menu favoritmu di antara empat kategori berikut</p>
         </div>
         <div class="row g-3 justify-content-center">
            <div class="col-6 col-sm-4 col-md-3" data-aos="zoom-in" data-aos-delay="0">
               <div class="catcard active" data-filter="all">
                  <img class="catimg" src="assets/img/category/1.jpg" alt="Semua Menu"/>
                  <div class="catnm">Semua Menu</div>
               </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3" data-aos="zoom-in" data-aos-delay="70">
               <div class="catcard" data-filter="ayam">
                  <img class="catimg" src="assets/img/category/2.jpg" alt="Ayam"/>
                  <div class="catnm">Ayam</div>
               </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3" data-aos="zoom-in" data-aos-delay="140">
               <div class="catcard" data-filter="mie">
                  <img class="catimg" src="assets/img/category/3.jpg" alt="Mie"/>
                  <div class="catnm">Mie</div>
               </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3" data-aos="zoom-in" data-aos-delay="210">
               <div class="catcard" data-filter="cemilan">
                  <img class="catimg" src="assets/img/category/5.jpg" alt="Cemilan / Dessert"/>
                  <div class="catnm">Cemilan / Dessert</div>
               </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3" data-aos="zoom-in" data-aos-delay="280">
               <div class="catcard" data-filter="minuman">
                  <img class="catimg" src="assets/img/category/4.jpg" alt="Minuman"/>
                  <div class="catnm">Minuman</div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section id="menu">
      <div class="container">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Menu Kami</span>
            <h2 class="stitle">Daftar <span>Menu</span></h2>
            <div class="sline"></div>
         </div>

         <div class="text-center mb-4" data-aos="fade-up">
            <button class="filtbtn active" data-f="all">Semua</button>
            <button class="filtbtn" data-f="ayam">Ayam</button>
            <button class="filtbtn" data-f="mie">Mie</button>
            <button class="filtbtn" data-f="cemilan">Cemilan / Dessert</button>
            <button class="filtbtn" data-f="minuman">Minuman</button>
         </div>

         <div class="row g-4" id="mgrid">
            <?php if (mysqli_num_rows($menus) > 0): ?>
               <?php while ($m = mysqli_fetch_assoc($menus)): ?>
                  <div class="col-sm-6 col-lg-4 mwrap" data-c="<?= slugCategory($m['category']) ?>" data-aos="fade-up">
                     <div class="mcard" data-id="<?= $m['id'] ?>" data-title="<?= htmlspecialchars($m['name']) ?>" data-cat="<?= htmlspecialchars($m['category']) ?>" data-price="<?= $m['price'] ?>">
                        <div class="mimg">
                           <img src="<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['name']) ?>"/>
                        </div>
                        <div class="mbody">
                           <div class="mcat"><?= htmlspecialchars($m['category']) ?></div>
                           <div class="mtit"><?= htmlspecialchars($m['name']) ?></div>
                           <div class="mdesc"><?= htmlspecialchars($m['description']) ?></div>
                           <div class="mfoot">
                              <div><div class="mprice">Rp <?= number_format($m['price'], 0, ',', '.') ?></div></div>
                              <button class="madd" data-id="<?= $m['id'] ?>" data-title="<?= htmlspecialchars($m['name']) ?>" data-price="<?= $m['price'] ?>" data-img="<?= htmlspecialchars($m['image']) ?>" title="Tambah ke Keranjang">
                                 <i class="fas fa-plus"></i>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endwhile; ?>
            <?php else: ?>
               <div class="col-12 text-center py-5"><p style="color:#999;">Belum ada menu tersedia.</p></div>
            <?php endif; ?>
         </div>

        
      </div>
   </section>

   <section id="hours">
      <div class="hrsbg"></div>
      <div class="container" style="position:relative;z-index:2;">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl" style="color:#a5d6bc;">Jam Buka</span>
            <h2 class="stitle" style="color:#fff;">Kami Buka <span style="color:var(--secondary);">Untukmu</span></h2>
            <div class="sline"></div>
         </div>
         <div class="row g-4 align-items-start justify-content-center">
            <div class="col-lg-5" data-aos="fade-right">
               <div class="hrscard">
                  <div class="hrsrow">
                     <span class="hrsday"><i class="fas fa-calendar-day me-2" style="color:var(--secondary);"></i>Senin - Jumat</span>
                     <div class="d-flex align-items-center gap-2">
                        <div class="hdot on"></div>
                        <span class="hrstime">09:00 - 21:00</span>
                     </div>
                  </div>
                  <div class="hrsrow">
                     <span class="hrsday"><i class="fas fa-calendar-day me-2" style="color:var(--secondary);"></i>Sabtu - Minggu</span>
                     <div class="d-flex align-items-center gap-2">
                        <div class="hdot on"></div>
                        <span class="hrstime">10:00 - 22:00</span>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-4" data-aos="fade-left">
               <div class="hrscard">
                  <h5 style="color:#fff;margin-bottom:18px;font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:700;"><i class="fas fa-map-marker-alt me-2" style="color:var(--secondary);"></i>Lokasi</h5>
                  <div class="hrsrow"><span class="hrsday"><i class="fas fa-location-dot me-2" style="color:var(--secondary);"></i>Alamat</span><span class="hrstime" style="font-size:.8rem;">Sarijadi, Kec. Sukasari, Kota Bandung</span></div>
                  <div class="hrsrow"><span class="hrsday"><i class="fas fa-phone me-2" style="color:var(--secondary);"></i>Telepon</span><span class="hrstime" style="font-size:.8rem;">+62 812-0000-0000</span></div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <section id="contact-section">
      <div class="container">
         <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Hubungi Kami</span>
            <h2 class="stitle">Kontak <span>Kami</span></h2>
            <div class="sline"></div>
         </div>
         <div class="row g-4 justify-content-center">
            <div class="col-lg-6" data-aos="fade-up">
               <div class="ctdark">
                  <h4>Mari Terhubung</h4>
                  <div class="ctitem">
                     <div class="cticon"><i class="fas fa-map-marker-alt"></i></div>
                     <div class="ctinfo"><strong>Alamat</strong><span>Sarijadi, Kec. Sukasari, Kota Bandung, Jawa Barat</span></div>
                  </div>
                  <div class="ctitem">
                     <div class="cticon"><i class="fas fa-phone-alt"></i></div>
                     <div class="ctinfo"><strong>Telepon</strong><span>+62 812-0000-0000</span></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <footer>
      <div class="container">
         <div class="row g-5">
            <div class="col-lg-5">
               <div class="fnm">Resto<span>Hijau</span></div>
               <p class="fdesc">Pesan makanan langsung dari meja, cepat dan praktis.</p>
            </div>
            <div class="col-sm-6 col-lg-3">
               <div class="ftit">Tautan</div>
               <ul class="flinks ps-0">
                  <li><a href="#hero"><i class="fas fa-chevron-right"></i>Beranda</a></li>
                  <li><a href="#menu"><i class="fas fa-chevron-right"></i>Menu</a></li>
                  <li><a href="#hours"><i class="fas fa-chevron-right"></i>Jam Buka</a></li>
                  <li><a href="#contact-section"><i class="fas fa-chevron-right"></i>Kontak</a></li>
               </ul>
            </div>
            <div class="col-sm-6 col-lg-4">
               <div class="ftit">Kontak</div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-map-marker-alt"></i></div>
                  <div class="fciinfo"><strong>Alamat</strong>Sarijadi, Kec. Sukasari, Kota Bandung, Jawa Barat</div>
               </div>
               <div class="fci">
                  <div class="fciico"><i class="fas fa-phone-alt"></i></div>
                  <div class="fciinfo"><strong>Telepon</strong>+62 812-0000-0000</div>
               </div>
            </div>
         </div>
      </div>
      <div class="fbot">
         <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <p>&copy 2026 <span>KedaiHimajas</span>. All Rights Reserved by <a target="_blank" class="mx-0 fw-bold text-success" href="https://bestwpware.com/">Bestwpware</a>. Made with <span><i class="fas fa-heart"></i></span>  <br>Distributed by <a target="_blank" class="mx-0 fw-bold text-success" href="https://themewagon.com">ThemeWagon</a></p>
                  <div><a href="#">Privacy Policy</a><a href="#">Terms</a><a href="#">Cookies</a></div>
               </div>
         </div>
      </div>
   </footer>

   <button id="btt" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="fas fa-chevron-up"></i></button>

   <script src="assets/js/jquery-3.7.1.min.js"></script>
   <script src="assets/js/bootstrap.bundle.min.js"></script>
   <script src="assets/js/aos.js"></script>
   <script src="assets/js/swiper-bundle.min.js"></script>
   <script>AOS.init({duration:700, once:true});</script>

   <script>
   document.querySelectorAll('.filtbtn').forEach(function(btn){
      btn.addEventListener('click', function(){
         document.querySelectorAll('.filtbtn').forEach(b=>b.classList.remove('active'));
         this.classList.add('active');
         var f = this.dataset.f;
         document.querySelectorAll('.mwrap').forEach(function(card){
            card.style.display = (f === 'all' || card.dataset.c === f) ? '' : 'none';
         });
      });
   });
   document.querySelectorAll('.catcard').forEach(function(cc){
      cc.addEventListener('click', function(){
         var f = this.dataset.filter;
         document.querySelector('.filtbtn[data-f="'+f+'"]').click();
         document.querySelectorAll('.catcard').forEach(c=>c.classList.remove('active'));
         this.classList.add('active');
         document.getElementById('menu').scrollIntoView({behavior:'smooth'});
      });
   });
   </script>

   <script src="assets/js/cart.js"></script>

   <div id="authOverlay">
   <div class="auth-modal">
      <button class="auth-close" id="btnCloseAuth"><i class="fas fa-times"></i></button>

      <!-- PANEL LOGIN -->
      <div id="panelLogin" class="auth-panel active">
         <div class="auth-logo"><i class="fas fa-leaf"></i></div>
         <h4 class="auth-title">Selamat Datang</h4>
         <p class="auth-sub">Masuk ke akun kamu</p>
         <div class="auth-field">
            <label>Email</label>
            <input type="email" id="loginEmail" placeholder="email@kamu.com"/>
         </div>
         <div class="auth-field">
            <label>Password</label>
            <div class="auth-pass-wrap">
               <input type="password" id="loginPass" placeholder="••••••••"/>
               <button type="button" class="auth-eye" data-target="loginPass"><i class="fas fa-eye"></i></button>
            </div>
         </div>
         <button class="auth-btn" id="btnLogin"><i class="fas fa-sign-in-alt"></i> Masuk</button>
         <div class="auth-msg" id="loginMsg"></div>
         <p class="auth-switch">Belum punya akun? <a href="#" id="goRegister">Daftar sekarang</a></p>
      </div>

      <!-- PANEL REGISTER -->
      <div id="panelRegister" class="auth-panel">
         <div class="auth-logo"><i class="fas fa-leaf"></i></div>
         <h4 class="auth-title">Buat Akun</h4>
         <p class="auth-sub">Daftar sebagai admin / staf</p>
         <div class="auth-field">
            <label>Nama Lengkap</label>
            <input type="text" id="regName" placeholder="Nama kamu"/>
         </div>
         <div class="auth-field">
            <label>Email</label>
            <input type="email" id="regEmail" placeholder="email@kamu.com"/>
         </div>
         <div class="auth-field">
            <label>Password</label>
            <div class="auth-pass-wrap">
               <input type="password" id="regPass" placeholder="Min. 8 karakter"/>
               <button type="button" class="auth-eye" data-target="regPass"><i class="fas fa-eye"></i></button>
            </div>
         </div>
         <div class="auth-field">
            <label>Konfirmasi Password</label>
            <div class="auth-pass-wrap">
               <input type="password" id="regPass2" placeholder="Ulangi password"/>
               <button type="button" class="auth-eye" data-target="regPass2"><i class="fas fa-eye"></i></button>
            </div>
         </div>
         <button class="auth-btn" id="btnRegister"><i class="fas fa-user-plus"></i> Daftar</button>
         <div class="auth-msg" id="registerMsg"></div>
         <p class="auth-switch">Sudah punya akun? <a href="#" id="goLogin">Masuk sekarang</a></p>
      </div>
   </div>
</div>

<script src="assets/js/auth-modal.js"></script>
</body>
</html>