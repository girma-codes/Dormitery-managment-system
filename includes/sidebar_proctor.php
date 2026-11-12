<?php $currentUser = getCurrentUser(); $curPage = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
  <div class="sidebar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($currentUser['fname'],0,1)); ?></div>
    <div>
      <div class="user-name"><?php echo htmlspecialchars($currentUser['fname'].' '.$currentUser['lname']); ?></div>
      <div class="user-role">Proctor</div>
    </div>
  </div>
  <ul class="sidebar-menu">
    <li class="menu-section">Main</li>
    <li><a href="<?php echo BASE_URL; ?>proctor/dashboard.php" class="<?php echo $curPage==='dashboard.php'?'active':''; ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>

    <li class="menu-section">Dormitory</li>
    <li><a href="<?php echo BASE_URL; ?>proctor/register-room.php" class="<?php echo $curPage==='register-room.php'?'active':''; ?>"><i class="fas fa-door-open"></i>Register Room</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor/allocate-student.php" class="<?php echo $curPage==='allocate-student.php'?'active':''; ?>"><i class="fas fa-user-plus"></i>Assign Student</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor/view-students.php" class="<?php echo $curPage==='view-students.php'?'active':''; ?>"><i class="fas fa-users"></i>View Students</a></li>

    <li class="menu-section">Management</li>
    <li><a href="<?php echo BASE_URL; ?>proctor/attendance.php" class="<?php echo $curPage==='attendance.php'?'active':''; ?>"><i class="fas fa-clipboard-check"></i>Attendance</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor/comments.php" class="<?php echo $curPage==='comments.php'?'active':''; ?>"><i class="fas fa-comments"></i>Comments</a></li>
    <li><a href="<?php echo BASE_URL; ?>proctor/report.php" class="<?php echo $curPage==='report.php'?'active':''; ?>"><i class="fas fa-chart-bar"></i>Generate Report</a></li>

    <li class="menu-section">Account</li>
    <li><a href="<?php echo BASE_URL; ?>proctor/change-password.php"><i class="fas fa-key"></i>Change Password</a></li>
    <li><a href="<?php echo BASE_URL; ?>logout.php" style="color:rgba(255,100,100,.8)!important;"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
  </ul>
</aside>
