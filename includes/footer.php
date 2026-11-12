<footer class="site-footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="d-flex align-items-center mb-3" style="gap:12px;">
          <span style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#e8a020,#f5c060);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#0d2247;flex-shrink:0;">UG</span>
          <div>
            <h5 class="mb-0">University of Gondar</h5>
            <small style="color:rgba(255,255,255,.5);">Atse Tewodros Campus</small>
          </div>
        </div>
        <p style="color:rgba(255,255,255,.65);font-size:13px;line-height:1.7;">
          A web-based dormitory management system designed to streamline student accommodation at Atse Tewodros Campus.
        </p>
        <div style="display:flex;gap:10px;margin-top:16px;">
          <a href="#" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.7);transition:all .2s;" onmouseover="this.style.background='#e8a020'" onmouseout="this.style.background='rgba(255,255,255,.1)'"><i class="fab fa-facebook-f"></i></a>
          <a href="#" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.7);transition:all .2s;" onmouseover="this.style.background='#e8a020'" onmouseout="this.style.background='rgba(255,255,255,.1)'"><i class="fab fa-twitter"></i></a>
          <a href="#" style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.7);transition:all .2s;" onmouseover="this.style.background='#e8a020'" onmouseout="this.style.background='rgba(255,255,255,.1)'"><i class="fas fa-envelope"></i></a>
        </div>
      </div>

      <div class="col-lg-2 col-md-6 mb-4">
        <h5>Quick Links</h5>
        <ul style="list-style:none;padding:0;margin:0;">
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>"><i class="fas fa-angle-right" style="color:var(--accent-lt);font-size:11px;"></i> Home</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>pages/about.php"><i class="fas fa-angle-right" style="color:var(--accent-lt);font-size:11px;"></i> About Us</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>pages/news.php"><i class="fas fa-angle-right" style="color:var(--accent-lt);font-size:11px;"></i> News</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>pages/events.php"><i class="fas fa-angle-right" style="color:var(--accent-lt);font-size:11px;"></i> Events</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>pages/contact.php"><i class="fas fa-angle-right" style="color:var(--accent-lt);font-size:11px;"></i> Contact</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <h5>Portal Access</h5>
        <ul style="list-style:none;padding:0;margin:0;">
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>login.php"><i class="fas fa-user-shield" style="color:var(--accent-lt);"></i> Admin Login</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>login.php"><i class="fas fa-user-tie" style="color:var(--accent-lt);"></i> Proctor Login</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>login.php"><i class="fas fa-user-graduate" style="color:var(--accent-lt);"></i> Student Login</a></li>
          <li><a class="footer-link" href="<?php echo BASE_URL; ?>pages/view-dorm.php"><i class="fas fa-search" style="color:var(--accent-lt);"></i> Check Dorm Info</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <h5>Contact Info</h5>
        <ul style="list-style:none;padding:0;margin:0;">
          <li style="padding:6px 0;color:rgba(255,255,255,.65);"><i class="fas fa-map-marker-alt mr-2" style="color:var(--accent-lt);width:16px;"></i> Gondar, Ethiopia</li>
          <li style="padding:6px 0;color:rgba(255,255,255,.65);"><i class="fas fa-phone mr-2" style="color:var(--accent-lt);width:16px;"></i> +251 58 111 1234</li>
          <li style="padding:6px 0;color:rgba(255,255,255,.65);"><i class="fas fa-envelope mr-2" style="color:var(--accent-lt);width:16px;"></i> info@uog.edu.et</li>
          <li style="padding:6px 0;color:rgba(255,255,255,.65);"><i class="fas fa-globe mr-2" style="color:var(--accent-lt);width:16px;"></i> www.uog.edu.et</li>
        </ul>
      </div>
    </div>

    <hr>
    <div class="d-flex flex-wrap justify-content-between align-items-center">
      <p class="copyright mb-0">&copy; <?php echo date('Y'); ?> University of Gondar &mdash; Dormitory Management System. All rights reserved.</p>
      <p class="copyright mb-0">Developed by Dept. of Information Science &middot; 2019</p>
    </div>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/main.js"></script>
</body>
</html>
