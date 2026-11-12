<?php
$pageTitle = 'Change Password';
require_once '../includes/config.php';
requireRole('proctor_manager');
require_once '../includes/change_password.php';
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-key mr-2 text-primary"></i>Change Password</h3></div></div>
  <div class="row justify-content-center"><div class="col-lg-6">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-lock mr-2"></i>Update Your Password</div>
      <div class="form-card-body">
        <?php if (!empty($cpSuccess)): ?><div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i><?php echo $cpSuccess; ?></div><?php endif; ?>
        <?php if (!empty($cpErrors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($cpErrors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>
        <form method="POST">
          <div class="form-group"><label class="required">Current Password</label><input type="password" name="current_password" class="form-control" required></div>
          <div class="form-group"><label class="required">New Password</label><input type="password" name="new_password" class="form-control" required minlength="6"></div>
          <div class="form-group"><label class="required">Confirm New Password</label><input type="password" name="confirm_password" class="form-control" required></div>
          <button type="submit" name="change_password" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update Password</button>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
