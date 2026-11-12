<?php
$pageTitle = 'Edit News';
require_once '../includes/config.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if (!$id) { setFlash('danger','Invalid.'); redirect('admin/news.php'); }
$post = $conn->query("SELECT * FROM news_events WHERE id=$id")->fetch_assoc();
if (!$post) { setFlash('danger','Post not found.'); redirect('admin/news.php'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = sanitize($_POST['title']   ?? '');
    $content = $_POST['content'] ?? '';
    $type    = sanitize($_POST['type']    ?? 'news');
    $date    = sanitize($_POST['event_date'] ?? '');
    $status  = sanitize($_POST['status'] ?? 'published');
    if (!$title)   $errors[] = 'Title required.';
    if (!$content) $errors[] = 'Content required.';
    if (empty($errors)) {
        $d = $date ?: null;
        $stmt = $conn->prepare("UPDATE news_events SET title=?,content=?,type=?,event_date=?,status=? WHERE id=?");
        $stmt->bind_param('sssssi',$title,$content,$type,$d,$status,$id);
        if ($stmt->execute()) { setFlash('success','Post updated!'); redirect('admin/news.php'); }
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-edit mr-2 text-primary"></i>Edit Post</h3></div>
    <a href="news.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
  </div>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>
  <div class="row justify-content-center"><div class="col-lg-8">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-newspaper mr-2"></i>Edit News / Event</div>
      <div class="form-card-body">
        <form method="POST">
          <div class="form-group"><label class="required">Title</label><input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($_POST['title']??$post['title']); ?>"></div>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label>Type</label><select name="type" class="form-control custom-select"><option value="news" <?php echo ($post['type']==='news')?'selected':''; ?>>News</option><option value="event" <?php echo ($post['type']==='event')?'selected':''; ?>>Event</option><option value="announcement" <?php echo ($post['type']==='announcement')?'selected':''; ?>>Announcement</option></select></div></div>
            <div class="col-md-4"><div class="form-group"><label>Event Date</label><input type="date" name="event_date" class="form-control" value="<?php echo $post['event_date']??''; ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Status</label><select name="status" class="form-control custom-select"><option value="published" <?php echo $post['status']==='published'?'selected':''; ?>>Published</option><option value="draft" <?php echo $post['status']==='draft'?'selected':''; ?>>Draft</option></select></div></div>
          </div>
          <div class="form-group"><label class="required">Content</label><textarea name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($_POST['content']??$post['content']); ?></textarea></div>
          <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="news.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update Post</button>
          </div>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
