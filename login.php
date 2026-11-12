<?php
$pageTitle = 'Login';
require_once 'includes/config.php';

if (isLoggedIn()) {
    $r = ['admin'=>'admin/dashboard.php','proctor_manager'=>'proctor-manager/dashboard.php','proctor'=>'proctor/dashboard.php','student_dean'=>'student-dean/dashboard.php','student'=>'student/dashboard.php'];
    redirect($r[$_SESSION['user_type']] ?? '');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = sanitize($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $user_type = sanitize($_POST['user_type'] ?? '');

    if (!$username || !$password) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND status='active'");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            if ($user_type && $user['user_type'] !== $user_type) {
                $error = 'The selected user type does not match this account.';
            } else {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['fname']     = $user['fname'];
                $_SESSION['lname']     = $user['lname'];
                $uid = $user['id']; $ip = $_SERVER['REMOTE_ADDR'];
                $conn->query("INSERT INTO activity_log(user_id,action,ip_address) VALUES($uid,'User logged in','$ip')");
                $r = ['admin'=>'admin/dashboard.php','proctor_manager'=>'proctor-manager/dashboard.php','proctor'=>'proctor/dashboard.php','student_dean'=>'student-dean/dashboard.php','student'=>'student/dashboard.php'];
                redirect($r[$user['user_type']]);
            }
        } else {
            $error = 'Invalid username or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | <?php echo SITE_NAME; ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>
<div class="login-page">
  <!-- LEFT PANEL -->
  <div class="login-left">
    <div class="logo-wrap"><i class="fas fa-university"></i></div>
    <h2>University of Gondar</h2>
    <p style="font-size:14px;color:rgba(255,255,255,.75);">Atse Tewodros Campus<br>Dormitory Management System</p>

    <ul class="login-features">
      <li><i class="fas fa-check-circle"></i> Online dormitory allocation</li>
      <li><i class="fas fa-check-circle"></i> Real-time news &amp; events</li>
      <li><i class="fas fa-check-circle"></i> Attendance management</li>
      <li><i class="fas fa-check-circle"></i> Report generation</li>
      <li><i class="fas fa-check-circle"></i> Secure role-based access</li>
    </ul>

    <a href="<?php echo BASE_URL; ?>" style="margin-top:30px;color:rgba(255,255,255,.6);font-size:13px;text-decoration:none;">
      <i class="fas fa-arrow-left mr-1"></i> Back to Homepage
    </a>
  </div>

  <!-- RIGHT PANEL -->
  <div class="login-right">
    <h3>Welcome Back!</h3>
    <p class="subtitle">Sign in to your UoG-DMS account to continue.</p>

    <?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center" style="gap:10px;">
      <i class="fas fa-exclamation-circle fa-lg"></i>
      <span><?php echo htmlspecialchars($error); ?></span>
    </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group">
        <label class="required">Username</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;"><i class="fas fa-user" style="color:var(--primary);"></i></span>
          </div>
          <input type="text" name="username" class="form-control" placeholder="Enter your username"
                 value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required
                 style="border-left:none;border-radius:0 8px 8px 0;">
        </div>
      </div>

      <div class="form-group">
        <label class="required">Password</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;"><i class="fas fa-lock" style="color:var(--primary);"></i></span>
          </div>
          <input type="password" name="password" id="pwd" class="form-control" placeholder="Enter your password" required
                 style="border-left:none;border-right:none;border-radius:0;">
          <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#pwd"
                    style="border-radius:0 8px 8px 0;border-left:none;">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>User Type <small style="color:var(--muted);font-weight:400;">(optional)</small></label>
        <select name="user_type" class="form-control custom-select">
          <option value="">-- All User Types --</option>
          <option value="admin" <?php echo ($_POST['user_type']??'')==='admin'?'selected':''; ?>>Administrator</option>
          <option value="proctor_manager" <?php echo ($_POST['user_type']??'')==='proctor_manager'?'selected':''; ?>>Proctor Manager</option>
          <option value="proctor" <?php echo ($_POST['user_type']??'')==='proctor'?'selected':''; ?>>Proctor</option>
          <option value="student_dean" <?php echo ($_POST['user_type']??'')==='student_dean'?'selected':''; ?>>Student Dean</option>
          <option value="student" <?php echo ($_POST['user_type']??'')==='student'?'selected':''; ?>>Student</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary btn-block mt-2" style="padding:13px;font-size:15px;font-weight:700;border-radius:10px;">
        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
      </button>
    </form>

    <div style="margin-top:28px;padding:14px 18px;background:#f8faff;border-radius:10px;border:1px solid #dde3ec;">
      <p style="font-size:11px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Demo Credentials</p>
      <div style="font-size:12px;color:var(--muted);display:grid;grid-template-columns:1fr 1fr;gap:4px 16px;">
        <span><strong>Admin:</strong> admin / password</span>
        <span><strong>Proctor:</strong> proctor1 / password</span>
        <span><strong>Pr. Mgr:</strong> proctor_mgr / password</span>
        <span><strong>Student:</strong> student1 / password</span>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/main.js"></script>
</body>
</html>
