<?php
$pageTitle = 'Reports';
require_once '../includes/config.php';
requireRole('admin');

$type   = sanitize($_GET['type'] ?? 'allocation');
$block  = (int)($_GET['block'] ?? 0);
$gender = sanitize($_GET['gender'] ?? '');

$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
$data   = null;

if ($type === 'allocation') {
    $sql = "SELECT sa.*, u.fname,u.lname,u.user_id_no,u.sex,b.block_number,r.room_number,sa.bed_number FROM student_allocations sa JOIN users u ON sa.student_id=u.id JOIN blocks b ON sa.block_id=b.id JOIN rooms r ON sa.room_id=r.id WHERE sa.status='active'";
    if ($block)  $sql .= " AND sa.block_id=$block";
    if ($gender) $sql .= " AND u.sex='$gender'";
    $data = $conn->query($sql);
} elseif ($type === 'room_occupancy') {
    $sql = "SELECT r.*,b.block_number,b.gender,(SELECT COUNT(*) FROM student_allocations sa WHERE sa.room_id=r.id AND sa.status='active') AS occupied_count FROM rooms r JOIN blocks b ON r.block_id=b.id";
    if ($block) $sql .= " WHERE r.block_id=$block";
    $data = $conn->query($sql);
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-chart-bar mr-2 text-primary"></i>Generate Reports</h3></div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print"><i class="fas fa-print mr-1"></i>Print Report</button>
  </div>

  <!-- Filter Form -->
  <div class="card mb-4 no-print">
    <div class="card-body">
      <form method="GET" class="row align-items-end" style="gap:0;">
        <div class="col-md-3 mb-2">
          <label style="font-size:12px;font-weight:600;">Report Type</label>
          <select name="type" class="form-control custom-select">
            <option value="allocation" <?php echo $type==='allocation'?'selected':''; ?>>Student Allocation</option>
            <option value="room_occupancy" <?php echo $type==='room_occupancy'?'selected':''; ?>>Room Occupancy</option>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:12px;font-weight:600;">Block</label>
          <select name="block" class="form-control custom-select">
            <option value="">All Blocks</option>
            <?php $blocks->data_seek(0); while($b=$blocks->fetch_assoc()): ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $block==$b['id']?'selected':''; ?>><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:12px;font-weight:600;">Gender</label>
          <select name="gender" class="form-control custom-select">
            <option value="">All</option>
            <option value="Male" <?php echo $gender==='Male'?'selected':''; ?>>Male</option>
            <option value="Female" <?php echo $gender==='Female'?'selected':''; ?>>Female</option>
          </select>
        </div>
        <div class="col-auto mb-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i>Generate</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Report Output -->
  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-file-alt mr-2"></i><?php echo ucfirst(str_replace('_',' ',$type)); ?> Report</h5>
      <span style="color:rgba(255,255,255,.7);font-size:12px;"><?php echo date('d F Y, H:i'); ?></span>
    </div>
    <div class="table-responsive">
      <?php if ($type === 'allocation' && $data): ?>
      <table class="table">
        <thead><tr><th>#</th><th>Student Name</th><th>ID No.</th><th>Gender</th><th>Block</th><th>Room</th><th>Bed No.</th><th>Faculty</th><th>Batch</th></tr></thead>
        <tbody>
          <?php $i=1; $total=0; while($a=$data->fetch_assoc()): $total++; ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($a['fname'].' '.$a['lname']); ?></td>
            <td><?php echo htmlspecialchars($a['user_id_no']??'-'); ?></td>
            <td><?php echo htmlspecialchars($a['sex']??'-'); ?></td>
            <td><strong><?php echo htmlspecialchars($a['block_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($a['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($a['bed_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($a['faculty']??'-'); ?></td>
            <td><?php echo htmlspecialchars($a['batch_year']??'-'); ?></td>
          </tr>
          <?php endwhile; ?>
          <tr class="table-active"><td colspan="9"><strong>Total Records: <?php echo $total; ?></strong></td></tr>
        </tbody>
      </table>
      <?php elseif ($type === 'room_occupancy' && $data): ?>
      <table class="table">
        <thead><tr><th>#</th><th>Block</th><th>Room No.</th><th>Gender</th><th>Capacity</th><th>Occupied</th><th>Available</th><th>Status</th></tr></thead>
        <tbody>
          <?php $i=1; while($r=$data->fetch_assoc()): $avail=$r['capacity']-$r['occupied_count']; ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($r['block_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($r['room_number']); ?></td>
            <td><?php echo htmlspecialchars($r['gender']); ?></td>
            <td><?php echo $r['capacity']; ?></td>
            <td><strong style="color:var(--danger);"><?php echo $r['occupied_count']; ?></strong></td>
            <td><strong style="color:var(--success);"><?php echo $avail; ?></strong></td>
            <td>
              <?php if($r['occupied_count']>=$r['capacity']): ?>
              <span class="badge badge-danger">Full</span>
              <?php elseif($r['occupied_count']===0): ?>
              <span class="badge badge-success">Empty</span>
              <?php else: ?>
              <span class="badge badge-warning">Partial</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
