<?php
$pageTitle = 'Proctor Dashboard';
require_once '../includes/config.php';
requireRole('proctor');
$pid = $_SESSION['user_id'];

$myStudents = $conn->query("SELECT COUNT(*) c FROM student_allocations sa JOIN rooms r ON sa.room_id=r.id JOIN blocks b ON r.block_id=b.id WHERE b.proctor_id=$pid AND sa.status='active'")->fetch_assoc()['c'];
$myRooms    = $conn->query("SELECT COUNT(*) c FROM rooms r JOIN blocks b ON r.block_id=b.id WHERE b.proctor_id=$pid")->fetch_assoc()['c'];
$todayAtten = $conn->query("SELECT COUNT(*) c FROM attendance WHERE recorded_by=$pid AND date=CURDATE()")->fetch_assoc()['c'];

$recentStudents = $conn->query("SELECT sa.*,u.fname,u.lname,u.user_id_no,b.block_number,r.room_number FROM student_allocations sa JOIN users u ON sa.student_id=u.id JOIN blocks b ON sa.block_id=b.id JOIN rooms r ON sa.room_id=r.id WHERE sa.allocated_by=$pid AND sa.status='active' ORDER BY sa.allocated_at DESC LIMIT 6");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div>
      <h3><i class="fas fa-tachometer-alt mr-2 text-primary"></i>Proctor Dashboard</h3>
      <p style="color:var(--muted);margin:0;font-size:13px;">Welcome back, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</p>
    </div>
    <div class="text-muted" style="font-size:13px;"><i class="fas fa-calendar mr-1"></i><?php echo date('l, d F Y'); ?></div>
  </div>

  <div class="row mb-4">
    <div class="col-md-4 mb-3"><div class="widget-card wc-blue"><div class="w-icon"><i class="fas fa-users"></i></div><div class="w-num"><?php echo $myStudents; ?></div><div class="w-label">My Students</div></div></div>
    <div class="col-md-4 mb-3"><div class="widget-card wc-green"><div class="w-icon"><i class="fas fa-door-open"></i></div><div class="w-num"><?php echo $myRooms; ?></div><div class="w-label">My Rooms</div></div></div>
    <div class="col-md-4 mb-3"><div class="widget-card wc-orange"><div class="w-icon"><i class="fas fa-clipboard-check"></i></div><div class="w-num"><?php echo $todayAtten; ?></div><div class="w-label">Today's Attendance</div></div></div>
  </div>

  <!-- Quick Actions -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white"><i class="fas fa-bolt mr-2"></i>Quick Actions</div>
    <div class="card-body d-flex flex-wrap" style="gap:10px;">
      <a href="allocate-student.php" class="btn btn-primary"><i class="fas fa-user-plus mr-1"></i>Assign Student</a>
      <a href="register-room.php" class="btn btn-success"><i class="fas fa-door-open mr-1"></i>Register Room</a>
      <a href="attendance.php" class="btn btn-warning"><i class="fas fa-clipboard-check mr-1"></i>Take Attendance</a>
      <a href="report.php" class="btn btn-info"><i class="fas fa-chart-bar mr-1"></i>Generate Report</a>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-users mr-2"></i>Recently Assigned Students</h5>
      <a href="view-students.php" class="btn btn-sm btn-light" style="font-size:12px;">View All</a>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead><tr><th>#</th><th>Student</th><th>ID No.</th><th>Block</th><th>Room</th><th>Bed</th><th>Date</th></tr></thead>
        <tbody>
          <?php $i=1; while($s=$recentStudents->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></strong></td>
            <td><small class="badge badge-secondary"><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></small></td>
            <td><span class="badge badge-primary"><?php echo htmlspecialchars($s['block_number']); ?></span></td>
            <td><?php echo htmlspecialchars($s['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($s['bed_number']); ?></strong></td>
            <td><small><?php echo date('d/m/Y', strtotime($s['allocated_at'])); ?></small></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
