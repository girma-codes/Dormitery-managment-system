<?php
$pageTitle = 'News & Events';
require_once '../includes/config.php';
requireRole('student');

$news = $conn->query("
    SELECT n.*, u.fname, u.lname
    FROM news_events n
    LEFT JOIN users u ON n.posted_by=u.id
    WHERE n.status='published'
    ORDER BY n.created_at DESC
");
?>
<?php require_once '../includes/header.php'; ?>
<div class="dash-wrapper">
<?php require_once '../includes/sidebar_student.php'; ?>
<div class="dash-content">

  <div class="page-header">
    <div><h3><i class="fas fa-newspaper mr-2 text-primary"></i>News &amp; Events</h3></div>
  </div>

  <div class="row">
    <?php while($n=$news->fetch_assoc()): ?>
    <div class="col-lg-6 mb-4">
      <div class="card h-100" style="border-left:4px solid <?php echo $n['type']==='event'?'var(--success)':($n['type']==='announcement'?'var(--accent)':'var(--info)'); ?>;">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <span class="badge badge-<?php echo $n['type']==='event'?'success':($n['type']==='announcement'?'warning':'info'); ?>">
              <i class="fas fa-<?php echo $n['type']==='event'?'calendar-alt':($n['type']==='announcement'?'bullhorn':'newspaper'); ?> mr-1"></i>
              <?php echo ucfirst($n['type']); ?>
            </span>
            <small style="color:var(--muted);">
              <?php echo date('d M Y', strtotime($n['created_at'])); ?>
            </small>
          </div>
          <h6 style="font-weight:700;color:var(--primary);"><?php echo htmlspecialchars($n['title']); ?></h6>
          <p style="font-size:13.5px;color:var(--muted);"><?php echo substr(strip_tags($n['content']),0,150); ?>...</p>
          <?php if($n['event_date']): ?>
          <p style="font-size:12px;color:var(--success);font-weight:600;margin-bottom:0;">
            <i class="fas fa-calendar-check mr-1"></i>Event Date: <?php echo date('d F Y', strtotime($n['event_date'])); ?>
          </p>
          <?php endif; ?>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
          <small style="color:var(--muted);">
            <i class="fas fa-user mr-1"></i>
            <?php echo htmlspecialchars($n['fname'].' '.$n['lname']); ?>
          </small>
          <button onclick="
            document.getElementById('modal-title').textContent='<?php echo addslashes($n['title']); ?>';
            document.getElementById('modal-content').textContent='<?php echo addslashes($n['content']); ?>';
            $('#newsModal').modal('show');
          " class="btn btn-sm btn-primary">Read More</button>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

</div>
</div>

<!-- News Modal -->
<div class="modal fade" id="newsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:14px;overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg,var(--primary),#2a5ba8);color:#fff;border:none;">
        <h5 class="modal-title" id="modal-title"></h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="padding:28px;font-size:15px;line-height:1.8;white-space:pre-line;">
        <p id="modal-content"></p>
      </div>
    </div>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>
