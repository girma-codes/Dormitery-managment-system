<?php
$pageTitle = 'Proctor Manager Dashboard';
require_once '../includes/config.php';
requireRole('proctor_manager');
$blocks   = $conn->query("SELECT COUNT(*) c FROM blocks WHERE status='active'")->fetch_assoc()['c'];
$proctors = $conn->query("SELECT COUNT(*) c FROM users WHERE user_type='proctor' AND status='active'")->fetch_assoc()['c'];
$students = $conn->query("SELECT COUNT(*) c FROM student_allocations WHERE status='active'")->fetch_assoc()['c'];
$comments = $conn->query("SELECT COUNT(*) c FROM comments WHERE status='pending'")->fetch_assoc()['c'];
$blkData  = $conn->query("SELECT b.*,(SELECT COUNT(*) FROM rooms r WHERE r.block_id=b.id) rc,(SELECT COUNT(*) FROM student_allocations sa JOIN rooms r ON sa.room_id=r.id WHERE r.block_id=b.id AND sa.status='active') alloc FROM blocks b WHERE b.status='active' ORDER BY b.block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Proctor Manager Dashboard</h3><p style="color:var(--muted);margin:0;font-size:13px;">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</p></div></div>
  <?php echo getFlash(); ?>
  <div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="widget-card wc-purple"><div class="w-icon"><i class="fas fa-building"></i></div><div class="w-num"><?php echo $blocks; ?></div><div class="w-label">Blocks</div></div></div>
    <div class="col-md-3 mb-3"><div class="widget-card wc-green"><div class="w-icon"><i class="fas fa-hard-hat"></i></div><div class="w-num"><?php echo $proctors; ?></div><div class="w-label">Proctors</div></div></div>
    <div class="col-md-3 mb-3"><div class="widget-card wc-blue"><div class="w-icon"><i class="fas fa-users"></i></div><div class="w-num"><?php echo $students; ?></div><div class="w-label">Students Allocated</div></div></div>
    <div class="col-md-3 mb-3"><div class="widget-card wc-orange"><div class="w-icon"><i class="fas fa-comments"></i></div><div class="w-num"><?php echo $comments; ?></div><div class="w-label">Pending Comments</div></div></div>
  </div>
  <div class="d-flex mb-4" style="gap:10px;">
    <a href="register-block.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Register Block</a>
    <a href="allocate-proctor.php" class="btn btn-success"><i class="fas fa-user-plus mr-1"></i>Assign Proctor</a>
    <a href="report.php" class="btn btn-info"><i class="fas fa-chart-bar mr-1"></i>Report</a>
    <a href="comments.php" class="btn btn-warning"><i class="fas fa-comments mr-1"></i>Comments</a>
  </div>

  <!-- Block Overview -->
  <div class="row">
    <?php while($b=$blkData->fetch_assoc()): ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card">
        <div class="card-header" style="background:linear-gradient(135deg,var(--primary),#2a5ba8);color:#fff;">
          <i class="fas fa-building mr-2"></i><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
          <span class="badge badge-light float-right"><?php echo $b['gender']; ?></span>
        </div>
        <div class="card-body text-center">
          <div class="row">
            <div class="col-4"><div style="font-size:1.6rem;font-weight:800;color:var(--primary);"><?php echo $b['capacity']; ?></div><div style="font-size:11px;color:var(--muted);">Capacity</div></div>
            <div class="col-4"><div style="font-size:1.6rem;font-weight:800;color:var(--success);"><?php echo $b['rc']; ?></div><div style="font-size:11px;color:var(--muted);">Rooms</div></div>
            <div class="col-4"><div style="font-size:1.6rem;font-weight:800;color:var(--info);"><?php echo $b['alloc']; ?></div><div style="font-size:11px;color:var(--muted);">Allocated</div></div>
          </div>
          <div class="mt-3">
            <?php $pct = $b['capacity']>0 ? round($b['alloc']/$b['capacity']*100) : 0; ?>
            <div class="progress" style="height:8px;border-radius:4px;">
              <div class="progress-bar bg-<?php echo $pct>80?'danger':($pct>50?'warning':'success'); ?>" style="width:<?php echo $pct; ?>%"></div>
            </div>
            <small style="color:var(--muted);"><?php echo $pct; ?>% occupied</small>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div></div>
<?php require_once '../includes/footer.php'; ?>
