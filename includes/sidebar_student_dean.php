<?php $currentUser = getCurrentUser(); $curPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($currentUser['fname'],0,1)); ?></div>
    <div>
      <div class="user-name"><?php echo htmlspecialchars($currentUser['fname'].' '.$currentUser['lname']); ?></div>
      <div class="user-role">Student Dean</div>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-section">Main</li>
    <li><a href="<?php echo BASE_URL; ?>student-dean/dashboard.php" class="<?php echo $curPage==='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>

    <li class="menu-section">Content</li>
    <li><a href="<?php echo BASE_URL; ?>student-dean/post-news.php" class="<?php echo $curPage==='post-news.php'?'active':''; ?>"><i class="fas fa-plus-circle"></i>Post News/Event</a></li>
    <li><a href="<?php echo BASE_URL; ?>student-dean/manage-news.php" class="<?php echo $curPage==='manage-news.php'?'active':''; ?>"><i class="fas fa-newspaper"></i>Manage Posts</a></li>
    <li><a href="<?php echo BASE_URL; ?>student-dean/comments.php" class="<?php echo $curPage==='comments.php'?'active':''; ?>"><i class="fas fa-comments"></i>View Comments</a></li>

    <li class="menu-section">Account</li>
    <li><a href="<?php echo BASE_URL; ?>student-dean/change-password.php"><i class="fas fa-key"></i>Change Password</a></li>
    <li><a href="<?php echo BASE_URL; ?>logout.php" style="color:rgba(255,100,100,.8)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</aside>
