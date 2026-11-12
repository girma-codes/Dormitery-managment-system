<?php
$pageTitle = 'Student Dashboard';
require_once '../includes/config.php';
requireRole('student');
$sid = $_SESSION['user_id'];

$allocation = $conn->query("SELECT sa.*,b.block_number,b.block_name,r.room_number,r.floor_no,r.capacity FROM student_allocations sa JOIN blocks b ON sa.block_id=b.id JOIN rooms r ON sa.room_id=r.id WHERE sa.student_id=$sid AND sa.status='active' LIMIT 1")->fetch_assoc();
$news   = $conn->query("SELECT * FROM news_events WHERE status='published' ORDER BY created_at DESC LIMIT 4");
$stud   = $conn->query("SELECT * FROM users WHERE id=$sid")->fetch_assoc();
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div>
      <h3><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Student Dashboard</h3>
      <p style="color:var(--muted);margin:0;font-size:13px;">Welcome, <?php echo htmlspecialchars($_SESSION['fname'].' '.$_SESSION['lname']); ?>!</p>
    </div>
    <div class="text-muted" style="font-size:13px;"><i class="fas fa-calendar mr-1"></i><?php echo date('l, d F Y'); ?></div>
  </div>

  <?php echo getFlash(); ?>

  <!-- Student Info Card -->
  <div class="row mb-4">
    <div class="col-lg-5 mb-3">
      <div class="card">
        <div class="card-header bg-primary text-white"><i class="fas fa-user-graduate mr-2"></i>My Profile</div>
        <div class="card-body">
          <div class="text-center mb-3">
            <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;margin:0 auto 10px;">
              <?php echo strtoupper(substr($stud['fname'],0,1)); ?>
            </div>
            <h5 style="font-weight:700;"><?php echo htmlspecialchars($stud['fname'].' '.$stud['lname']); ?></h5>
            <span class="badge badge-primary"><?php echo htmlspecialchars($stud['user_id_no']??'N/A'); ?></span>
          </div>
          <table class="table table-sm mb-0" style="font-size:13px;">
            <tr><td style="color:var(--muted);">Username</td><td><strong><?php echo htmlspecialchars($stud['username']); ?></strong></td></tr>
            <tr><td style="color:var(--muted);">Email</td><td><?php echo htmlspecialchars($stud['email']??'N/A'); ?></td></tr>
            <tr><td style="color:var(--muted);">Gender</td><td><?php echo htmlspecialchars($stud['sex']??'N/A'); ?></td></tr>
            <tr><td style="color:var(--muted);">Status</td><td><span class="badge badge-success">Active</span></td></tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Dormitory Allocation -->
    <div class="col-lg-7 mb-3">
      <?php if ($allocation): ?>
      <div class="alloc-result">
        <div class="alloc-header">
          <div class="d-flex align-items-center" style="gap:14px;">
            <i class="fas fa-check-circle fa-3x" style="opacity:.8;"></i>
            <div>
              <h4 style="margin:0;font-weight:800;">Dormitory Assigned!</h4>
              <p style="margin:0;opacity:.85;font-size:13px;">Your dormitory allocation for <?php echo htmlspecialchars($allocation['academic_year']??'this year'); ?></p>
            </div>
          </div>
        </div>
        <div class="alloc-info-grid">
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-building mr-1"></i>Block</div>
            <div class="item-value"><?php echo htmlspecialchars($allocation['block_number']); ?></div>
          </div>
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-door-open mr-1"></i>Room No.</div>
            <div class="item-value"><?php echo htmlspecialchars($allocation['room_number']); ?></div>
          </div>
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-bed mr-1"></i>Bed No.</div>
            <div class="item-value"><?php echo htmlspecialchars($allocation['bed_number']); ?></div>
          </div>
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-layer-group mr-1"></i>Floor</div>
            <div class="item-value"><?php echo $allocation['floor_no']??'1'; ?></div>
          </div>
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-users mr-1"></i>Room Capacity</div>
            <div class="item-value"><?php echo $allocation['capacity']; ?></div>
          </div>
          <div class="alloc-info-item">
            <div class="item-label"><i class="fas fa-graduation-cap mr-1"></i>Faculty</div>
            <div class="item-value" style="font-size:1rem;"><?php echo htmlspecialchars($allocation['faculty']??'N/A'); ?></div>
          </div>
        </div>
        <div class="p-3 d-flex" style="gap:10px;">
          <a href="my-dorm.php" class="btn btn-success btn-sm"><i class="fas fa-eye mr-1"></i>View Details</a>
          <a href="submit-comment.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-comment mr-1"></i>Submit Comment</a>
        </div>
      </div>
      <?php else: ?>
      <div class="card h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
          <i class="fas fa-bed fa-4x text-muted mb-3"></i>
          <h5 style="color:var(--muted);">No Allocation Yet</h5>
          <p style="color:var(--muted);font-size:13.5px;">Your dormitory has not been assigned yet. Please contact your proctor.</p>
          <a href="submit-comment.php" class="btn btn-primary btn-sm"><i class="fas fa-comment mr-1"></i>Contact Proctor</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Latest News -->
  <div class="card">
    <div class="card-header bg-primary text-white"><i class="fas fa-newspaper mr-2"></i>Latest News &amp; Announcements</div>
    <div class="card-body p-0">
      <?php while($n=$news->fetch_assoc()): ?>
      <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:flex-start;gap:14px;">
        <span style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;flex-shrink:0;">
          <i class="fas fa-<?php echo $n['type']==='event'?'calendar':'newspaper'; ?>"></i>
        </span>
        <div>
          <h6 style="font-weight:700;margin:0 0 4px;"><?php echo htmlspecialchars($n['title']); ?></h6>
          <p style="color:var(--muted);font-size:12.5px;margin:0;"><?php echo substr(strip_tags($n['content']),0,100); ?>...</p>
          <small style="color:var(--muted);"><i class="fas fa-clock mr-1"></i><?php echo date('d M Y', strtotime($n['created_at'])); ?></small>
        </div>
        <span class="badge badge-<?php echo $n['type']==='event'?'success':'info'; ?> ml-auto"><?php echo ucfirst($n['type']); ?></span>
      </div>
      <?php endwhile; ?>
    </div>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
