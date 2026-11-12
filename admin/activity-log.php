<?php
$pageTitle = 'Activity Log';
require_once '../includes/config.php';
requireRole('admin');

$logs = $conn->query("
    SELECT al.*, u.fname, u.lname, u.user_type
    FROM activity_log al
    LEFT JOIN users u ON al.user_id = u.id
    ORDER BY al.created_at DESC
    LIMIT 200
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div>
      <h3><i class="fas fa-history mr-2 text-primary"></i>Activity Log</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Activity Log</li>
      </ol></nav>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-list mr-2"></i>Recent Activity (Last 200)</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm"
             placeholder="Search logs..." style="width:200px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead>
          <tr><th>#</th><th>User</th><th>Role</th><th>Action</th><th>IP Address</th><th>Date &amp; Time</th></tr>
        </thead>
        <tbody>
          <?php $i=1; while($l=$logs->fetch_assoc()):
            $roleColors = [
              'admin'=>'danger','proctor_manager'=>'success',
              'proctor'=>'warning','student_dean'=>'info','student'=>'primary'
            ];
            $rc = $roleColors[$l['user_type']??''] ?? 'secondary';
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td>
              <div class="d-flex align-items-center" style="gap:8px;">
                <span style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;flex-shrink:0;">
                  <?php echo strtoupper(substr($l['fname']??'?',0,1)); ?>
                </span>
                <?php echo htmlspecialchars(($l['fname']??'Unknown').' '.($l['lname']??'')); ?>
              </div>
            </td>
            <td><span class="badge badge-<?php echo $rc; ?>"><?php echo ucfirst(str_replace('_',' ',$l['user_type']??'-')); ?></span></td>
            <td><?php echo htmlspecialchars($l['action']); ?></td>
            <td><small class="text-muted"><?php echo htmlspecialchars($l['ip_address']??'-'); ?></small></td>
            <td><small><?php echo date('d/m/Y H:i:s', strtotime($l['created_at'])); ?></small></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</div>
<?php require_once '../includes/footer.php'; ?>
