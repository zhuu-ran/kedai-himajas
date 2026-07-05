   <!DOCTYPE html>
   <html lang="id">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Checkout - KedaiHimajas</title>
      <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
      <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
      <link rel="stylesheet" href="assets/css/all.min.css"/>
      <link rel="stylesheet" href="assets/css/style.css"/>
      <link rel="stylesheet" href="assets/css/custom.css"/>
   </head>
   <body style="background:var(--cream);">

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
         </div>
      </nav>

      <div class="checkout-wrap">
         <a href="index.php#menu" class="back-to-menu"><i class="fas fa-arrow-left"></i> Kembali ke Menu</a>

         <div class="checkout-card">
            <h5><i class="fas fa-shopping-bag me-2"></i>Pesanan Kamu</h5>
            <div id="cartItemsWrap">
               <!-- diisi oleh JS -->
            </div>
            <div id="emptyState" class="empty-cart" style="display:none;">
               <i class="fas fa-cart-arrow-down fa-2x mb-2" style="color:#ccc;"></i>
               <p>Keranjang masih kosong. Yuk pilih menu dulu.</p>
               <a href="index.php#menu" class="btn-red"><i class="fas fa-utensils"></i> Lihat Menu</a>
            </div>
            <div class="checkout-total-row" id="totalRow" style="display:none;">
               <span>Total</span>
               <span id="totalPrice">Rp 0</span>
            </div>
         </div>

         <div class="checkout-card checkout-form" id="formCard" style="display:none;">
            <h5><i class="fas fa-clipboard-list me-2"></i>Informasi Pemesanan</h5>
            <form id="orderForm">
               <div class="row g-3">
                  <div class="col-sm-4">
                     <label class="form-label">No. Meja *</label>
                     <input type="text" class="form-control" id="noMeja" placeholder="Contoh: 5" required>
                  </div>
                  <div class="col-sm-4">
                     <label class="form-label">Nama Pemesan *</label>
                     <input type="text" class="form-control" id="namaPemesan" placeholder="Nama kamu" required>
                  </div>
                  <div class="col-sm-4">
                     <label class="form-label">No. Telepon *</label>
                     <input type="tel" class="form-control" id="noTelp" placeholder="08xxxxxxxxxx" required>
                  </div>
               </div>
               <div class="mt-4">
                  <button type="submit" class="btn-checkout">
                     <i class="fas fa-paper-plane me-2"></i>Kirim Pesanan
                  </button>
               </div>
               <p class="text-center mt-3" style="font-size:.8rem;color:#888;">
                  Pembayaran dilakukan langsung di kasir / staf, bukan melalui halaman ini.
               </p>
            </form>
         </div>
      </div>

      <script src="assets/js/jquery-3.7.1.min.js"></script>
      <script src="assets/js/bootstrap.bundle.min.js"></script>
      <script>
      const CART_KEY = 'rh_cart';

      function getCart() {
         try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; }
         catch (e) { return []; }
      }
      function saveCart(cart) {
         localStorage.setItem(CART_KEY, JSON.stringify(cart));
      }
      function formatRupiah(num) {
         return 'Rp ' + num.toLocaleString('id-ID');
      }

      function renderCart() {
         const cart = getCart();
         const wrap = document.getElementById('cartItemsWrap');
         const empty = document.getElementById('emptyState');
         const totalRow = document.getElementById('totalRow');
         const formCard = document.getElementById('formCard');

         if (cart.length === 0) {
            wrap.innerHTML = '';
            empty.style.display = 'block';
            totalRow.style.display = 'none';
            formCard.style.display = 'none';
            return;
         }

         empty.style.display = 'none';
         totalRow.style.display = 'flex';
         formCard.style.display = 'block';

         let html = '';
         let total = 0;
         cart.forEach((item, idx) => {
            const subtotal = item.price * item.qty;
            total += subtotal;
            html += `
               <div class="cart-item-row">
                  <img src="${item.img}" alt="${item.title}">
                  <div class="cart-item-info">
                     <div class="nm">${item.title}</div>
                     <div class="pr">${formatRupiah(item.price)}</div>
                  </div>
                  <div class="qty-ctrl">
                     <button type="button" onclick="changeQty(${idx}, -1)">-</button>
                     <span>${item.qty}</span>
                     <button type="button" onclick="changeQty(${idx}, 1)">+</button>
                  </div>
                  <div style="min-width:90px;text-align:right;font-weight:600;">${formatRupiah(subtotal)}</div>
                  <button type="button" onclick="removeItem(${idx})" style="border:none;background:none;color:#c0392b;font-size:1rem;">
                     <i class="fas fa-trash"></i>
                  </button>
               </div>
            `;
         });
         wrap.innerHTML = html;
         document.getElementById('totalPrice').textContent = formatRupiah(total);
      }

      function changeQty(idx, delta) {
         const cart = getCart();
         cart[idx].qty += delta;
         if (cart[idx].qty <= 0) cart.splice(idx, 1);
         saveCart(cart);
         renderCart();
      }

      function removeItem(idx) {
         const cart = getCart();
         cart.splice(idx, 1);
         saveCart(cart);
         renderCart();
      }

      document.getElementById('orderForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const cart = getCart();

      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';

      const order = {
         noMeja: document.getElementById('noMeja').value,
         nama: document.getElementById('namaPemesan').value,
         noTelp: document.getElementById('noTelp').value,
         items: cart,
      };

      fetch('proses_order.php', {
         method: 'POST',
         headers: { 'Content-Type': 'application/json' },
         body: JSON.stringify(order)
      })
      .then(res => res.json())
      .then(data => {
         if (data.status === 'success') {
            localStorage.removeItem(CART_KEY);
            alert('Pesanan berhasil dikirim! Kode pesanan: ' + data.order_code);
            window.location.href = 'index.php';
         } else {
            alert('Gagal: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
         }
      })
      .catch(err => {
         alert('Terjadi kesalahan koneksi. Coba lagi.');
         submitBtn.disabled = false;
         submitBtn.innerHTML = originalText;
      });
   });

      renderCart();
      </script>
   </body>
   </html>
