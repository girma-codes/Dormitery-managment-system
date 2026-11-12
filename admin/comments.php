<?php
$pageTitle = 'Comments';
require_once '../includes/config.php';
requireRole('admin');
if (isset($_GET['approve'])) { $conn->query("UPDATE comments SET status='approved' WHERE id=".(int)$_GET['approve']); setFlash('success','Approved.'); redirect('admin/comments.php'); }
if (isset($_GET['delete']))  { $conn->query("DELETE FROM comments WHERE id=".(int)$_GET['delete']); setFlash('success','Deleted.'); redirect('admin/comments.php'); }
$comments = $conn->query("SELECT * FROM comments ORDER BY created_at DESC");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-comments mr-2 text-primary"></i>Manage Comments</h3></div></div>
  <?php echo getFlash(); ?>
  <div class="table-card">
    <div class="table-header"><h5>All Comments</h5></div>
    <div class="table-responsive">
      <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Comment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
          <?php $i=1; while($c=$comments->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($c['name']); ?></strong></td>
            <td><?php echo htmlspecialchars($c['email']??'-'); ?></td>
            <td style="max-width:300px;"><small><?php echo htmlspecialchars(substr($c['comment'],0,100)); ?>...</small></td>
            <td><span class="badge badge-<?php echo $c['status']==='approved'?'success':($c['status']==='pending'?'warning':'danger'); ?>"><?php echo ucfirst($c['status']); ?></span></td>
            <td><small><?php echo date('d/m/Y', strtotime($c['created_at'])); ?></small></td>
            <td>
              <div class="btn-group btn-group-sm">
                <?php if($c['status']==='pending'): ?><a href="comments.php?approve=<?php echo $c['id']; ?>" class="btn btn-success"><i class="fas fa-check"></i></a><?php endif; ?>
                <a href="comments.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i></a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
