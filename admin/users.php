<?php
$pageTitle = 'Manage Users';
require_once '../includes/config.php';
requireRole('admin');

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        $conn->query("UPDATE users SET status='inactive' WHERE id=$id");
        setFlash('success','User account deactivated successfully.');
    } else {
        setFlash('danger','You cannot deactivate your own account.');
    }
    redirect('admin/users.php');
}

$filter = sanitize($_GET['type'] ?? '');
$search = sanitize($_GET['search'] ?? '');
$sql = "SELECT * FROM users WHERE 1=1";
if ($filter) $sql .= " AND user_type='$filter'";
if ($search)  $sql .= " AND (fname LIKE '%$search%' OR lname LIKE '%$search%' OR username LIKE '%$search%')";
$sql .= " ORDER BY created_at DESC";
$users = $conn->query($sql);
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">
  <div class="page-header">
    <div>
      <h3><i class="fas fa-users mr-2 text-primary"></i>Manage Users</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li><li class="breadcrumb-item active">Users</li></ol></nav>
    </div>
    <a href="create-user.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Create User</a>
  </div>
  <?php echo getFlash(); ?>

  <!-- Filters -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row align-items-end" style="gap:0;">
        <div class="col-md-4 mb-2">
          <label style="font-size:12px;font-weight:600;">Search</label>
          <input type="text" name="search" class="form-control" placeholder="Name or username..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:12px;font-weight:600;">User Type</label>
          <select name="type" class="form-control custom-select">
            <option value="">All Types</option>
            <option value="admin" <?php echo $filter==='admin'?'selected':''; ?>>Admin</option>
            <option value="proctor_manager" <?php echo $filter==='proctor_manager'?'selected':''; ?>>Proctor Manager</option>
            <option value="proctor" <?php echo $filter==='proctor'?'selected':''; ?>>Proctor</option>
            <option value="student_dean" <?php echo $filter==='student_dean'?'selected':''; ?>>Student Dean</option>
            <option value="student" <?php echo $filter==='student'?'selected':''; ?>>Student</option>
          </select>
        </div>
        <div class="col-auto mb-2">
          <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>Filter</button>
          <a href="users.php" class="btn btn-outline-secondary ml-1"><i class="fas fa-sync"></i></a>
        </div>
      </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-list mr-2"></i>User List</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Quick search..." style="width:200px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead>
          <tr><th>#</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php $i=1; while($u=$users->fetch_assoc()): ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td>
              <div class="d-flex align-items-center" style="gap:10px;">
                <span style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;">
                  <?php echo strtoupper(substr($u['fname'],0,1)); ?>
                </span>
                <strong><?php echo htmlspecialchars($u['fname'].' '.$u['lname']); ?></strong>
              </div>
            </td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['email']??'-'); ?></td>
            <td>
              <?php
              $roleColors = ['admin'=>'danger','proctor_manager'=>'success','proctor'=>'warning','student_dean'=>'purple','student'=>'info'];
              $rc = $roleColors[$u['user_type']] ?? 'secondary';
              ?>
              <span class="badge badge-<?php echo $rc; ?>"><?php echo ucfirst(str_replace('_',' ',$u['user_type'])); ?></span>
            </td>
            <td><span class="badge badge-<?php echo $u['status']==='active'?'success':'secondary'; ?>"><?php echo ucfirst($u['status']); ?></span></td>
            <td><small><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></small></td>
            <td>
              <div class="btn-group btn-group-sm">
                <a href="edit-user.php?id=<?php echo $u['id']; ?>" class="btn btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                <?php if($u['id'] !== $_SESSION['user_id']): ?>
                <a href="users.php?delete=<?php echo $u['id']; ?>" class="btn btn-danger btn-delete" title="Deactivate"><i class="fas fa-ban"></i></a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php require_once '../includes/footer.php'; ?>
