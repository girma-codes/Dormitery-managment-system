<?php
$pageTitle = 'Contact Us';
require_once '../includes/config.php';
$success = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name    = sanitize($_POST['name']??'');
    $email   = sanitize($_POST['email']??'');
    $comment = sanitize($_POST['message']??'');
    if ($name && $comment) {
        $stmt = $conn->prepare("INSERT INTO comments(name,email,comment) VALUES(?,?,?)");
        $stmt->bind_param('sss',$name,$email,$comment);
        if ($stmt->execute()) $success = 'Your message has been sent. We will get back to you soon!';
        $stmt->close();
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div style="background:linear-gradient(135deg,var(--primary-dk),var(--primary));padding:50px 0;text-align:center;color:#fff;">
  <h2 style="font-weight:800;"><i class="fas fa-envelope mr-3"></i>Contact Us</h2>
</div>
<main class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 mb-4">
        <h4 style="font-weight:700;color:var(--primary);">Get In Touch</h4>
        <p style="color:var(--muted);">Have questions about dormitory allocation or the system? Send us a message.</p>
        <div style="display:flex;flex-direction:column;gap:16px;margin-top:24px;">
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;"><i class="fas fa-map-marker-alt"></i></div><div><strong style="color:var(--primary);">Address</strong><br><span style="color:var(--muted);font-size:13.5px;">Gondar, Ethiopia</span></div></div>
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;"><i class="fas fa-phone"></i></div><div><strong style="color:var(--primary);">Phone</strong><br><span style="color:var(--muted);font-size:13.5px;">+251 58 111 1234</span></div></div>
          <div style="display:flex;align-items:center;gap:14px;"><div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;"><i class="fas fa-envelope"></i></div><div><strong style="color:var(--primary);">Email</strong><br><span style="color:var(--muted);font-size:13.5px;">dorm@uog.edu.et</span></div></div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="form-card">
          <div class="form-card-header"><i class="fas fa-paper-plane mr-2"></i>Send a Message</div>
          <div class="form-card-body">
            <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i><?php echo $success; ?></div><?php endif; ?>
            <form method="POST">
              <div class="row">
                <div class="col-md-6"><div class="form-group"><label class="required">Full Name</label><input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name']??''); ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email']??''); ?>"></div></div>
              </div>
              <div class="form-group"><label class="required">Message</label><textarea name="message" class="form-control" rows="5" required><?php echo htmlspecialchars($_POST['message']??''); ?></textarea></div>
              <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require_once '../includes/footer.php'; ?>
