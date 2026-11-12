<?php
$pageTitle = 'Generate Report';
require_once '../includes/config.php';
requireRole('proctor');
$pid = $_SESSION['user_id'];
$data = null; $type = sanitize($_GET['type']??'students');

if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['generate'])) {
    if ($type === 'students') {
        $data = $conn->query("SELECT sa.*,u.fname,u.lname,u.user_id_no,u.sex,b.block_number,r.room_number FROM student_allocations sa JOIN users u ON sa.student_id=u.id JOIN blocks b ON sa.block_id=b.id JOIN rooms r ON sa.room_id=r.id WHERE sa.allocated_by=$pid AND sa.status='active' ORDER BY b.block_number,r.room_number");
    } elseif ($type === 'attendance') {
        $date = sanitize($_GET['date']??date('Y-m-d'));
        $data = $conn->query("SELECT a.*,u.fname,u.lname,u.user_id_no FROM attendance a JOIN users u ON a.student_id=u.id WHERE a.recorded_by=$pid AND a.date='$date' ORDER BY a.status");
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-chart-bar mr-2 text-primary"></i>Generate Report</h3></div>
    <?php if ($data): ?><button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print"><i class="fas fa-print mr-1"></i>Print</button><?php endif; ?>
  </div>

  <div class="card mb-4 no-print">
    <div class="card-body">
      <form method="GET" class="row align-items-end" style="gap:0;">
        <div class="col-md-3 mb-2"><label style="font-size:12px;font-weight:600;">Report Type</label>
          <select name="type" class="form-control custom-select">
            <option value="students" <?php echo $type==='students'?'selected':''; ?>>Student Allocation</option>
            <option value="attendance" <?php echo $type==='attendance'?'selected':''; ?>>Attendance Report</option>
          </select>
        </div>
        <?php if ($type==='attendance'): ?>
        <div class="col-md-3 mb-2"><label style="font-size:12px;font-weight:600;">Date</label><input type="date" name="date" class="form-control" value="<?php echo sanitize($_GET['date']??date('Y-m-d')); ?>"></div>
        <?php endif; ?>
        <div class="col-auto mb-2"><button type="submit" name="generate" value="1" class="btn btn-primary"><i class="fas fa-cog mr-1"></i>Generate</button></div>
      </form>
    </div>
  </div>

  <?php if ($data): ?>
  <div class="table-card">
    <div class="table-header">
      <h5>Report: <?php echo ucfirst($type); ?></h5>
      <span style="color:rgba(255,255,255,.7);font-size:12px;"><?php echo date('d F Y, H:i'); ?></span>
    </div>
    <div class="table-responsive">
      <?php if ($type==='students'): ?>
      <table class="table"><thead><tr><th>#</th><th>Name</th><th>ID No.</th><th>Gender</th><th>Block</th><th>Room</th><th>Bed</th><th>Faculty</th></tr></thead>
      <tbody><?php $i=1; while($s=$data->fetch_assoc()): ?><tr><td><?php echo $i++; ?></td><td><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></td><td><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></td><td><?php echo htmlspecialchars($s['sex']??'-'); ?></td><td><?php echo htmlspecialchars($s['block_number']); ?></td><td><?php echo htmlspecialchars($s['room_number']); ?></td><td><?php echo htmlspecialchars($s['bed_number']); ?></td><td><?php echo htmlspecialchars($s['faculty']??'-'); ?></td></tr><?php endwhile; ?></tbody></table>
      <?php elseif ($type==='attendance'): ?>
      <table class="table"><thead><tr><th>#</th><th>Name</th><th>ID No.</th><th>Date</th><th>Status</th></tr></thead>
      <tbody><?php $i=1; while($a=$data->fetch_assoc()): ?><tr><td><?php echo $i++; ?></td><td><?php echo htmlspecialchars($a['fname'].' '.$a['lname']); ?></td><td><?php echo htmlspecialchars($a['user_id_no']??'-'); ?></td><td><?php echo $a['date']; ?></td><td><span class="badge badge-<?php echo $a['status']==='present'?'success':'danger'; ?>"><?php echo ucfirst($a['status']); ?></span></td></tr><?php endwhile; ?></tbody></table>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
