<?php
$pageTitle = 'My Dormitory';
require_once '../includes/config.php';
requireRole('student');
$sid = $_SESSION['user_id'];

$allocation = $conn->query("
    SELECT sa.*, u.fname, u.lname, u.user_id_no, u.sex, u.email,
           b.block_number, b.block_name, b.gender AS block_gender,
           r.room_number, r.floor_no, r.capacity AS room_capacity,
           p.fname AS p_fname, p.lname AS p_lname,
           al.fname AS alloc_fname, al.lname AS alloc_lname
    FROM student_allocations sa
    JOIN users u ON sa.student_id=u.id
    JOIN blocks b ON sa.block_id=b.id
    JOIN rooms r ON sa.room_id=r.id
    LEFT JOIN users p ON b.proctor_id=p.id
    LEFT JOIN users al ON sa.allocated_by=al.id
    WHERE sa.student_id=$sid AND sa.status='active'
    LIMIT 1
")->fetch_assoc();
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div>
      <h3><i class="fas fa-bed mr-2 text-primary"></i>My Dormitory Information</h3>
    </div>
    <?php if($allocation): ?>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
      <i class="fas fa-print mr-1"></i>Print
    </button>
    <?php endif; ?>
  </div>

  <?php if ($allocation): ?>

  <!-- Print Header (only visible on print) -->
  <div style="display:none;" class="print-only text-center mb-4">
    <h3>University of Gondar — Dormitory Management System</h3>
    <h5>Atse Tewodros Campus — Dormitory Allocation</h5>
    <hr>
  </div>

  <div class="alloc-result mb-4">
    <div class="alloc-header">
      <div class="d-flex align-items-center" style="gap:16px;">
        <span style="width:65px;height:65px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;flex-shrink:0;">
          <?php echo strtoupper(substr($allocation['fname'],0,1)); ?>
        </span>
        <div>
          <h4 style="margin:0;font-weight:800;"><?php echo htmlspecialchars($allocation['fname'].' '.$allocation['lname']); ?></h4>
          <p style="margin:0;opacity:.85;font-size:13px;">
            <strong><?php echo htmlspecialchars($allocation['user_id_no']??'N/A'); ?></strong>
            &nbsp;&middot;&nbsp; <?php echo htmlspecialchars($allocation['sex']??''); ?>
            &nbsp;&middot;&nbsp; <?php echo htmlspecialchars($allocation['faculty']??''); ?>
          </p>
          <?php if($allocation['academic_year']): ?>
          <span style="background:rgba(255,255,255,.2);padding:2px 10px;border-radius:12px;font-size:11px;margin-top:4px;display:inline-block;">
            Academic Year: <?php echo htmlspecialchars($allocation['academic_year']); ?>
          </span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="alloc-info-grid">
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-building mr-1"></i>Block</div>
        <div class="item-value"><?php echo htmlspecialchars($allocation['block_number']); ?></div>
        <small style="color:var(--muted);"><?php echo htmlspecialchars($allocation['block_name']??''); ?></small>
      </div>
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-door-open mr-1"></i>Room No.</div>
        <div class="item-value"><?php echo htmlspecialchars($allocation['room_number']); ?></div>
      </div>
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-bed mr-1"></i>Bed No.</div>
        <div class="item-value"><?php echo htmlspecialchars($allocation['bed_number']); ?></div>
      </div>
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-layer-group mr-1"></i>Floor</div>
        <div class="item-value"><?php echo $allocation['floor_no']??1; ?></div>
      </div>
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-users mr-1"></i>Room Capacity</div>
        <div class="item-value"><?php echo $allocation['room_capacity']; ?> beds</div>
      </div>
      <div class="alloc-info-item">
        <div class="item-label"><i class="fas fa-venus-mars mr-1"></i>Block Type</div>
        <div class="item-value" style="font-size:1rem;"><?php echo htmlspecialchars($allocation['block_gender']); ?></div>
      </div>
    </div>

    <?php if($allocation['p_fname']): ?>
    <div class="p-3" style="background:#f0f8ff;border-top:1px solid var(--border);">
      <strong style="color:var(--primary);font-size:13px;">
        <i class="fas fa-user-tie mr-2"></i>Your Proctor:
        <?php echo htmlspecialchars($allocation['p_fname'].' '.$allocation['p_lname']); ?>
      </strong>
    </div>
    <?php endif; ?>

    <div class="p-3 d-flex justify-content-between align-items-center" style="border-top:1px solid var(--border);">
      <small style="color:var(--success);font-weight:600;">
        <i class="fas fa-check-circle mr-1"></i>
        Allocated on: <?php echo date('d F Y', strtotime($allocation['allocated_at'])); ?>
      </small>
      <span class="badge badge-success" style="font-size:12px;padding:6px 14px;">Active</span>
    </div>
  </div>

  <!-- Roommates -->
  <?php
  $roommates = $conn->query("
      SELECT u.fname, u.lname, u.user_id_no, sa.bed_number
      FROM student_allocations sa
      JOIN users u ON sa.student_id=u.id
      WHERE sa.room_id={$allocation['room_id']}
        AND sa.status='active'
        AND sa.student_id != $sid
      ORDER BY sa.bed_number
  ");
  if ($roommates->num_rows > 0):
  ?>
  <div class="card">
    <div class="card-header bg-primary text-white">
      <i class="fas fa-users mr-2"></i>Roommates in Room <?php echo htmlspecialchars($allocation['room_number']); ?>
    </div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>Name</th><th>ID No.</th><th>Bed No.</th></tr></thead>
        <tbody>
          <?php while($rm=$roommates->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($rm['fname'].' '.$rm['lname']); ?></td>
            <td><small class="badge badge-secondary"><?php echo htmlspecialchars($rm['user_id_no']??'-'); ?></small></td>
            <td><strong><?php echo htmlspecialchars($rm['bed_number']); ?></strong></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <?php else: ?>
  <div class="card">
    <div class="card-body text-center py-5">
      <i class="fas fa-bed fa-5x text-muted mb-4" style="opacity:.3;"></i>
      <h4 style="color:var(--muted);">No Dormitory Assigned Yet</h4>
      <p style="color:var(--muted);">Your dormitory allocation has not been set up. Please contact your proctor.</p>
      <a href="submit-comment.php" class="btn btn-primary">
        <i class="fas fa-comment mr-1"></i>Contact Proctor
      </a>
    </div>
  </div>
  <?php endif; ?>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
