<?php
$pageTitle = 'Assign Proctor to Block';
require_once '../includes/config.php';
requireRole('proctor_manager');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_id   = (int)($_POST['block_id']   ?? 0);
    $proctor_id = (int)($_POST['proctor_id'] ?? 0);
    if (!$block_id || !$proctor_id) {
        $errors[] = 'Please select both a block and a proctor.';
    } else {
        $conn->query("UPDATE blocks SET proctor_id=$proctor_id WHERE id=$block_id");
        setFlash('success', 'Proctor assigned to block successfully!');
        redirect('proctor-manager/allocate-proctor.php');
    }
}

$blocks   = $conn->query("
    SELECT b.*, u.fname AS p_fname, u.lname AS p_lname
    FROM blocks b
    LEFT JOIN users u ON b.proctor_id=u.id
    WHERE b.status='active' ORDER BY b.block_number
");
$proctors = $conn->query("SELECT * FROM users WHERE user_type='proctor' AND status='active' ORDER BY fname");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-user-tie mr-2 text-primary"></i>Assign Proctor to Block</h3></div>
  </div>

  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><?php echo $errors[0]; ?></div><?php endif; ?>

  <div class="row">
    <div class="col-lg-5 mb-4">
      <div class="form-card">
        <div class="form-card-header"><i class="fas fa-random mr-2"></i>Proctor Assignment</div>
        <div class="form-card-body">
          <form method="POST">
            <div class="form-group">
              <label class="required">Select Block</label>
              <select name="block_id" class="form-control custom-select" required>
                <option value="">Choose a block...</option>
                <?php $blocks->data_seek(0); while($b=$blocks->fetch_assoc()): ?>
                <option value="<?php echo $b['id']; ?>">
                  <?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
                  <?php if($b['p_fname']): ?>(Current: <?php echo htmlspecialchars($b['p_fname'].' '.$b['p_lname']); ?>)<?php endif; ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="required">Select Proctor</label>
              <select name="proctor_id" class="form-control custom-select" required>
                <option value="">Choose a proctor...</option>
                <?php while($p=$proctors->fetch_assoc()): ?>
                <option value="<?php echo $p['id']; ?>">
                  <?php echo htmlspecialchars($p['fname'].' '.$p['lname']); ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-check mr-2"></i>Assign Proctor
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7 mb-4">
      <div class="table-card">
        <div class="table-header"><h5><i class="fas fa-list mr-2"></i>Block-Proctor Assignments</h5></div>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>Block</th><th>Gender</th><th>Assigned Proctor</th><th>Status</th></tr></thead>
            <tbody>
              <?php $blocks->data_seek(0); while($b=$blocks->fetch_assoc()): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?></strong></td>
                <td><?php echo htmlspecialchars($b['gender']); ?></td>
                <td>
                  <?php if($b['p_fname']): ?>
                  <div class="d-flex align-items-center" style="gap:8px;">
                    <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--success),#1e8449);display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;">
                      <?php echo strtoupper(substr($b['p_fname'],0,1)); ?>
                    </span>
                    <?php echo htmlspecialchars($b['p_fname'].' '.$b['p_lname']); ?>
                  </div>
                  <?php else: ?>
                  <span class="text-muted"><i class="fas fa-minus-circle mr-1"></i>Not assigned</span>
                  <?php endif; ?>
                </td>
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
