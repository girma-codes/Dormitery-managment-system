<?php
$pageTitle = 'Edit Block';
require_once '../includes/config.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid.'); redirect('admin/blocks.php'); }
$block = $conn->query("SELECT * FROM blocks WHERE id=$id")->fetch_assoc();
if (!$block) { setFlash('danger','Block not found.'); redirect('admin/blocks.php'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_number = sanitize($_POST['block_number'] ?? '');
    $block_name   = sanitize($_POST['block_name']   ?? '');
    $capacity     = (int)($_POST['capacity']        ?? 0);
    $gender       = sanitize($_POST['gender']       ?? '');
    $proctor_id   = (int)($_POST['proctor_id']      ?? 0);
    $description  = sanitize($_POST['description']  ?? '');
    $status       = sanitize($_POST['status']       ?? 'active');

    if (!$block_number) $errors[] = 'Block number required.';
    if ($capacity < 1)  $errors[] = 'Capacity must be > 0.';

    if (empty($errors)) {
        $pid = $proctor_id ?: null;
        $stmt = $conn->prepare(
            "UPDATE blocks SET block_number=?,block_name=?,capacity=?,gender=?,proctor_id=?,description=?,status=? WHERE id=?"
        );
        $stmt->bind_param('ssissssi',
            $block_number,$block_name,$capacity,$gender,$pid,$description,$status,$id);
        if ($stmt->execute()) { setFlash('success','Block updated!'); redirect('admin/blocks.php'); }
        $errors[] = $conn->error;
        $stmt->close();
    }
}
$proctors = $conn->query("SELECT id,fname,lname FROM users WHERE user_type='proctor' AND status='active' ORDER BY fname");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-edit mr-2 text-primary"></i>Edit Block</h3></div>
    <a href="blocks.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
  </div>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>
  <div class="row justify-content-center"><div class="col-lg-7">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-building mr-2"></i>Block Details</div>
      <div class="form-card-body">
        <form method="POST">
          <div class="row">
            <div class="col-md-6"><div class="form-group"><label class="required">Block Number</label><input type="text" name="block_number" class="form-control" required value="<?php echo htmlspecialchars($block['block_number']); ?>"></div></div>
            <div class="col-md-6"><div class="form-group"><label>Block Name</label><input type="text" name="block_name" class="form-control" value="<?php echo htmlspecialchars($block['block_name']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label class="required">Capacity</label><input type="number" name="capacity" class="form-control" required value="<?php echo $block['capacity']; ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Gender</label><select name="gender" class="form-control custom-select"><option value="Male" <?php echo $block['gender']==='Male'?'selected':''; ?>>Male</option><option value="Female" <?php echo $block['gender']==='Female'?'selected':''; ?>>Female</option><option value="Mixed" <?php echo $block['gender']==='Mixed'?'selected':''; ?>>Mixed</option></select></div></div>
            <div class="col-md-4"><div class="form-group"><label>Status</label><select name="status" class="form-control custom-select"><option value="active" <?php echo $block['status']==='active'?'selected':''; ?>>Active</option><option value="inactive" <?php echo $block['status']==='inactive'?'selected':''; ?>>Inactive</option></select></div></div>
            <div class="col-12"><div class="form-group"><label>Assign Proctor</label><select name="proctor_id" class="form-control custom-select"><option value="">None</option><?php while($p=$proctors->fetch_assoc()): ?><option value="<?php echo $p['id']; ?>" <?php echo $block['proctor_id']==$p['id']?'selected':''; ?>><?php echo htmlspecialchars($p['fname'].' '.$p['lname']); ?></option><?php endwhile; ?></select></div></div>
            <div class="col-12"><div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($block['description']??''); ?></textarea></div></div>
          </div>
          <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="blocks.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update Block</button>
          </div>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
