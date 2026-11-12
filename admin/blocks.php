<?php
$pageTitle = 'Manage Blocks';
require_once '../includes/config.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("UPDATE blocks SET status='inactive' WHERE id=$id");
    setFlash('success','Block deactivated.');
    redirect('admin/blocks.php');
}

$blocks = $conn->query("SELECT b.*,u.fname,u.lname,(SELECT COUNT(*) FROM rooms r WHERE r.block_id=b.id) rooms_count FROM blocks b LEFT JOIN users u ON b.proctor_id=u.id ORDER BY b.block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div>
      <h3><i class="fas fa-building mr-2 text-primary"></i>Dormitory Blocks</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li><li class="breadcrumb-item active">Blocks</li></ol></nav>
    </div>
    <a href="add-block.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Add Block</a>
  </div>
  <?php echo getFlash(); ?>

  <div class="row">
    <?php while($b=$blocks->fetch_assoc()): ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;">
              <i class="fas fa-building"></i>
            </div>
            <span class="badge badge-<?php echo $b['status']==='active'?'success':'secondary'; ?>"><?php echo ucfirst($b['status']); ?></span>
          </div>
          <h5 style="font-weight:700;color:var(--primary);"><?php echo htmlspecialchars($b['block_name'] ?: 'Block '.$b['block_number']); ?></h5>
          <p class="text-muted mb-2" style="font-size:13px;">Block No: <strong><?php echo htmlspecialchars($b['block_number']); ?></strong></p>
          <div class="row text-center mt-3" style="border-top:1px solid var(--border);padding-top:12px;">
            <div class="col-4">
              <div style="font-size:1.3rem;font-weight:800;color:var(--primary);"><?php echo $b['capacity']; ?></div>
              <div style="font-size:10px;color:var(--muted);">Capacity</div>
            </div>
            <div class="col-4">
              <div style="font-size:1.3rem;font-weight:800;color:var(--success);"><?php echo $b['rooms_count']; ?></div>
              <div style="font-size:10px;color:var(--muted);">Rooms</div>
            </div>
            <div class="col-4">
              <div style="font-size:1.1rem;font-weight:700;color:var(--info);"><?php echo $b['gender']; ?></div>
              <div style="font-size:10px;color:var(--muted);">Gender</div>
            </div>
          </div>
          <?php if($b['fname']): ?>
          <p class="mt-2 mb-0" style="font-size:12px;color:var(--muted);"><i class="fas fa-user mr-1"></i>Proctor: <?php echo htmlspecialchars($b['fname'].' '.$b['lname']); ?></p>
          <?php endif; ?>
          <div class="d-flex mt-3" style="gap:8px;">
            <a href="edit-block.php?id=<?php echo $b['id']; ?>" class="btn btn-warning btn-sm flex-fill"><i class="fas fa-edit mr-1"></i>Edit</a>
            <a href="rooms.php?block=<?php echo $b['id']; ?>" class="btn btn-info btn-sm flex-fill"><i class="fas fa-door-open mr-1"></i>Rooms</a>
            <a href="blocks.php?delete=<?php echo $b['id']; ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
