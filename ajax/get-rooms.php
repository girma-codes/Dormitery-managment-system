<?php
require_once '../includes/db.php';
$block_id = (int)($_GET['block_id'] ?? 0);
if (!$block_id) { echo '<option value="">Invalid block</option>'; exit; }
$rooms = $conn->query("SELECT r.*,(r.capacity - r.occupied) AS available FROM rooms r WHERE r.block_id=$block_id AND r.status!='maintenance' ORDER BY r.room_number");
echo '<option value="">Select Room</option>';
while ($r = $rooms->fetch_assoc()) {
    $avail = $r['available'];
    $disabled = $avail <= 0 ? 'disabled' : '';
    echo "<option value=\"{$r['id']}\" $disabled>Room {$r['room_number']} ({$avail} beds free)</option>";
}
?>
