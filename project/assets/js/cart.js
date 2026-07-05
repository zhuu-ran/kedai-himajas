/* =========================================================
   CART.JS — logic keranjang sementara pakai localStorage.
   CATATAN: ini sementara untuk tahap landing page dummy.
   Nanti setelah database & PHP backend siap, ini akan diganti
   supaya cart tersimpan di session PHP / database biar sinkron
   dengan sistem pemesanan per meja.
   ========================================================= */

const CART_KEY = 'rh_cart';

function getCart() {
   try {
      return JSON.parse(localStorage.getItem(CART_KEY)) || [];
   } catch (e) {
      return [];
   }
}

function saveCart(cart) {
   localStorage.setItem(CART_KEY, JSON.stringify(cart));
   updateCartBadge();
}

function updateCartBadge() {
   const cart = getCart();
   const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
   const badge = document.getElementById('cartCount');
   if (badge) badge.textContent = totalQty;
}

function addToCart(id, title, price, img) {
   const cart = getCart();
   const existing = cart.find(item => item.id === id);
   if (existing) {
      existing.qty += 1;
   } else {
      cart.push({ id, title, price: parseInt(price), img, qty: 1 });
   }
   saveCart(cart);
}

// Hubungkan semua tombol "+" di kartu menu
document.querySelectorAll('.madd').forEach(function (btn) {
   btn.addEventListener('click', function () {
      addToCart(
         this.dataset.id,
         this.dataset.title,
         this.dataset.price,
         this.dataset.img
      );
      // Feedback singkat
      const original = this.innerHTML;
      this.innerHTML = '<i class="fas fa-check"></i>';
      setTimeout(() => { this.innerHTML = original; }, 700);
   });
});

updateCartBadge();
