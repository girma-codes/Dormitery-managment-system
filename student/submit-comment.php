<?php
$pageTitle = 'Submit Comment';
require_once '../includes/config.php';
requireRole('student');
$stud = $conn->query("SELECT * FROM users WHERE id={$_SESSION['user_id']}")->fetch_assoc();
$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name    = sanitize($stud['fname'].' '.$stud['lname']);
    $email   = sanitize($stud['email']??'');
    $comment = sanitize($_POST['comment']??'');
    if (!$comment) $errors[]='Comment cannot be empty.';
    if (empty($errors)) {
        $uid = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO comments(name,email,comment,user_id) VALUES(?,?,?,?)");
        $stmt->bind_param('sssi',$name,$email,$comment,$uid);
        if ($stmt->execute()) { setFlash('success','Your comment has been submitted successfully!'); redirect('student/submit-comment.php'); }
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-comment-alt mr-2 text-primary"></i>Submit Comment</h3></div></div>
  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><?php echo $errors[0]; ?></div><?php endif; ?>
  <div class="row justify-content-center"><div class="col-lg-7">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-comment-alt mr-2"></i>Your Feedback</div>
      <div class="form-card-body">
        <div class="d-flex align-items-center mb-4 p-3" style="background:#f8faff;border-radius:10px;gap:12px;">
          <span style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:800;flex-shrink:0;">
            <?php echo strtoupper(substr($stud['fname'],0,1)); ?>
          </span>
          <div>
            <strong><?php echo htmlspecialchars($stud['fname'].' '.$stud['lname']); ?></strong><br>
            <small style="color:var(--muted);"><?php echo htmlspecialchars($stud['user_id_no']??'Student'); ?></small>
          </div>
        </div>
        <form method="POST">
          <div class="form-group">
            <label class="required">Your Comment / Feedback</label>
            <textarea name="comment" class="form-control" rows="6" placeholder="Write your comment, suggestion or feedback here..." required><?php echo htmlspecialchars($_POST['comment']??''); ?></textarea>
          </div>
          <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Submit Comment</button>
          </div>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
