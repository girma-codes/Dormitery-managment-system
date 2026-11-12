<?php
$pageTitle = 'Events';
require_once '../includes/config.php';
$events = $conn->query("SELECT n.*,u.fname,u.lname FROM news_events n LEFT JOIN users u ON n.posted_by=u.id WHERE n.status='published' AND n.type='event' ORDER BY n.event_date DESC");
?>
<?php require_once '../includes/header.php'; ?>
<div style="background:linear-gradient(135deg,var(--primary-dk),var(--primary));padding:50px 0;text-align:center;color:#fff;">
  <h2 style="font-weight:800;"><i class="fas fa-calendar-alt mr-3"></i>Upcoming Events</h2>
  <p style="color:rgba(255,255,255,.75);">Stay up to date with campus events and activities</p>
</div>
<main class="py-5">
  <div class="container">
    <div class="row">
      <?php while($e=$events->fetch_assoc()): ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card news-card h-100" style="border-top:4px solid var(--success);">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3" style="gap:12px;">
              <div style="background:linear-gradient(135deg,var(--success),#1e8449);color:#fff;padding:10px 14px;border-radius:10px;text-align:center;min-width:60px;">
                <div style="font-size:1.4rem;font-weight:800;line-height:1;"><?php echo date('d', strtotime($e['event_date']?:$e['created_at'])); ?></div>
                <div style="font-size:10px;text-transform:uppercase;"><?php echo date('M', strtotime($e['event_date']?:$e['created_at'])); ?></div>
              </div>
              <div>
                <h6 style="font-weight:700;color:var(--primary);margin:0;"><?php echo htmlspecialchars($e['title']); ?></h6>
                <small style="color:var(--muted);">Posted by <?php echo htmlspecialchars($e['fname'].' '.$e['lname']); ?></small>
              </div>
            </div>
            <p style="color:var(--muted);font-size:13px;"><?php echo substr(strip_tags($e['content']),0,120); ?>...</p>
          </div>
          <div class="card-footer bg-transparent">
            <a href="news.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-success btn-block">View Details</a>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</main>
<?php require_once '../includes/footer.php'; ?>
