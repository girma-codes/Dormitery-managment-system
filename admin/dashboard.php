<?php
$pageTitle = 'Admin Dashboard';
require_once '../includes/config.php';
requireRole('admin');

// Stats
$stats = [
  'students'  => $conn->query("SELECT COUNT(*) c FROM users WHERE user_type='student' AND status='active'")->fetch_assoc()['c'],
  'proctors'  => $conn->query("SELECT COUNT(*) c FROM users WHERE user_type='proctor' AND status='active'")->fetch_assoc()['c'],
  'blocks'    => $conn->query("SELECT COUNT(*) c FROM blocks WHERE status='active'")->fetch_assoc()['c'],
  'rooms'     => $conn->query("SELECT COUNT(*) c FROM rooms")->fetch_assoc()['c'],
  'allocated' => $conn->query("SELECT COUNT(*) c FROM student_allocations WHERE status='active'")->fetch_assoc()['c'],
  'comments'  => $conn->query("SELECT COUNT(*) c FROM comments WHERE status='pending'")->fetch_assoc()['c'],
];

// Recent allocations
$recentAlloc = $conn->query("
  SELECT sa.*, u.fname, u.lname, u.user_id_no, b.block_number, r.room_number, sa.bed_number
  FROM student_allocations sa
  JOIN users u ON sa.student_id=u.id
  JOIN blocks b ON sa.block_id=b.id
  JOIN rooms r ON sa.room_id=r.id
  WHERE sa.status='active'
  ORDER BY sa.allocated_at DESC LIMIT 8
");

// Recent activity
$recentActivity = $conn->query("
  SELECT al.*, u.fname, u.lname FROM activity_log al
  LEFT JOIN users u ON al.user_id=u.id
  ORDER BY al.created_at DESC LIMIT 6
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div>
      <h3><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Admin Dashboard</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item active">Dashboard</li></ol></nav>
    </div>
    <div class="text-muted" style="font-size:13px;"><i class="fas fa-calendar mr-1"></i><?php echo date('l, d F Y'); ?></div>
  </div>

  <?php echo getFlash(); ?>

  <!-- WIDGETS -->
  <div class="row mb-4">
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-blue">
        <div class="w-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="w-num"><?php echo $stats['students']; ?></div>
        <div class="w-label">Students</div>
      </div>
    </div>
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-green">
        <div class="w-icon"><i class="fas fa-hard-hat"></i></div>
        <div class="w-num"><?php echo $stats['proctors']; ?></div>
        <div class="w-label">Proctors</div>
      </div>
    </div>
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-purple">
        <div class="w-icon"><i class="fas fa-building"></i></div>
        <div class="w-num"><?php echo $stats['blocks']; ?></div>
        <div class="w-label">Blocks</div>
      </div>
    </div>
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-orange">
        <div class="w-icon"><i class="fas fa-door-open"></i></div>
        <div class="w-num"><?php echo $stats['rooms']; ?></div>
        <div class="w-label">Rooms</div>
      </div>
    </div>
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-blue">
        <div class="w-icon"><i class="fas fa-random"></i></div>
        <div class="w-num"><?php echo $stats['allocated']; ?></div>
        <div class="w-label">Allocated</div>
      </div>
    </div>
    <div class="col-6 col-lg-2 mb-3">
      <div class="widget-card wc-red">
        <div class="w-icon"><i class="fas fa-comments"></i></div>
        <div class="w-num"><?php echo $stats['comments']; ?></div>
        <div class="w-label">Pending Comments</div>
      </div>
    </div>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <i class="fas fa-bolt mr-2"></i>Quick Actions
        </div>
        <div class="card-body">
          <div class="row" style="gap:0;">
            <div class="col-auto mb-2"><a href="create-user.php" class="btn btn-primary"><i class="fas fa-user-plus mr-1"></i>Create User</a></div>
            <div class="col-auto mb-2"><a href="blocks.php" class="btn btn-success"><i class="fas fa-building mr-1"></i>Manage Blocks</a></div>
            <div class="col-auto mb-2"><a href="allocations.php" class="btn btn-warning"><i class="fas fa-random mr-1"></i>Allocations</a></div>
            <div class="col-auto mb-2"><a href="news.php" class="btn btn-info"><i class="fas fa-newspaper mr-1"></i>Post News</a></div>
            <div class="col-auto mb-2"><a href="reports.php" class="btn btn-secondary"><i class="fas fa-chart-bar mr-1"></i>Reports</a></div>
            <div class="col-auto mb-2"><a href="comments.php" class="btn btn-danger"><i class="fas fa-comments mr-1"></i>Comments <?php if($stats['comments']>0): ?><span class="badge badge-light"><?php echo $stats['comments']; ?></span><?php endif; ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Recent Allocations -->
    <div class="col-lg-8 mb-4">
      <div class="table-card">
        <div class="table-header">
          <h5><i class="fas fa-random mr-2"></i>Recent Student Allocations</h5>
          <a href="allocations.php" class="btn btn-sm btn-light" style="font-size:12px;">View All</a>
        </div>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>#</th><th>Student</th><th>ID No.</th><th>Block</th><th>Room</th><th>Bed</th><th>Date</th></tr></thead>
            <tbody>
            <?php $i=1; while($a = $recentAlloc->fetch_assoc()): ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><strong><?php echo htmlspecialchars($a['fname'].' '.$a['lname']); ?></strong></td>
              <td><small class="badge badge-secondary"><?php echo htmlspecialchars($a['user_id_no']??'-'); ?></small></td>
              <td><span class="badge badge-primary"><?php echo htmlspecialchars($a['block_number']); ?></span></td>
              <td><?php echo htmlspecialchars($a['room_number']); ?></td>
              <td><?php echo htmlspecialchars($a['bed_number']); ?></td>
              <td><small><?php echo date('d/m/Y', strtotime($a['allocated_at'])); ?></small></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Activity Log -->
    <div class="col-lg-4 mb-4">
      <div class="card h-100">
        <div class="card-header bg-dark text-white">
          <i class="fas fa-history mr-2"></i>Recent Activity
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
          <?php while($al = $recentActivity->fetch_assoc()): ?>
          <li class="list-group-item" style="font-size:12.5px;padding:10px 16px;">
            <div class="d-flex align-items-start gap-2" style="gap:8px;">
              <span style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;flex-shrink:0;">
                <i class="fas fa-user"></i>
              </span>
              <div>
                <strong><?php echo htmlspecialchars($al['fname'].' '.($al['lname']??'')); ?></strong><br>
                <span class="text-muted"><?php echo htmlspecialchars($al['action']); ?></span><br>
                <small class="text-muted"><i class="fas fa-clock mr-1"></i><?php echo date('d/m H:i', strtotime($al['created_at'])); ?></small>
              </div>
            </div>
          </li>
          <?php endwhile; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
