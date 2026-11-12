<?php
$pageTitle = 'Student Dean Dashboard';
require_once '../includes/config.php';
requireRole('student_dean');
$uid = $_SESSION['user_id'];
$myPosts   = $conn->query("SELECT COUNT(*) c FROM news_events WHERE posted_by=$uid")->fetch_assoc()['c'];
$pendComm  = $conn->query("SELECT COUNT(*) c FROM comments WHERE status='pending'")->fetch_assoc()['c'];
$recentPost= $conn->query("SELECT * FROM news_events WHERE posted_by=$uid ORDER BY created_at DESC LIMIT 5");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student_dean.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Student Dean Dashboard</h3><p style="color:var(--muted);margin:0;font-size:13px;">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</p></div></div>
  <?php echo getFlash(); ?>
  <div class="row mb-4">
    <div class="col-md-4 mb-3"><div class="widget-card wc-blue"><div class="w-icon"><i class="fas fa-newspaper"></i></div><div class="w-num"><?php echo $myPosts; ?></div><div class="w-label">My Posts</div></div></div>
    <div class="col-md-4 mb-3"><div class="widget-card wc-orange"><div class="w-icon"><i class="fas fa-comments"></i></div><div class="w-num"><?php echo $pendComm; ?></div><div class="w-label">Pending Comments</div></div></div>
    <div class="col-md-4 mb-3"><div class="widget-card wc-green"><a href="post-news.php" style="text-decoration:none;color:#fff;"><div class="w-icon"><i class="fas fa-plus-circle"></i></div><div class="w-num"><i class="fas fa-plus"></i></div><div class="w-label">Post New Content</div></a></div></div>
  </div>
  <div class="d-flex mb-4" style="gap:10px;">
    <a href="post-news.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Post News/Event</a>
    <a href="comments.php" class="btn btn-warning"><i class="fas fa-comments mr-1"></i>View Comments <?php if($pendComm>0): ?><span class="badge badge-dark"><?php echo $pendComm; ?></span><?php endif; ?></a>
  </div>
  <div class="table-card">
    <div class="table-header"><h5><i class="fas fa-list mr-2"></i>My Recent Posts</h5><a href="manage-news.php" class="btn btn-sm btn-light" style="font-size:12px;">View All</a></div>
    <div class="table-responsive"><table class="table">
      <thead><tr><th>#</th><th>Title</th><th>Type</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody><?php $i=1; while($p=$recentPost->fetch_assoc()): ?><tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo htmlspecialchars($p['title']); ?></td>
        <td><span class="badge badge-<?php echo $p['type']==='news'?'info':($p['type']==='event'?'success':'warning'); ?>"><?php echo ucfirst($p['type']); ?></span></td>
        <td><span class="badge badge-<?php echo $p['status']==='published'?'success':'secondary'; ?>"><?php echo ucfirst($p['status']); ?></span></td>
        <td><small><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></small></td>
        <td><a href="edit-news.php?id=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a></td>
      </tr><?php endwhile; ?></tbody>
    </table></div>
  </div>
</div></div>
<?php require_once '../includes/footer.php'; ?>
