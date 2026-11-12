<?php
$pageTitle = 'Post News';
require_once '../includes/config.php';
requireRole('student_dean');
$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title   = sanitize($_POST['title']??'');
    $content = $_POST['content']??'';
    $type    = sanitize($_POST['type']??'news');
    $date    = sanitize($_POST['event_date']??'');
    $status  = sanitize($_POST['status']??'published');
    $uid     = $_SESSION['user_id'];
    if (!$title) $errors[]='Title required.';
    if (!$content) $errors[]='Content required.';
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO news_events(title,content,type,event_date,posted_by,status) VALUES(?,?,?,?,?,?)");
        $d = $date ?: null;
        $stmt->bind_param('ssssss',$title,$content,$type,$d,$uid,$status);
        if ($stmt->execute()) { setFlash('success','Post published successfully!'); redirect('student-dean/post-news.php'); }
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student_dean.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-plus-circle mr-2 text-primary"></i>Post News / Event</h3></div><a href="manage-news.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a></div>
  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>
  <div class="row justify-content-center"><div class="col-lg-8">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-newspaper mr-2"></i>New Post</div>
      <div class="form-card-body">
        <form method="POST">
          <div class="form-group"><label class="required">Title</label><input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($_POST['title']??''); ?>"></div>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label>Type</label><select name="type" class="form-control custom-select"><option value="news">News</option><option value="event">Event</option><option value="announcement">Announcement</option></select></div></div>
            <div class="col-md-4"><div class="form-group"><label>Event Date</label><input type="date" name="event_date" class="form-control"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Status</label><select name="status" class="form-control custom-select"><option value="published">Published</option><option value="draft">Draft</option></select></div></div>
          </div>
          <div class="form-group"><label class="required">Content</label><textarea name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($_POST['content']??''); ?></textarea></div>
          <div class="d-flex justify-content-end" style="gap:10px;"><a href="manage-news.php" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Publish</button></div>
        </form>
      </div>
    </div>
  </div></div>
</div></div>
<?php require_once '../includes/footer.php'; ?>
