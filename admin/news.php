<?php
$pageTitle = 'Manage News';
require_once '../includes/config.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM news_events WHERE id=$id");
    setFlash('success','Post deleted.'); redirect('admin/news.php');
}
$posts = $conn->query("SELECT n.*,u.fname,u.lname FROM news_events n LEFT JOIN users u ON n.posted_by=u.id ORDER BY n.created_at DESC");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-newspaper mr-2 text-primary"></i>News &amp; Events</h3></div>
    <a href="add-news.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Post New</a>
  </div>
  <?php echo getFlash(); ?>
  <div class="table-card">
    <div class="table-header"><h5><i class="fas fa-list mr-2"></i>All Posts</h5></div>
    <div class="table-responsive">
      <table class="table">
        <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Posted By</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
          <?php $i=1; while($p=$posts->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($p['title']); ?></strong></td>
            <td><span class="badge badge-<?php echo $p['type']==='news'?'info':($p['type']==='event'?'success':'warning'); ?>"><?php echo ucfirst($p['type']); ?></span></td>
            <td><?php echo htmlspecialchars($p['fname'].' '.$p['lname']); ?></td>
            <td><span class="badge badge-<?php echo $p['status']==='published'?'success':'secondary'; ?>"><?php echo ucfirst($p['status']); ?></span></td>
            <td><small><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></small></td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="edit-news.php?id=<?php echo $p['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                <a href="news.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i></a>
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
