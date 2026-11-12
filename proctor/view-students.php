<?php
$pageTitle = 'View Students';
require_once '../includes/config.php';
requireRole('proctor');

$students = $conn->query("
    SELECT sa.*, u.fname, u.lname, u.user_id_no, u.sex,
           b.block_number, r.room_number
    FROM student_allocations sa
    JOIN users u ON sa.student_id=u.id
    JOIN blocks b ON sa.block_id=b.id
    JOIN rooms r ON sa.room_id=r.id
    WHERE sa.allocated_by={$_SESSION['user_id']} AND sa.status='active'
    ORDER BY sa.allocated_at DESC
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-users mr-2 text-primary"></i>My Assigned Students</h3></div>
    <a href="allocate-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>Assign New</a>
  </div>
  <div class="table-card">
    <div class="table-header">
      <h5>Student List</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search..." style="width:200px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead><tr><th>#</th><th>Name</th><th>ID No.</th><th>Gender</th><th>Block</th><th>Room</th><th>Bed</th><th>Faculty</th><th>Batch</th><th>Date</th></tr></thead>
        <tbody>
          <?php $i=1; while($s=$students->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></strong></td>
            <td><small class="badge badge-secondary"><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></small></td>
            <td><span class="badge badge-<?php echo $s['sex']==='Male'?'info':'danger'; ?>"><?php echo $s['sex']??'-'; ?></span></td>
            <td><strong><?php echo htmlspecialchars($s['block_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($s['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($s['bed_number']); ?></strong></td>
            <td><?php echo htmlspecialchars($s['faculty']??'-'); ?></td>
            <td><?php echo htmlspecialchars($s['batch_year']??'-'); ?></td>
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
