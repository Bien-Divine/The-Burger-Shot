<footer class="bs-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <h5 class="navbar-brand mb-2">THE BURGER <span class="text-accent">SHOT</span></h5>
        <p class="text-secondary small mb-3">
          Hand-smashed patties, flame-grilled flavour and a whole lot of love —
          served fresh every single day.
        </p>
        <div class="d-flex gap-2">
          <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <div class="col-6 col-lg-2">
        <h6 class="footer-head">Explore</h6>
        <ul class="list-unstyled small">
          <li><a href="index.php">Home</a></li>
          <li><a href="home.php">Menu</a></li>
          <li><a href="order.php">Order</a></li>
          <li><a href="about.php">About Us</a></li>
        </ul>
      </div>

      <div class="col-6 col-lg-2">
        <h6 class="footer-head">Account</h6>
        <ul class="list-unstyled small">
          <li><a href="login.php">Sign in</a></li>
          <li><a href="register.php">Create account</a></li>
          <li><a href="orders.php">My orders</a></li>
        </ul>
      </div>

      <div class="col-lg-4">
        <h6 class="footer-head">Visit us</h6>
        <ul class="list-unstyled small text-secondary mb-0">
          <li><i class="bi bi-geo-alt text-accent"></i> 14 Grill Street, Lagos</li>
          <li><i class="bi bi-telephone text-accent"></i> +234 901 250 0324</li>
          <li><i class="bi bi-envelope text-accent"></i> hello@burgershot.com</li>
          <li><i class="bi bi-clock text-accent"></i> Mon–Sun · 10:00 – 23:00</li>
        </ul>
      </div>
    </div>

    <hr class="my-4">
    <p class="text-center small text-secondary mb-0">
      &copy; <?= date('Y') ?> The Burger Shot. All rights reserved.
    </p>
  </div>
</footer>

<!-- ===== Cart drawer (available on every page) ===== -->
<div class="offcanvas offcanvas-end bs-cart" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel">
  <div class="offcanvas-header border-bottom border-secondary-subtle">
    <h5 class="offcanvas-title" id="cartDrawerLabel"><i class="bi bi-bag"></i> Your Bag</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <div id="cartItems" class="flex-grow-1"></div>
    <div class="cart-footer mt-3">
      <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
        <span>Total</span>
        <span class="text-accent" id="cartTotal">$0.00</span>
      </div>
      <a href="checkout.php" class="btn btn-accent w-100">
        <i class="bi bi-credit-card"></i> Proceed to checkout
      </a>
    </div>
  </div>
</div>

<!-- ===== Floating WhatsApp Button ===== -->
<a href="#" id="whatsappBtn" class="whatsapp-float" data-phone="<?= defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '2349012500324' ?>" target="_blank" aria-label="Order via WhatsApp">
  <i class="bi bi-whatsapp"></i>
</a>

<!-- ===== Item Detail Modal ===== -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bs-modal">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 pt-0">
        <div class="row g-4">
          <div class="col-md-6">
            <img id="modalImage" src="" alt="" class="img-fluid rounded-4" style="width: 100%; aspect-ratio: 1; object-fit: cover;">
          </div>
          <div class="col-md-6 d-flex flex-column">
            <span id="modalCategory" class="badge-cat position-static align-self-start mb-2"></span>
            <h2 id="modalName" class="h3 mb-2"></h2>
            <p id="modalDesc" class="text-secondary flex-grow-1"></p>
            
            <div class="mb-3 p-3 rounded-3" style="background:var(--bg-3); border:1px solid var(--line);">
              <div class="small text-secondary text-uppercase mb-1" style="letter-spacing:1px;">Allergens</div>
              <div id="modalAllergens" class="small fw-bold"></div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto">
              <span id="modalPrice" class="fs-3 fw-bold text-accent"></span>
              <button id="modalAddBtn" class="btn btn-accent btn-lg px-4" data-add-to-cart="">
                <i class="bi bi-bag-plus"></i> Add to bag
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>