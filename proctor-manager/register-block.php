<?php
$pageTitle = 'Register Block';
require_once '../includes/config.php';
requireRole('proctor_manager');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_number = sanitize($_POST['block_number'] ?? '');
    $block_name   = sanitize($_POST['block_name']   ?? '');
    $capacity     = (int)($_POST['capacity']        ?? 0);
    $gender       = sanitize($_POST['gender']       ?? 'Mixed');
    $description  = sanitize($_POST['description']  ?? '');

    if (!$block_number) $errors[] = 'Block number is required.';
    if ($capacity < 1)  $errors[] = 'Capacity must be greater than 0.';

    // Check duplicate
    if (empty($errors)) {
        $chk = $conn->prepare("SELECT id FROM blocks WHERE block_number=?");
        $chk->bind_param('s',$block_number); $chk->execute();
        if ($chk->get_result()->num_rows > 0) $errors[] = "Block $block_number already exists.";
        $chk->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO blocks (block_number,block_name,capacity,gender,description) VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('ssiss', $block_number,$block_name,$capacity,$gender,$description);
        if ($stmt->execute()) {
            setFlash('success', "Block $block_number registered successfully!");
            redirect('proctor-manager/register-block.php');
        } else { $errors[] = $conn->error; }
        $stmt->close();
    }
}
$recentBlocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY created_at DESC LIMIT 8");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-building mr-2 text-primary"></i>Register Block</h3></div>
    <a href="blocks.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-th-large mr-1"></i>View All Blocks
    </a>
  </div>

  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
  </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-5 mb-4">
      <div class="form-card">
        <div class="form-card-header"><i class="fas fa-plus mr-2"></i>New Block Information</div>
        <div class="form-card-body">
          <form method="POST">
            <div class="form-group">
              <label class="required">Block Number</label>
              <input type="text" name="block_number" class="form-control"
                     placeholder="e.g. B1" required
                     value="<?php echo htmlspecialchars($_POST['block_number']??''); ?>">
            </div>
            <div class="form-group">
              <label>Block Name</label>
              <input type="text" name="block_name" class="form-control"
                     placeholder="e.g. Block 1 - Male Students"
                     value="<?php echo htmlspecialchars($_POST['block_name']??''); ?>">
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="required">Capacity</label>
                  <input type="number" name="capacity" class="form-control"
                         min="1" required
                         value="<?php echo htmlspecialchars($_POST['capacity']??120); ?>">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label>Gender</label>
                  <select name="gender" class="form-control custom-select">
                    <option value="Male"   <?php echo ($_POST['gender']??'')==='Male'  ?'selected':''; ?>>Male</option>
                    <option value="Female" <?php echo ($_POST['gender']??'')==='Female'?'selected':''; ?>>Female</option>
                    <option value="Mixed"  <?php echo ($_POST['gender']??'')==='Mixed' ?'selected':''; ?>>Mixed</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="3"
                        placeholder="Optional..."><?php echo htmlspecialchars($_POST['description']??''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-save mr-2"></i>Register Block
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7 mb-4">
      <div class="table-card">
        <div class="table-header"><h5><i class="fas fa-building mr-2"></i>Registered Blocks</h5></div>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>Block No.</th><th>Name</th><th>Capacity</th><th>Gender</th><th>Status</th></tr></thead>
            <tbody>
              <?php while($b=$recentBlocks->fetch_assoc()): ?>
              <tr>
                <td><span class="badge badge-primary"><?php echo htmlspecialchars($b['block_number']); ?></span></td>
                <td><?php echo htmlspecialchars($b['block_name']??'-'); ?></td>
                <td><?php echo $b['capacity']; ?></td>
                <td><?php echo htmlspecialchars($b['gender']); ?></td>
                <td><span class="badge badge-<?php echo $b['status']==='active'?'success':'secondary'; ?>"><?php echo ucfirst($b['status']); ?></span></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
