<header>
  <div class="logo-container">
    <a href="homepage.html"><img src="Media/icon2.png" alt="Logo" class="header-logo"></a>
  </div>
  <nav>
    <ul>
      <li>
      <div class="cart-icon">
        <a href="cart.php"><i class="fas fa-shopping-cart fa-lg"></i></a>
        <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
        <span id="cart-count"><?= $_SESSION['cart_count'] ?></span>
        <?php else: ?>
        <span id="cart-count">0</span>
        <?php endif; ?>
        </div>

      </a>
      </li>
      <li><a href="homepage.html">Home</a></li>
      <li><a href="gamestore.php">Games</a></li>
      <li><a href="homepage.html#about">About Us</a></li>
      <li><a href="support.html">Contact</a></li>
      <li><a href="Login_Signup/selection.html" class="login-btn">Login</a></li>
      <li><a href="Login_Signup/selection.html" class="signup-btn">Sign Up</a></li>
      
    </ul>
  </nav>
</header>

