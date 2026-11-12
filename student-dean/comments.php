<?php
$pageTitle = 'View Comments';
require_once '../includes/config.php';
requireRole('student_dean');

if (isset($_GET['approve'])) { $conn->query("UPDATE comments SET status='approved' WHERE id=".(int)$_GET['approve']); setFlash('success','Approved.'); redirect('student-dean/comments.php'); }
if (isset($_GET['reject']))  { $conn->query("UPDATE comments SET status='rejected' WHERE id=".(int)$_GET['reject']);  setFlash('success','Rejected.'); redirect('student-dean/comments.php'); }

$comments = $conn->query("SELECT * FROM comments ORDER BY created_at DESC");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student_dean.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-comments mr-2 text-primary"></i>Student Comments</h3></div></div>
  <?php echo getFlash(); ?>
  <div class="row">
    <?php while($c=$comments->fetch_assoc()): ?>
    <div class="col-lg-6 mb-4">
      <div class="card" style="border-left:4px solid <?php echo $c['status']==='pending'?'var(--accent)':($c['status']==='approved'?'var(--success)':'var(--danger)'); ?>;">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <strong><?php echo htmlspecialchars($c['name']); ?></strong>
            <span class="badge badge-<?php echo $c['status']==='approved'?'success':($c['status']==='pending'?'warning':'danger'); ?>"><?php echo ucfirst($c['status']); ?></span>
          </div>
          <p style="font-size:13.5px;background:#f8faff;padding:10px;border-radius:8px;">"<?php echo htmlspecialchars($c['comment']); ?>"</p>
          <div class="d-flex justify-content-between align-items-center">
            <small style="color:var(--muted);"><i class="fas fa-clock mr-1"></i><?php echo date('d M Y', strtotime($c['created_at'])); ?></small>
            <?php if($c['status']==='pending'): ?>
            <div class="btn-group btn-group-sm">
              <a href="comments.php?approve=<?php echo $c['id']; ?>" class="btn btn-success"><i class="fas fa-check mr-1"></i>Approve</a>
              <a href="comments.php?reject=<?php echo $c['id']; ?>"  class="btn btn-danger"><i class="fas fa-times mr-1"></i>Reject</a>
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
