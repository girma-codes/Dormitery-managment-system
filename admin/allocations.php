<?php
$pageTitle = 'Student Allocations';
require_once '../includes/config.php';
requireRole('admin');

$allocations = $conn->query("
    SELECT sa.*, u.fname, u.lname, u.user_id_no, u.sex,
           b.block_number, r.room_number, sa.bed_number,
           p.fname AS p_fname, p.lname AS p_lname
    FROM student_allocations sa
    JOIN users u ON sa.student_id=u.id
    JOIN blocks b ON sa.block_id=b.id
    JOIN rooms r ON sa.room_id=r.id
    LEFT JOIN users p ON sa.allocated_by=p.id
    WHERE sa.status='active'
    ORDER BY sa.allocated_at DESC
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-random mr-2 text-primary"></i>Student Allocations</h3></div>
    <div>
      <button onclick="window.print()" class="btn btn-outline-secondary btn-sm mr-2"><i class="fas fa-print mr-1"></i>Print</button>
      <a href="<?php echo BASE_URL; ?>proctor/allocate-student.php" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>New Allocation</a>
    </div>
  </div>
  <?php echo getFlash(); ?>
  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-list mr-2"></i>All Allocations</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search..." style="width:220px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead><tr><th>#</th><th>Student Name</th><th>ID No.</th><th>Gender</th><th>Block</th><th>Room</th><th>Bed</th><th>Faculty</th><th>Allocated By</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
          <?php $i=1; while($a=$allocations->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><strong><?php echo htmlspecialchars($a['fname'].' '.$a['lname']); ?></strong></td>
            <td><small class="badge badge-secondary"><?php echo htmlspecialchars($a['user_id_no']??'-'); ?></small></td>
            <td><span class="badge badge-<?php echo $a['sex']==='Male'?'info':'danger'; ?>"><?php echo htmlspecialchars($a['sex']??'-'); ?></span></td>
            <td><span class="badge badge-primary"><?php echo htmlspecialchars($a['block_number']); ?></span></td>
            <td><?php echo htmlspecialchars($a['room_number']); ?></td>
            <td><strong><?php echo htmlspecialchars($a['bed_number']); ?></strong></td>
            <td><small><?php echo htmlspecialchars($a['faculty']??'-'); ?></small></td>
            <td><small><?php echo htmlspecialchars($a['p_fname'].' '.$a['p_lname']); ?></small></td>
            <td><small><?php echo date('d/m/Y', strtotime($a['allocated_at'])); ?></small></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
