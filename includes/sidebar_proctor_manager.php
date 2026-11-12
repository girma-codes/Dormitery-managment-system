<?php $currentUser = getCurrentUser(); $curPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($currentUser['fname'],0,1)); ?></div>
    <div>
      <div class="user-name"><?php echo htmlspecialchars($currentUser['fname'].' '.$currentUser['lname']); ?></div>
      <div class="user-role">Proctor Manager</div>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-section">Main</li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/dashboard.php" class="<?php echo $curPage==='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>

    <li class="menu-section">Dormitory</li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/register-block.php" class="<?php echo $curPage==='register-block.php'?'active':''; ?>"><i class="fas fa-building"></i>Register Block</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/blocks.php" class="<?php echo $curPage==='blocks.php'?'active':''; ?>"><i class="fas fa-th-large"></i>View Blocks</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/allocate-proctor.php" class="<?php echo $curPage==='allocate-proctor.php'?'active':''; ?>"><i class="fas fa-user-tie"></i>Assign Proctor</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/view-students.php" class="<?php echo $curPage==='view-students.php'?'active':''; ?>"><i class="fas fa-users"></i>View Students</a></li>

    <li class="menu-section">Reports & Feedback</li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/report.php" class="<?php echo $curPage==='report.php'?'active':''; ?>"><i class="fas fa-chart-bar"></i>Generate Report</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/comments.php" class="<?php echo $curPage==='comments.php'?'active':''; ?>"><i class="fas fa-comments"></i>View Comments</a></li>

    <li class="menu-section">Account</li>
    <li><a href="<?php echo BASE_URL; ?>proctor-manager/change-password.php"><i class="fas fa-key"></i>Change Password</a></li>
    <li><a href="<?php echo BASE_URL; ?>logout.php" style="color:rgba(255,100,100,.8)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</aside>
