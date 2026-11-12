<?php
$pageTitle = 'View Dormitory Info';
require_once '../includes/config.php';

$result = null;
$error  = '';
$searched = false;

if (!empty($_GET['student_id'])) {
    $searched = true;
    $sid = sanitize($_GET['student_id']);
    $stmt = $conn->prepare("
        SELECT u.fname, u.lname, u.user_id_no, u.sex,
               sa.bed_number, sa.faculty, sa.batch_year, sa.academic_year,
               b.block_number, b.block_name,
               r.room_number, r.floor_no, r.capacity
        FROM users u
        JOIN student_allocations sa ON sa.student_id = u.id
        JOIN blocks b ON sa.block_id = b.id
        JOIN rooms r ON sa.room_id = r.id
        WHERE u.user_id_no = ? AND sa.status = 'active'
        LIMIT 1
    ");
    $stmt->bind_param('s', $sid); $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc(); $stmt->close();
    if (!$result) $error = "No dormitory allocation found for student ID: <strong>" . htmlspecialchars($sid) . "</strong>";
}
?>
<?php require_once '../includes/header.php'; ?>

<div style="background:linear-gradient(135deg,var(--primary-dk),var(--primary));padding:50px 0;text-align:center;color:#fff;">
  <h2 style="font-weight:800;">Check Your Dormitory Allocation</h2>
  <p style="color:rgba(255,255,255,.75);">Enter your Student ID to view your assigned dormitory information</p>
</div>

<main class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <!-- Search Form -->
        <div class="search-box mb-4">
          <form action="view-dorm.php" method="GET">
            <div class="d-flex" style="gap:10px;">
              <input type="text" name="student_id" class="form-control form-control-lg"
                     placeholder="Enter your Student ID (e.g. GUR/01867/09)"
                     value="<?php echo htmlspecialchars($_GET['student_id']??''); ?>" required style="flex:1;">
              <button type="submit" class="btn btn-primary btn-lg" style="padding:0 28px;">
                <i class="fas fa-search mr-1"></i>Search
              </button>
            </div>
          </form>
        </div>

        <!-- Error -->
        <?php if ($searched && $error): ?>
        <div class="alert alert-danger d-flex align-items-center" style="gap:14px;border-radius:12px;padding:20px;">
          <i class="fas fa-exclamation-circle fa-2x"></i>
          <div><?php echo $error; ?><br><small>Please check your ID and try again, or contact your proctor.</small></div>
        </div>
        <?php endif; ?>

        <!-- Result -->
        <?php if ($result): ?>
        <div class="alloc-result">
          <div class="alloc-header">
            <div class="d-flex align-items-center" style="gap:16px;">
              <i class="fas fa-check-circle fa-3x" style="opacity:.85;"></i>
              <div>
                <h4 style="margin:0;font-weight:800;">
                  <?php echo htmlspecialchars($result['fname'].' '.$result['lname']); ?>
                </h4>
                <p style="margin:0;opacity:.8;font-size:13px;">
                  Student ID: <strong><?php echo htmlspecialchars($result['user_id_no']); ?></strong>
                  &nbsp;&middot;&nbsp; <?php echo htmlspecialchars($result['sex']??''); ?>
                  &nbsp;&middot;&nbsp; <?php echo htmlspecialchars($result['faculty']??''); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="alloc-info-grid">
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-building mr-1"></i>Block Number</div>
              <div class="item-value"><?php echo htmlspecialchars($result['block_number']); ?></div>
              <small style="color:var(--muted);"><?php echo htmlspecialchars($result['block_name']??''); ?></small>
            </div>
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-door-open mr-1"></i>Room Number</div>
              <div class="item-value"><?php echo htmlspecialchars($result['room_number']); ?></div>
            </div>
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-bed mr-1"></i>Bed Number</div>
              <div class="item-value"><?php echo htmlspecialchars($result['bed_number']); ?></div>
            </div>
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-layer-group mr-1"></i>Floor</div>
              <div class="item-value"><?php echo $result['floor_no']??1; ?></div>
            </div>
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-graduation-cap mr-1"></i>Faculty</div>
              <div class="item-value" style="font-size:1rem;"><?php echo htmlspecialchars($result['faculty']??'N/A'); ?></div>
            </div>
            <div class="alloc-info-item">
              <div class="item-label"><i class="fas fa-calendar mr-1"></i>Academic Year</div>
              <div class="item-value" style="font-size:1rem;"><?php echo htmlspecialchars($result['academic_year']??'N/A'); ?></div>
            </div>
          </div>

          <div class="p-4 text-center" style="background:#f8fffe;border-top:1px solid var(--border);">
            <p style="color:var(--success);font-weight:600;margin-bottom:8px;">
              <i class="fas fa-info-circle mr-2"></i>Please bring your student ID when you move in.
            </p>
            <a href="#" onclick="window.print()" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-print mr-1"></i>Print This Information
            </a>
          </div>
        </div>
        <?php endif; ?>

        <!-- Instructions -->
        <?php if (!$searched): ?>
        <div class="card mt-2">
          <div class="card-body">
            <h6 style="font-weight:700;color:var(--primary);"><i class="fas fa-info-circle mr-2"></i>How to find your dormitory information:</h6>
            <ol style="font-size:13.5px;color:var(--muted);padding-left:20px;margin:10px 0 0;">
              <li>Enter your Student ID number (e.g. <strong>GUR/01867/09</strong>)</li>
              <li>Click the Search button</li>
              <li>Your block, room and bed assignment will be displayed</li>
              <li>If not found, contact your proctor or proctor manager</li>
            </ol>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</main>

<?php require_once '../includes/footer.php'; ?>
