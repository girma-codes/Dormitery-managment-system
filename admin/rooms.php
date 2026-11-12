<?php
$pageTitle = 'Manage Rooms';
require_once '../includes/config.php';
requireRole('admin');

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM rooms WHERE id=$id");
    setFlash('success','Room deleted.'); redirect('admin/rooms.php');
}

$block_filter = (int)($_GET['block'] ?? 0);
$sql = "SELECT r.*, b.block_number, b.block_name,
        (SELECT COUNT(*) FROM student_allocations sa WHERE sa.room_id=r.id AND sa.status='active') AS students
        FROM rooms r JOIN blocks b ON r.block_id=b.id";
if ($block_filter) $sql .= " WHERE r.block_id=$block_filter";
$sql .= " ORDER BY b.block_number, r.room_number";
$rooms  = $conn->query($sql);
$blocks = $conn->query("SELECT * FROM blocks WHERE status='active' ORDER BY block_number");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_admin.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div>
      <h3><i class="fas fa-door-open mr-2 text-primary"></i>Manage Rooms</h3>
      <nav aria-label="breadcrumb"><ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Rooms</li>
      </ol></nav>
    </div>
    <a href="add-room.php" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Add Room</a>
  </div>

  <?php echo getFlash(); ?>

  <!-- Filter -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="d-flex align-items-end" style="gap:12px;">
        <div>
          <label style="font-size:12px;font-weight:600;display:block;">Filter by Block</label>
          <select name="block" class="form-control custom-select" style="width:220px;">
            <option value="">All Blocks</option>
            <?php while($b=$blocks->fetch_assoc()): ?>
            <option value="<?php echo $b['id']; ?>" <?php echo $block_filter==$b['id']?'selected':''; ?>>
              <?php echo htmlspecialchars($b['block_name']?:$b['block_number']); ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i>Filter</button>
        <a href="rooms.php" class="btn btn-outline-secondary"><i class="fas fa-sync"></i></a>
      </form>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <h5><i class="fas fa-list mr-2"></i>Room List</h5>
      <input type="text" id="tableSearch" class="form-control form-control-sm"
             placeholder="Search rooms..." style="width:200px;border-radius:8px;">
    </div>
    <div class="table-responsive">
      <table class="table" id="searchableTable">
        <thead>
          <tr>
            <th>#</th><th>Block</th><th>Room No.</th><th>Floor</th>
            <th>Capacity</th><th>Occupied</th><th>Available</th>
            <th>Gender</th><th>Status</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($r=$rooms->fetch_assoc()):
            $available = $r['capacity'] - $r['students'];
            $pct = $r['capacity'] > 0 ? round($r['students']/$r['capacity']*100) : 0;
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><span class="badge badge-primary"><?php echo htmlspecialchars($r['block_number']); ?></span></td>
            <td><strong><?php echo htmlspecialchars($r['room_number']); ?></strong></td>
            <td><?php echo $r['floor_no']; ?></td>
            <td><?php echo $r['capacity']; ?></td>
            <td>
              <span style="font-weight:700;color:<?php echo $r['students']>0?'var(--danger)':'var(--muted)'; ?>;">
                <?php echo $r['students']; ?>
              </span>
            </td>
            <td>
              <span style="font-weight:700;color:<?php echo $available>0?'var(--success)':'var(--danger)'; ?>;">
                <?php echo $available; ?>
              </span>
            </td>
            <td><?php echo htmlspecialchars($r['gender']); ?></td>
            <td>
              <?php
              $sc = $r['status']==='available'?'success':($r['status']==='full'?'danger':'warning');
              ?>
              <span class="badge badge-<?php echo $sc; ?>"><?php echo ucfirst($r['status']); ?></span>
            </td>
            <td>
              <a href="rooms.php?delete=<?php echo $r['id']; ?>"
                 class="btn btn-danger btn-sm btn-delete" title="Delete">
                <i class="fas fa-trash"></i>
              </a>
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
