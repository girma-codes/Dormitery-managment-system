<?php
$pageTitle = 'View Comments';
require_once '../includes/config.php';
requireRole('proctor_manager');

if (isset($_GET['approve'])) {
    $conn->query("UPDATE comments SET status='approved' WHERE id=".(int)$_GET['approve']);
    setFlash('success','Comment approved.'); redirect('proctor-manager/comments.php');
}
if (isset($_GET['reject'])) {
    $conn->query("UPDATE comments SET status='rejected' WHERE id=".(int)$_GET['reject']);
    setFlash('success','Comment rejected.'); redirect('proctor-manager/comments.php');
}

$status = sanitize($_GET['status'] ?? '');
$sql = "SELECT c.*, u.fname, u.lname FROM comments c LEFT JOIN users u ON c.user_id=u.id";
if ($status) $sql .= " WHERE c.status='$status'";
$sql .= " ORDER BY c.created_at DESC";
$comments = $conn->query($sql);
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-comments mr-2 text-primary"></i>Student Comments</h3></div>
    <div class="btn-group btn-group-sm no-print">
      <a href="comments.php" class="btn btn-<?php echo !$status?'primary':'outline-primary'; ?>">All</a>
      <a href="comments.php?status=pending"  class="btn btn-<?php echo $status==='pending' ?'primary':'outline-primary'; ?>">Pending</a>
      <a href="comments.php?status=approved" class="btn btn-<?php echo $status==='approved'?'primary':'outline-primary'; ?>">Approved</a>
    </div>
  </div>

  <?php echo getFlash(); ?>

  <div class="row">
    <?php while($c=$comments->fetch_assoc()): ?>
    <div class="col-lg-6 mb-4">
      <div class="card h-100" style="border-left:4px solid <?php echo $c['status']==='pending'?'var(--accent)':($c['status']==='approved'?'var(--success)':'var(--danger)'); ?>;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center" style="gap:10px;">
              <span style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700;flex-shrink:0;">
                <?php echo strtoupper(substr($c['name'],0,1)); ?>
              </span>
              <div>
                <strong style="font-size:14px;"><?php echo htmlspecialchars($c['name']); ?></strong><br>
                <small style="color:var(--muted);"><?php echo htmlspecialchars($c['email']??''); ?></small>
              </div>
            </div>
            <span class="badge badge-<?php echo $c['status']==='pending'?'warning':($c['status']==='approved'?'success':'danger'); ?>">
              <?php echo ucfirst($c['status']); ?>
            </span>
          </div>

          <p style="font-size:13.5px;color:var(--text);background:#f8faff;padding:12px;border-radius:8px;margin:12px 0;">
            "<?php echo htmlspecialchars($c['comment']); ?>"
          </p>

          <div class="d-flex justify-content-between align-items-center">
            <small style="color:var(--muted);">
              <i class="fas fa-clock mr-1"></i>
              <?php echo date('d M Y, H:i', strtotime($c['created_at'])); ?>
            </small>
            <?php if($c['status'] === 'pending'): ?>
            <div class="btn-group btn-group-sm">
              <a href="comments.php?approve=<?php echo $c['id']; ?>" class="btn btn-success">
                <i class="fas fa-check mr-1"></i>Approve
              </a>
              <a href="comments.php?reject=<?php echo $c['id']; ?>" class="btn btn-danger">
                <i class="fas fa-times mr-1"></i>Reject
              </a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
