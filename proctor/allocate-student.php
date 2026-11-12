<?php
$pageTitle = 'Allocate Student';
require_once '../includes/config.php';
requireRole('proctor');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname    = sanitize($_POST['fname']??'');
    $lname    = sanitize($_POST['lname']??'');
    $uid_no   = sanitize($_POST['user_id_no']??'');
    $faculty  = sanitize($_POST['faculty']??'');
    $batch    = sanitize($_POST['batch_year']??'');
    $acad     = sanitize($_POST['academic_year']??'');
    $block_id = (int)($_POST['block_id']??0);
    $room_id  = (int)($_POST['room_id']??0);
    $bed_no   = sanitize($_POST['bed_number']??'');
    $allocated_by = $_SESSION['user_id'];

    if (!$fname || !$lname) $errors[] = 'Student name required.';
    if (!$uid_no) $errors[] = 'Student ID required.';
    if (!$block_id || !$room_id || !$bed_no) $errors[] = 'Block, room and bed number required.';

    if (empty($errors)) {
        // Find or create student user
        $stmt = $conn->prepare("SELECT id FROM users WHERE user_id_no=? AND user_type='student'");
        $stmt->bind_param('s', $uid_no); $stmt->execute();
        $r = $stmt->get_result(); $stmt->close();
        
        if ($r->num_rows > 0) {
            $student_id = $r->fetch_assoc()['id'];
        } else {
            // Create student account
            $uname = strtolower(str_replace(['/', ' '], '', $uid_no));
            $hpass = password_hash('password', PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users(fname,lname,user_id_no,username,password,user_type,status) VALUES(?,?,?,?,?,'student','active')");
            $ins->bind_param('sssss', $fname, $lname, $uid_no, $uname, $hpass);
            $ins->execute(); $student_id = $conn->insert_id; $ins->close();
        }

        // Check bed not taken
        $chk = $conn->prepare("SELECT id FROM student_allocations WHERE room_id=? AND bed_number=? AND status='active'");
        $chk->bind_param('is', $room_id, $bed_no); $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $errors[] = "Bed $bed_no in this room is already occupied.";
        } else {
            $ins2 = $conn->prepare("INSERT INTO student_allocations(student_id,block_id,room_id,bed_number,faculty,batch_year,academic_year,allocated_by) VALUES(?,?,?,?,?,?,?,?)");
            $ins2->bind_param('iiississ', $student_id, $block_id, $room_id, $bed_no, $faculty, $batch, $acad, $allocated_by);
            if ($ins2->execute()) {
                // Update room occupancy
                $conn->query("UPDATE rooms SET occupied=occupied+1 WHERE id=$room_id");
                setFlash('success', "Student $fname $lname successfully allocated to Room $room_id, Bed $bed_no!");
                redirect('proctor/allocate-student.php');
            } else { $errors[] = $conn->error; }
            $ins2->close();
        }
        $chk->close();
    }
}
$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-user-plus mr-2 text-primary"></i>Allocate Student to Dormitory</h3></div>
    <a href="view-students.php" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
  </div>
  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>

  <div class="row justify-content-center"><div class="col-lg-9">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-random mr-2"></i>Student Assignation Form</div>
      <div class="form-card-body">
        <form method="POST">
          <h6 style="font-weight:700;color:var(--primary);border-bottom:2px solid var(--border);padding-bottom:8px;margin-bottom:16px;">Student Information</h6>
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label class="required">First Name</label><input type="text" name="fname" class="form-control" required value="<?php echo htmlspecialchars($_POST['fname']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label class="required">Last Name</label><input type="text" name="lname" class="form-control" required value="<?php echo htmlspecialchars($_POST['lname']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label class="required">Student ID No.</label><input type="text" name="user_id_no" class="form-control" placeholder="GUR/01867/09" required value="<?php echo htmlspecialchars($_POST['user_id_no']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Faculty</label><input type="text" name="faculty" class="form-control" value="<?php echo htmlspecialchars($_POST['faculty']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Batch Year</label><input type="text" name="batch_year" class="form-control" placeholder="e.g. 2019" value="<?php echo htmlspecialchars($_POST['batch_year']??''); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Academic Year</label><input type="text" name="academic_year" class="form-control" placeholder="e.g. 2019/2020" value="<?php echo htmlspecialchars($_POST['academic_year']??''); ?>"></div></div>
          </div>

          <h6 style="font-weight:700;color:var(--primary);border-bottom:2px solid var(--border);padding-bottom:8px;margin:16px 0;">Dormitory Assignment</h6>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Block</label>
                <select name="block_id" id="blockSelect" class="form-control custom-select" required>
                  <option value="">Select Block</option>
                  <?php while($b=$blocks->fetch_assoc()): ?>
                  <option value="<?php echo $b['id']; ?>" <?php echo ($_POST['block_id']??'')==$b['id']?'selected':''; ?>><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?> (<?php echo $b['gender']; ?>)</option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Room</label>
                <select name="room_id" id="roomSelect" class="form-control custom-select" required>
                  <option value="">Select Block First</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Bed Number</label>
                <select name="bed_number" id="bedSelect" class="form-control custom-select" required>
                  <option value="">Select Room First</option>
                </select>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-2" style="gap:10px;">
            <a href="view-students.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Assign Student</button>
          </div>
        </form>
      </div>
    </div>
  </div></div>
</div>
</div>

<script>
$('#blockSelect').on('change', function(){
    var blockId = $(this).val();
    $('#roomSelect').html('<option value="">Loading...</option>');
    $('#bedSelect').html('<option value="">Select Room First</option>');
    if (!blockId) { $('#roomSelect').html('<option value="">Select Block First</option>'); return; }
    $.get('<?php echo BASE_URL; ?>ajax/get-rooms.php?block_id='+blockId, function(data){
        $('#roomSelect').html(data);
    });
});
$('#roomSelect').on('change', function(){
    var roomId = $(this).val();
    $('#bedSelect').html('<option value="">Loading...</option>');
    if (!roomId) { $('#bedSelect').html('<option value="">Select Room First</option>'); return; }
    $.get('<?php echo BASE_URL; ?>ajax/get-beds.php?room_id='+roomId, function(data){
        $('#bedSelect').html(data);
    });
});
</script>
<?php require_once '../includes/footer.php'; ?>
