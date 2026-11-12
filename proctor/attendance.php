<?php
$pageTitle = 'Attendance';
require_once '../includes/config.php';
requireRole('proctor');
$pid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $date = sanitize($_POST['date']??date('Y-m-d'));
    $block_id = (int)($_POST['block_id']??0);
    foreach ($_POST['attendance'] as $stud_id => $status) {
        $stud_id = (int)$stud_id;
        $status = in_array($status,['present','absent']) ? $status : 'present';
        // Insert or update
        $stmt = $conn->prepare("INSERT INTO attendance(student_id,block_id,date,status,recorded_by) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE status=VALUES(status)");
        $stmt->bind_param('iissi',$stud_id,$block_id,$date,$status,$pid);
        $stmt->execute(); $stmt->close();
    }
    setFlash('success','Attendance saved for '.$date.'!');
    redirect('proctor/attendance.php');
}

$date     = sanitize($_GET['date'] ?? date('Y-m-d'));
$block_id = (int)($_GET['block_id'] ?? 0);
$blocks   = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
$students = null;
if ($block_id) {
    $students = $conn->query("
        SELECT sa.student_id, u.fname, u.lname, u.user_id_no, sa.room_id, r.room_number, sa.bed_number,
               (SELECT status FROM attendance WHERE student_id=sa.student_id AND date='$date') AS att_status
        FROM student_allocations sa
        JOIN users u ON sa.student_id=u.id
        JOIN rooms r ON sa.room_id=r.id
        WHERE sa.block_id=$block_id AND sa.status='active'
        ORDER BY r.room_number, sa.bed_number
    ");
}
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-clipboard-check mr-2 text-primary"></i>Student Attendance</h3></div></div>
  <?php echo getFlash(); ?>

  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row align-items-end" style="gap:0;">
        <div class="col-md-3 mb-2"><label style="font-size:12px;font-weight:600;">Date</label><input type="date" name="date" class="form-control" value="<?php echo $date; ?>"></div>
        <div class="col-md-4 mb-2"><label style="font-size:12px;font-weight:600;">Block</label><select name="block_id" class="form-control custom-select"><option value="">Select Block</option><?php while($b=$blocks->fetch_assoc()): ?><option value="<?php echo $b['id']; ?>" <?php echo $block_id==$b['id']?'selected':''; ?>><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?></option><?php endwhile; ?></select></div>
        <div class="col-auto mb-2"><button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>Load Students</button></div>
      </form>
    </div>
  </div>

  <?php if ($students && $block_id): ?>
  <form method="POST">
    <input type="hidden" name="date" value="<?php echo $date; ?>">
    <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
    <div class="table-card">
      <div class="table-header">
        <h5><i class="fas fa-list mr-2"></i>Attendance for <?php echo date('d F Y', strtotime($date)); ?></h5>
        <div style="display:flex;gap:8px;">
          <button type="button" class="btn btn-sm btn-success" onclick="$('.att-status').val('present')"><i class="fas fa-check mr-1"></i>All Present</button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead><tr><th>#</th><th>Student</th><th>ID No.</th><th>Room</th><th>Bed</th><th>Attendance</th></tr></thead>
          <tbody>
            <?php $i=1; while($s=$students->fetch_assoc()): $curr = $s['att_status']??'present'; ?>
            <tr>
              <td><?php echo $i++; ?></td>
              <td><strong><?php echo htmlspecialchars($s['fname'].' '.$s['lname']); ?></strong></td>
              <td><small><?php echo htmlspecialchars($s['user_id_no']??'-'); ?></small></td>
              <td><?php echo htmlspecialchars($s['room_number']); ?></td>
              <td><?php echo htmlspecialchars($s['bed_number']); ?></td>
              <td>
                <select name="attendance[<?php echo $s['student_id']; ?>]" class="form-control form-control-sm att-status" style="width:120px;border-radius:6px;">
                  <option value="present" <?php echo $curr==='present'?'selected':''; ?>>✅ Present</option>
                  <option value="absent"  <?php echo $curr==='absent'?'selected':''; ?>>❌ Absent</option>
                </select>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="p-3 text-right"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-2"></i>Save Attendance</button></div>
    </div>
  </form>
  <?php endif; ?>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
