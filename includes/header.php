<?php
if (!defined('BASE_URL')) require_once __DIR__ . '/config.php';
$currentUser = getCurrentUser();
$curPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle).' | ' : ''; ?><?php echo SITE_NAME; ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>

<nav class="uog-navbar navbar navbar-expand-lg">
  <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
    <span class="logo-circle" style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#e8a020,#f5c060);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;color:#0d2247;flex-shrink:0;">UG</span>
    <span class="brand-text ml-2">
      <span class="brand-title">University of Gondar</span>
      <span class="brand-sub">Dormitory Management System</span>
    </span>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" style="border-color:rgba(255,255,255,.4);">
    <i class="fas fa-bars text-white"></i>
  </button>

  <div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link <?php echo $curPage==='index.php'?'active':''; ?>" href="<?php echo BASE_URL; ?>"><i class="fas fa-home"></i> Home</a></li>
      <li class="nav-item"><a class="nav-link <?php echo $curPage==='about.php'?'active':''; ?>" href="<?php echo BASE_URL; ?>pages/about.php"><i class="fas fa-university"></i> About</a></li>
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/news.php"><i class="fas fa-newspaper"></i> News</a></li>
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/events.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>pages/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
    </ul>

    <ul class="navbar-nav">
      <?php if (isLoggedIn()): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-toggle="dropdown" style="gap:8px;">
            <span style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#e8a020,#f5c060);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:#0d2247;">
              <?php echo strtoupper(substr($currentUser['fname'],0,1)); ?>
            </span>
            <span>
              <strong style="display:block;font-size:13px;"><?php echo htmlspecialchars($currentUser['fname']); ?></strong>
              <small style="color:rgba(255,255,255,.65);font-size:10px;"><?php echo ucfirst(str_replace('_',' ',$currentUser['user_type'])); ?></small>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow" style="border:none;border-radius:12px;min-width:200px;">
            <?php
            $dashLinks = ['admin'=>'admin/dashboard.php','proctor_manager'=>'proctor-manager/dashboard.php','proctor'=>'proctor/dashboard.php','student_dean'=>'student-dean/dashboard.php','student'=>'student/dashboard.php'];
            $dl = $dashLinks[$currentUser['user_type']] ?? '';
            ?>
            <a class="dropdown-item" href="<?php echo BASE_URL.$dl; ?>"><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Dashboard</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
          </div>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL; ?>login.php"
             style="background:var(--accent);color:#fff!important;border-radius:8px;padding:8px 20px!important;font-weight:700;">
            <i class="fas fa-sign-in-alt"></i> Login
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
