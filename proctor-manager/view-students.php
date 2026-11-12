<?php
$pageTitle = 'View Students';
require_once '../includes/config.php';
requireRole('proctor_manager');

$block_filter = (int)($_GET['block'] ?? 0);
$sql = "
    SELECT sa.*, u.fname, u.lname, u.user_id_no, u.sex,
           b.block_number, b.block_name, r.room_number
    FROM student_allocations sa
    JOIN users u ON sa.student_id=u.id
    JOIN blocks b ON sa.block_id=b.id
    JOIN rooms r ON sa.room_id=r.id
    WHERE sa.status='active'
";
if ($block_filter) $sql .= " AND sa.block_id=$block_filter";
$sql .= " ORDER BY b.block_number, r.room_number, sa.bed_number";
$students = $conn->query($sql);
$blocks   = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-users mr-2 text-primary"></i>View Students</h3></div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
      <i class="fas fa-print mr-1"></i>Print
    </button>
  </div>

  <!-- Filter -->
  <div class="card mb-4 no-print">
    <div class="card-body">
      <form method="GET" class="d-flex align-items-end" style="gap:12px;">
        <div>
          <label style="font-size:12px;font-weight:600;display:block;">Filter by Block</label>
          <select name="block" class="form-control custom-select" style="width:220px;">
            <option value="">All Blocks</option>
            <?php while($b=$blocks->fetch_assoc()): ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $block_filter==$b['id']?'selected':''; ?>>
              <?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i>Filter</button>
        <a href="view-students.php" class="btn btn-outline-secondary"><i class="fas fa-sync"></i></a>
      </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-list mr-2"></i>Student Allocation List</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm"
             placeholder="Search..." style="width:200px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead>
          <tr><th>#</th><th>Name</th><th>ID No.</th><th>Gender</th>
              <th>Block</th><th>Room</th><th>Bed</th><th>Faculty</th><th>Batch</th></tr>
        </thead>
        <tbody>
          <?php $i=1; while($s=$students->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></strong></td>
            <td><small class="badge badge-secondary"><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></small></td>
            <td><span class="badge badge-<?php echo $s['sex']==='Male'?'info':'danger'; ?>"><?php echo $s['sex']??'-'; ?></span></td>
            <td><span class="badge badge-primary"><?php echo htmlspecialchars($s['block_number']); ?></span></td>
            <td><?php echo htmlspecialchars($s['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($s['bed_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($s['faculty']??'-'); ?></td>
            <td><?php echo htmlspecialchars($s['batch_year']??'-'); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
