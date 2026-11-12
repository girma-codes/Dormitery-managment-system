<?php
$pageTitle = 'Create User';
require_once '../includes/config.php';
requireRole('admin');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname     = sanitize($_POST['fname'] ?? '');
    $lname     = sanitize($_POST['lname'] ?? '');
    $uid_no    = sanitize($_POST['user_id_no'] ?? '');
    $username  = sanitize($_POST['username'] ?? '');
    $email     = sanitize($_POST['email'] ?? '');
    $sex       = sanitize($_POST['sex'] ?? '');
    $user_type = sanitize($_POST['user_type'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    if (!$fname)     $errors[] = 'First name is required.';
    if (!$lname)     $errors[] = 'Last name is required.';
    if (!$username)  $errors[] = 'Username is required.';
    if (!$user_type) $errors[] = 'User type is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)  $errors[] = 'Passwords do not match.';

    // Check unique username
    $chk = $conn->prepare("SELECT id FROM users WHERE username=?");
    $chk->bind_param('s', $username); $chk->execute();
    if ($chk->get_result()->num_rows > 0) $errors[] = 'Username already exists.';
    $chk->close();

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (fname,lname,user_id_no,username,password,email,sex,user_type) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssss', $fname, $lname, $uid_no, $username, $hashed, $email, $sex, $user_type);
        if ($stmt->execute()) {
            setFlash('success', "User account for $fname $lname created successfully!");
            redirect('admin/create-user.php');
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div>
      <h3><i class="fas fa-user-plus mr-2 text-primary"></i>Create User Account</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="users.php">Users</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
    <a href="users.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
  </div>
  <?php echo getFlash(); ?>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <strong><i class="fas fa-exclamation-triangle mr-2"></i>Please fix the following:</strong>
    <ul class="mb-0 mt-2"><?php foreach($errors as $e): ?><li><?php echo $e; ?></li><?php endforeach; ?></ul>
  </div>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="form-card">
        <div class="form-card-header">
          <i class="fas fa-user-plus mr-2"></i>New User Registration Form
        </div>
        <div class="form-card-body">
          <form action="create-user.php" method="POST">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">First Name</label>
                  <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($_POST['fname']??''); ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Last Name</label>
                  <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($_POST['lname']??''); ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Student/Staff ID No.</label>
                  <input type="text" name="user_id_no" class="form-control" placeholder="e.g. GUR/01867/09" value="<?php echo htmlspecialchars($_POST['user_id_no']??''); ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Username</label>
                  <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username']??''); ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Email Address</label>
                  <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email']??''); ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Gender</label>
                  <select name="sex" class="form-control custom-select">
                    <option value="">Select</option>
                    <option value="Male" <?php echo ($_POST['sex']??'')==='Male'?'selected':''; ?>>Male</option>
                    <option value="Female" <?php echo ($_POST['sex']??'')==='Female'?'selected':''; ?>>Female</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label class="required">User Type</label>
                  <select name="user_type" class="form-control custom-select" required>
                    <option value="">Select Role</option>
                    <option value="admin" <?php echo ($_POST['user_type']??'')==='admin'?'selected':''; ?>>Admin</option>
                    <option value="proctor_manager" <?php echo ($_POST['user_type']??'')==='proctor_manager'?'selected':''; ?>>Proctor Manager</option>
                    <option value="proctor" <?php echo ($_POST['user_type']??'')==='proctor'?'selected':''; ?>>Proctor</option>
                    <option value="student_dean" <?php echo ($_POST['user_type']??'')==='student_dean'?'selected':''; ?>>Student Dean</option>
                    <option value="student" <?php echo ($_POST['user_type']??'')==='student'?'selected':''; ?>>Student</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Password</label>
                  <div class="input-group">
                    <input type="password" id="pwd" name="password" class="form-control" required minlength="6">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#pwd"><i class="fas fa-eye"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Confirm Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-2" style="gap:10px;">
              <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Create Account</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
