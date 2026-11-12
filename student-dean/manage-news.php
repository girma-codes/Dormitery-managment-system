<?php
$pageTitle = 'Manage Posts';
require_once '../includes/config.php';
requireRole('student_dean');
$uid = $_SESSION['user_id'];
if (isset($_GET['delete'])) { $conn->query("DELETE FROM news_events WHERE id=".(int)$_GET['delete']." AND posted_by=$uid"); setFlash('success','Deleted.'); redirect('student-dean/manage-news.php'); }
$posts = $conn->query("SELECT * FROM news_events WHERE posted_by=$uid ORDER BY created_at DESC");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student_dean.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-newspaper mr-2 text-primary"></i>My Posts</h3></div><a href="post-news.php" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>New Post</a></div>
  <?php echo getFlash(); ?>
  <div class="table-card">
    <div class="table-header"><h5>All My Posts</h5></div>
    <div class="table-responsive"><table class="table">
      <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody><?php $i=1; while($p=$posts->fetch_assoc()): ?><tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo htmlspecialchars($p['title']); ?></td>
        <td><span class="badge badge-info"><?php echo ucfirst($p['type']); ?></span></td>
        <td><span class="badge badge-<?php echo $p['status']==='published'?'success':'secondary'; ?>"><?php echo ucfirst($p['status']); ?></span></td>
        <td><small><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></small></td>
        <td><div class="btn-group btn-group-sm"><a href="edit-news.php?id=<?php echo $p['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a><a href="manage-news.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i></a></div></td>
      </tr><?php endwhile; ?></tbody>
    </table></div>
  </div>
</div></div>
<?php require_once '../includes/footer.php'; ?>
