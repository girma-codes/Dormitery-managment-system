<?php
require_once '../includes/db.php';
$room_id = (int)($_GET['room_id'] ?? 0);
if (!$room_id) { echo '<option value="">Invalid room</option>'; exit; }
$room = $conn->query("SELECT capacity FROM rooms WHERE id=$room_id")->fetch_assoc();
if (!$room) { echo '<option value="">Room not found</option>'; exit; }

$takenResult = $conn->query("SELECT bed_number FROM student_allocations WHERE room_id=$room_id AND status='active'");
$taken = [];
while ($t = $takenResult->fetch_assoc()) $taken[] = $t['bed_number'];

echo '<option value="">Select Bed</option>';
for ($i = 1; $i <= $room['capacity']; $i++) {
    $bed = "Bed $i";
    $disabled = in_array($bed, $taken) ? 'disabled style="color:#ccc"' : '';
    $label = in_array($bed, $taken) ? "$bed (Taken)" : $bed;
    echo "<option value=\"$bed\" $disabled>$label</option>";
}
?>
