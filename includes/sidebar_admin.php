<?php
$currentUser = getCurrentUser();
$curPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($currentUser['fname'],0,1)); ?></div>
    <div>
      <div class="user-name"><?php echo htmlspecialchars($currentUser['fname'].' '.$currentUser['lname']); ?></div>
      <div class="user-role">Administrator</div>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-section">Main</li>
    <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="<?php echo $curPage==='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>

    <li class="menu-section">User Management</li>
    <li><a href="<?php echo BASE_URL; ?>admin/users.php" class="<?php echo $curPage==='users.php'?'active':''; ?>"><i class="fas fa-users"></i>Manage Users</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/create-user.php" class="<?php echo $curPage==='create-user.php'?'active':''; ?>"><i class="fas fa-user-plus"></i>Create Account</a></li>

    <li class="menu-section">Dormitory</li>
    <li><a href="<?php echo BASE_URL; ?>admin/blocks.php" class="<?php echo $curPage==='blocks.php'?'active':''; ?>"><i class="fas fa-building"></i>Blocks</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/rooms.php" class="<?php echo $curPage==='rooms.php'?'active':''; ?>"><i class="fas fa-door-open"></i>Rooms</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/allocations.php" class="<?php echo $curPage==='allocations.php'?'active':''; ?>"><i class="fas fa-random"></i>Allocations</a></li>

    <li class="menu-section">Content</li>
    <li><a href="<?php echo BASE_URL; ?>admin/news.php" class="<?php echo $curPage==='news.php'?'active':''; ?>"><i class="fas fa-newspaper"></i>News &amp; Events</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/comments.php" class="<?php echo $curPage==='comments.php'?'active':''; ?>"><i class="fas fa-comments"></i>Comments</a></li>

    <li class="menu-section">System</li>
    <li><a href="<?php echo BASE_URL; ?>admin/reports.php" class="<?php echo $curPage==='reports.php'?'active':''; ?>"><i class="fas fa-chart-bar"></i>Reports</a></li>
    <li><a href="<?php echo BASE_URL; ?>admin/activity-log.php"><i class="fas fa-history"></i>Activity Log</a></li>
    <li><a href="<?php echo BASE_URL; ?>logout.php" style="color:rgba(255,100,100,.8)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</aside>
