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
          <li><i class="bi bi-telephone text-accent"></i> +234 800 000 0000</li>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>