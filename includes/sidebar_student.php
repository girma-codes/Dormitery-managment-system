<?php $currentUser = getCurrentUser(); $curPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($currentUser['fname'],0,1)); ?></div>
    <div>
      <div class="user-name"><?php echo htmlspecialchars($currentUser['fname'].' '.$currentUser['lname']); ?></div>
      <div class="user-role">Student</div>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-section">Main</li>
    <li><a href="<?php echo BASE_URL; ?>student/dashboard.php" class="<?php echo $curPage==='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
    <li><a href="<?php echo BASE_URL; ?>student/my-dorm.php" class="<?php echo $curPage==='my-dorm.php'?'active':''; ?>"><i class="fas fa-bed"></i>My Dormitory</a></li>

    <li class="menu-section">Information</li>
    <li><a href="<?php echo BASE_URL; ?>student/news.php" class="<?php echo $curPage==='news.php'?'active':''; ?>"><i class="fas fa-newspaper"></i>News &amp; Events</a></li>
    <li><a href="<?php echo BASE_URL; ?>student/submit-comment.php" class="<?php echo $curPage==='submit-comment.php'?'active':''; ?>"><i class="fas fa-comment-alt"></i>Submit Comment</a></li>

    <li class="menu-section">Account</li>
    <li><a href="<?php echo BASE_URL; ?>student/change-password.php"><i class="fas fa-key"></i>Change Password</a></li>
    <li><a href="<?php echo BASE_URL; ?>logout.php" style="color:rgba(255,100,100,.8)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</aside>
