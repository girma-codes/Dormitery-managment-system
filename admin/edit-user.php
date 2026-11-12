<?php
$pageTitle = 'Edit User';
require_once '../includes/config.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid user.'); redirect('admin/users.php'); }

$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
if (!$user) { setFlash('danger','User not found.'); redirect('admin/users.php'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname     = sanitize($_POST['fname']     ?? '');
    $lname     = sanitize($_POST['lname']     ?? '');
    $uid_no    = sanitize($_POST['user_id_no']?? '');
    $email     = sanitize($_POST['email']     ?? '');
    $sex       = sanitize($_POST['sex']       ?? '');
    $user_type = sanitize($_POST['user_type'] ?? '');
    $status    = sanitize($_POST['status']    ?? 'active');

    if (!$fname || !$lname) $errors[] = 'First and last name required.';
    if (!$user_type)        $errors[] = 'User type required.';

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "UPDATE users SET fname=?,lname=?,user_id_no=?,email=?,sex=?,user_type=?,status=? WHERE id=?"
        );
        $stmt->bind_param('sssssssi',
            $fname,$lname,$uid_no,$email,$sex,$user_type,$status,$id);
        if ($stmt->execute()) {
            setFlash('success','User updated successfully!');
            redirect('admin/users.php');
        } else { $errors[] = $conn->error; }
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-user-edit mr-2 text-primary"></i>Edit User</h3></div>
    <a href="users.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i>Back
    </a>
  </div>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
  </div>
  <?php endif; ?>

  <div class="row justify-content-center"><div class="col-lg-8">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-user-edit mr-2"></i>Edit Account</div>
      <div class="form-card-body">
        <form method="POST">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="fname" class="form-control" required
                       value="<?php echo htmlspecialchars($user['fname']); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="lname" class="form-control" required
                       value="<?php echo htmlspecialchars($user['lname']); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>ID No.</label>
                <input type="text" name="user_id_no" class="form-control"
                       value="<?php echo htmlspecialchars($user['user_id_no']??''); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email']??''); ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Gender</label>
                <select name="sex" class="form-control custom-select">
                  <option value="">Select</option>
                  <option value="Male"   <?php echo $user['sex']==='Male'  ?'selected':''; ?>>Male</option>
                  <option value="Female" <?php echo $user['sex']==='Female'?'selected':''; ?>>Female</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">User Type</label>
                <select name="user_type" class="form-control custom-select" required>
                  <option value="admin"           <?php echo $user['user_type']==='admin'          ?'selected':''; ?>>Admin</option>
                  <option value="proctor_manager" <?php echo $user['user_type']==='proctor_manager'?'selected':''; ?>>Proctor Manager</option>
                  <option value="proctor"         <?php echo $user['user_type']==='proctor'        ?'selected':''; ?>>Proctor</option>
                  <option value="student_dean"    <?php echo $user['user_type']==='student_dean'   ?'selected':''; ?>>Student Dean</option>
                  <option value="student"         <?php echo $user['user_type']==='student'        ?'selected':''; ?>>Student</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control custom-select">
                  <option value="active"   <?php echo $user['status']==='active'  ?'selected':''; ?>>Active</option>
                  <option value="inactive" <?php echo $user['status']==='inactive'?'selected':''; ?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-2"></i>Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
