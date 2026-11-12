<?php
$pageTitle = 'Add Room';
require_once '../includes/config.php';
requireRole('admin');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_id    = (int)($_POST['block_id']    ?? 0);
    $room_number = sanitize($_POST['room_number'] ?? '');
    $capacity    = (int)($_POST['capacity']    ?? 4);
    $gender      = sanitize($_POST['gender']   ?? 'Male');
    $floor_no    = (int)($_POST['floor_no']    ?? 1);

    if (!$block_id)    $errors[] = 'Block is required.';
    if (!$room_number) $errors[] = 'Room number is required.';
    if ($capacity < 1) $errors[] = 'Capacity must be at least 1.';

    if (empty($errors)) {
        $stmt = $conn->prepare(
            "INSERT INTO rooms (block_id,room_number,capacity,gender,floor_no) VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('isisi', $block_id, $room_number, $capacity, $gender, $floor_no);
        if ($stmt->execute()) {
            setFlash('success', "Room $room_number registered successfully!");
            redirect('admin/rooms.php');
        } else {
            $errors[] = 'Error: ' . $conn->error;
        }
        $stmt->close();
    }
}
$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-door-open mr-2 text-primary"></i>Add New Room</h3></div>
    <a href="rooms.php" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i>Back
    </a>
  </div>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul>
  </div>
  <?php endif; ?>

  <div class="row justify-content-center"><div class="col-lg-6">
    <div class="form-card">
      <div class="form-card-header"><i class="fas fa-plus mr-2"></i>Room Information</div>
      <div class="form-card-body">
        <form method="POST">
          <div class="form-group">
            <label class="required">Block</label>
            <select name="block_id" class="form-control custom-select" required>
              <option value="">Select Block</option>
              <?php while($b=$blocks->fetch_assoc()): ?>
              <option value="<?php echo $b['id']; ?>"
                <?php echo ($_POST['block_id']??'')==$b['id']?'selected':''; ?>>
                <?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
                (<?php echo $b['gender']; ?>)
              </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="required">Room Number</label>
            <input type="text" name="room_number" class="form-control"
                   placeholder="e.g. 101" required
                   value="<?php echo htmlspecialchars($_POST['room_number']??''); ?>">
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="required">Capacity (beds)</label>
                <input type="number" name="capacity" class="form-control"
                       value="<?php echo (int)($_POST['capacity']??4); ?>" min="1" max="12" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Floor No.</label>
                <input type="number" name="floor_no" class="form-control"
                       value="<?php echo (int)($_POST['floor_no']??1); ?>" min="1">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control custom-select">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end" style="gap:10px;">
            <a href="rooms.php" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-2"></i>Add Room
            </button>
          </div>
        </form>
      </div>
    </div>
  </div></div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
