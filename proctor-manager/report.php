<?php
$pageTitle = 'Generate Report';
require_once '../includes/config.php';
requireRole('proctor_manager');

$type   = sanitize($_GET['type']   ?? 'allocation');
$block  = (int)($_GET['block']     ?? 0);
$gender = sanitize($_GET['gender'] ?? '');
$data   = null;

$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");

if (isset($_GET['generate'])) {
    if ($type === 'allocation') {
        $sql = "SELECT sa.*,u.fname,u.lname,u.user_id_no,u.sex,b.block_number,r.room_number
                FROM student_allocations sa
                JOIN users u ON sa.student_id=u.id
                JOIN blocks b ON sa.block_id=b.id
                JOIN rooms r ON sa.room_id=r.id
                WHERE sa.status='active'";
        if ($block)  $sql .= " AND sa.block_id=$block";
        if ($gender) $sql .= " AND u.sex='$gender'";
        $data = $conn->query($sql);
    } elseif ($type === 'room_summary') {
        $sql = "SELECT b.block_number, b.block_name, b.gender, b.capacity,
                       COUNT(r.id) AS total_rooms,
                       SUM(r.occupied) AS total_occupied,
                       (b.capacity - SUM(r.occupied)) AS available
                FROM blocks b
                LEFT JOIN rooms r ON r.block_id=b.id
                WHERE b.status='active'";
        if ($block) $sql .= " AND b.id=$block";
        $sql .= " GROUP BY b.id ORDER BY b.block_number";
        $data = $conn->query($sql);
    }
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-chart-bar mr-2 text-primary"></i>Generate Reports</h3></div>
    <?php if ($data): ?>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
      <i class="fas fa-print mr-1"></i>Print Report
    </button>
    <?php endif; ?>
  </div>

  <!-- Filter Form -->
  <div class="card mb-4 no-print">
    <div class="card-body">
      <form method="GET" class="row align-items-end" style="gap:0;">
        <div class="col-md-3 mb-2">
          <label style="font-size:12px;font-weight:600;">Report Type</label>
          <select name="type" class="form-control custom-select">
            <option value="allocation"   <?php echo $type==='allocation'  ?'selected':''; ?>>Student Allocation</option>
            <option value="room_summary" <?php echo $type==='room_summary'?'selected':''; ?>>Block/Room Summary</option>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:12px;font-weight:600;">Block</label>
          <select name="block" class="form-control custom-select">
            <option value="">All Blocks</option>
            <?php $blocks->data_seek(0); while($b=$blocks->fetch_assoc()): ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $block==$b['id']?'selected':''; ?>>
              <?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:12px;font-weight:600;">Gender</label>
          <select name="gender" class="form-control custom-select">
            <option value="">All</option>
            <option value="Male"   <?php echo $gender==='Male'  ?'selected':''; ?>>Male</option>
            <option value="Female" <?php echo $gender==='Female'?'selected':''; ?>>Female</option>
          </select>
        </div>
        <div class="col-auto mb-2">
          <button type="submit" name="generate" value="1" class="btn btn-primary">
            <i class="fas fa-cog mr-1"></i>Generate
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php if ($data): ?>
  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-file-alt mr-2"></i><?php echo ucfirst(str_replace('_',' ',$type)); ?> Report</h5>
      <span style="color:rgba(255,255,255,.7);font-size:12px;">
        Generated: <?php echo date('d F Y, H:i'); ?>
      </span>
    </div>
    <div class="table-responsive">
      <?php if ($type === 'allocation'): ?>
      <table class="table">
        <thead><tr><th>#</th><th>Name</th><th>ID No.</th><th>Gender</th><th>Block</th><th>Room</th><th>Bed</th><th>Faculty</th><th>Batch</th></tr></thead>
        <tbody>
          <?php $i=1; $total=0; while($s=$data->fetch_assoc()): $total++; ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></td>
            <td><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></td>
            <td><?php echo htmlspecialchars($s['sex']??'-'); ?></td>
            <td><strong><?php echo htmlspecialchars($s['block_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($s['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($s['bed_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($s['faculty']??'-'); ?></td>
            <td><?php echo htmlspecialchars($s['batch_year']??'-'); ?></td>
          </tr>
          <?php endwhile; ?>
          <tr class="table-active"><td colspan="9"><strong>Total: <?php echo $total; ?> students</strong></td></tr>
        </tbody>
      </table>

      <?php elseif ($type === 'room_summary'): ?>
      <table class="table">
        <thead><tr><th>#</th><th>Block</th><th>Name</th><th>Gender</th><th>Capacity</th><th>Rooms</th><th>Occupied</th><th>Available</th><th>Occupancy</th></tr></thead>
        <tbody>
          <?php $i=1; while($b=$data->fetch_assoc()):
            $avail = $b['available'] ?? ($b['capacity'] - ($b['total_occupied']??0));
            $pct = $b['capacity'] > 0 ? round(($b['total_occupied']??0)/$b['capacity']*100) : 0;
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($b['block_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($b['block_name']??'-'); ?></td>
            <td><?php echo htmlspecialchars($b['gender']); ?></td>
            <td><?php echo $b['capacity']; ?></td>
            <td><?php echo $b['total_rooms']??0; ?></td>
            <td><span style="color:var(--danger);font-weight:700;"><?php echo $b['total_occupied']??0; ?></span></td>
            <td><span style="color:var(--success);font-weight:700;"><?php echo max(0,$avail); ?></span></td>
            <td>
              <div class="progress" style="height:10px;border-radius:5px;width:80px;">
                <div class="progress-bar bg-<?php echo $pct>80?'danger':($pct>50?'warning':'success'); ?>"
                     style="width:<?php echo $pct; ?>%;"></div>
              </div>
              <small><?php echo $pct; ?>%</small>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
