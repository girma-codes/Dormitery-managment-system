<?php
$pageTitle = 'Register Room';
require_once '../includes/config.php';
requireRole('proctor');
$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $block_id   = (int)($_POST['block_id']??0);
    $room_number= sanitize($_POST['room_number']??'');
    $capacity   = (int)($_POST['capacity']??4);
    $gender     = sanitize($_POST['gender']??'Male');
    $floor_no   = (int)($_POST['floor_no']??1);
    if (!$block_id || !$room_number) $errors[]='Block and room number required.';
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO rooms(block_id,room_number,capacity,gender,floor_no) VALUES(?,?,?,?,?)");
        $stmt->bind_param('isiis',$block_id,$room_number,$capacity,$gender,$floor_no);
        if ($stmt->execute()) { setFlash('success',"Room $room_number registered!"); redirect('proctor/register-room.php'); }
        else $errors[]=$conn->error;
        $stmt->close();
    }
}
$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
$rooms  = $conn->query("SELECT r.*,b.block_number FROM rooms r JOIN blocks b ON r.block_id=b.id ORDER BY b.block_number,r.room_number LIMIT 20");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor.php'; ?>
<div class="dash-content">
  <div class="page-header"><div><h3><i class="fas fa-door-open mr-2 text-primary"></i>Register Room</h3></div></div>
  <?php echo getFlash(); ?>
  <?php if (!empty($errors)): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div><?php endif; ?>
  <div class="row">
    <div class="col-lg-5 mb-4">
      <div class="form-card">
        <div class="form-card-header"><i class="fas fa-plus mr-2"></i>New Room</div>
        <div class="form-card-body">
          <form method="POST">
            <div class="form-group"><label class="required">Block</label><select name="block_id" class="form-control custom-select" required><option value="">Select Block</option><?php while($b=$blocks->fetch_assoc()): ?><option value="<?php echo $b['id']; ?>"><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?></option><?php endwhile; ?></select></div>
            <div class="form-group"><label class="required">Room Number</label><input type="text" name="room_number" class="form-control" placeholder="e.g. 101" required></div>
            <div class="row">
              <div class="col-6"><div class="form-group"><label>Capacity</label><input type="number" name="capacity" class="form-control" value="4" min="1" max="10"></div></div>
              <div class="col-6"><div class="form-group"><label>Floor</label><input type="number" name="floor_no" class="form-control" value="1" min="1"></div></div>
            </div>
            <div class="form-group"><label>Gender</label><select name="gender" class="form-control custom-select"><option value="Male">Male</option><option value="Female">Female</option></select></div>
            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-2"></i>Register Room</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="table-card">
        <div class="table-header"><h5>Recent Rooms</h5></div>
        <div class="table-responsive">
          <table class="table">
            <thead><tr><th>Block</th><th>Room No.</th><th>Capacity</th><th>Occupied</th><th>Gender</th><th>Status</th></tr></thead>
            <tbody>
              <?php while($r=$rooms->fetch_assoc()): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($r['block_number']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['room_number']); ?></td>
                <td><?php echo $r['capacity']; ?></td>
                <td><?php echo $r['occupied']; ?></td>
                <td><?php echo htmlspecialchars($r['gender']); ?></td>
                <td><span class="badge badge-<?php echo $r['status']==='available'?'success':'warning'; ?>"><?php echo ucfirst($r['status']); ?></span></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
