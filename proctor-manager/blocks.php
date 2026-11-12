<?php
$pageTitle = 'View Blocks';
require_once '../includes/config.php';
requireRole('proctor_manager');

$blocks = $conn->query("
    SELECT b.*,
           u.fname AS p_fname, u.lname AS p_lname,
           (SELECT COUNT(*) FROM rooms r WHERE r.block_id=b.id) AS rooms_count,
           (SELECT COUNT(*) FROM student_allocations sa
            JOIN rooms r ON sa.room_id=r.id
            WHERE r.block_id=b.id AND sa.status='active') AS alloc_count
    FROM blocks b
    LEFT JOIN users u ON b.proctor_id=u.id
    ORDER BY b.block_number
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_proctor_manager.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div><h3><i class="fas fa-th-large mr-2 text-primary"></i>All Blocks</h3></div>
    <a href="register-block.php" class="btn btn-primary btn-sm">
      <i class="fas fa-plus mr-1"></i>Register Block
    </a>
  </div>

  <div class="row">
    <?php while($b=$blocks->fetch_assoc()):
      $pct = $b['capacity'] > 0 ? round($b['alloc_count']/$b['capacity']*100) : 0;
      $barClass = $pct > 80 ? 'danger' : ($pct > 50 ? 'warning' : 'success');
    ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header" style="background:linear-gradient(135deg,var(--primary),#2a5ba8);color:#fff;padding:14px 20px;">
          <div class="d-flex justify-content-between align-items-center">
            <strong><i class="fas fa-building mr-2"></i><?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?></strong>
            <span class="badge badge-<?php echo $b['gender']==='Male'?'info':'danger'; ?>"><?php echo $b['gender']; ?></span>
          </div>
        </div>
        <div class="card-body">
          <div class="row text-center mb-3">
            <div class="col-4">
              <div style="font-size:1.5rem;font-weight:800;color:var(--primary);"><?php echo $b['capacity']; ?></div>
              <div style="font-size:11px;color:var(--muted);">Capacity</div>
            </div>
            <div class="col-4">
              <div style="font-size:1.5rem;font-weight:800;color:var(--success);"><?php echo $b['rooms_count']; ?></div>
              <div style="font-size:11px;color:var(--muted);">Rooms</div>
            </div>
            <div class="col-4">
              <div style="font-size:1.5rem;font-weight:800;color:var(--info);"><?php echo $b['alloc_count']; ?></div>
              <div style="font-size:11px;color:var(--muted);">Students</div>
            </div>
          </div>

          <div class="mb-2">
            <div class="d-flex justify-content-between" style="font-size:12px;margin-bottom:4px;">
              <span style="color:var(--muted);">Occupancy</span>
              <strong><?php echo $pct; ?>%</strong>
            </div>
            <div class="progress" style="height:8px;border-radius:4px;">
              <div class="progress-bar bg-<?php echo $barClass; ?>"
                   style="width:<?php echo $pct; ?>%;border-radius:4px;"></div>
            </div>
          </div>

          <?php if($b['p_fname']): ?>
          <p style="font-size:12px;color:var(--muted);margin:10px 0 0;">
            <i class="fas fa-user-tie mr-1"></i>Proctor:
            <strong><?php echo htmlspecialchars($b['p_fname'].' '.$b['p_lname']); ?></strong>
          </p>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-transparent">
          <a href="view-students.php?block=<?php echo $b['id']; ?>"
             class="btn btn-sm btn-primary btn-block">
            <i class="fas fa-users mr-1"></i>View Students
          </a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
