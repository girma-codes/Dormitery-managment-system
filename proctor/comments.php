<?php
$pageTitle = 'Comments';
require_once '../includes/config.php';
requireRole('proctor');

$comments = $conn->query("
    SELECT c.*, u.fname, u.lname
    FROM comments c
    LEFT JOIN users u ON c.user_id=u.id
    ORDER BY c.created_at DESC
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-comments mr-2 text-primary"></i>Student Comments</h3></div></div>
  <div class="row">
    <?php while($c=$comments->fetch_assoc()): ?>
    <div class="col-lg-6 mb-4">
      <div class="card" style="border-left:4px solid <?php echo $c['status']==='pending'?'var(--accent)':($c['status']==='approved'?'var(--success)':'var(--danger)'); ?>;">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <strong><?php echo htmlspecialchars($c['name']); ?></strong>
            <span class="badge badge-<?php echo $c['status']==='approved'?'success':($c['status']==='pending'?'warning':'danger'); ?>"><?php echo ucfirst($c['status']); ?></span>
          </div>
          <p style="font-size:13.5px;background:#f8faff;padding:10px;border-radius:8px;margin-bottom:8px;">"<?php echo htmlspecialchars($c['comment']); ?>"</p>
          <small style="color:var(--muted);"><i class="fas fa-clock mr-1"></i><?php echo date('d M Y', strtotime($c['created_at'])); ?></small>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
