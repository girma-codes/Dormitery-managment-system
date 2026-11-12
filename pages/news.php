<?php
$pageTitle = 'News & Events';
require_once '../includes/config.php';

// Single news view
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $post = $conn->query("SELECT n.*,u.fname,u.lname FROM news_events n LEFT JOIN users u ON n.posted_by=u.id WHERE n.id=$id AND n.status='published'")->fetch_assoc();
}

// All news
$type   = sanitize($_GET['type'] ?? '');
$sql = "SELECT n.*,u.fname,u.lname FROM news_events n LEFT JOIN users u ON n.posted_by=u.id WHERE n.status='published'";
if ($type) $sql .= " AND n.type='$type'";
$sql .= " ORDER BY n.created_at DESC";
$allNews = $conn->query($sql);
?>
<?php require_once '../includes/header.php'; ?>

<div style="background:linear-gradient(135deg,var(--primary-dk),var(--primary));padding:50px 0;text-align:center;color:#fff;">
  <h2 style="font-weight:800;"><i class="fas fa-newspaper mr-3"></i>News &amp; Events</h2>
  <p style="color:rgba(255,255,255,.75);">Stay updated with the latest announcements and events</p>
</div>

<main class="py-5">
  <div class="container">

    <?php if (isset($post) && $post): ?>
    <!-- Single Post View -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <a href="news.php" class="btn btn-outline-secondary btn-sm mb-4"><i class="fas fa-arrow-left mr-1"></i>Back to News</a>
        <div class="card">
          <div class="card-header" style="background:linear-gradient(135deg,var(--primary),#2a5ba8);color:#fff;padding:20px 24px;">
            <span class="badge badge-warning mb-2"><?php echo ucfirst($post['type']); ?></span>
            <h4 style="font-weight:800;margin:0;"><?php echo htmlspecialchars($post['title']); ?></h4>
            <small style="opacity:.75;"><i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($post['fname'].' '.$post['lname']); ?> &nbsp;&middot;&nbsp; <i class="fas fa-clock mr-1"></i><?php echo date('d F Y', strtotime($post['created_at'])); ?></small>
          </div>
          <div class="card-body" style="font-size:15px;line-height:1.8;padding:28px;">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
          </div>
        </div>
      </div>
    </div>

    <?php else: ?>
    <!-- News List -->
    <div class="row mb-4">
      <div class="col-auto">
        <div class="btn-group">
          <a href="news.php" class="btn btn-sm <?php echo !$type?'btn-primary':'btn-outline-primary'; ?>">All</a>
          <a href="news.php?type=news" class="btn btn-sm <?php echo $type==='news'?'btn-primary':'btn-outline-primary'; ?>">News</a>
          <a href="news.php?type=event" class="btn btn-sm <?php echo $type==='event'?'btn-primary':'btn-outline-primary'; ?>">Events</a>
          <a href="news.php?type=announcement" class="btn btn-sm <?php echo $type==='announcement'?'btn-primary':'btn-outline-primary'; ?>">Announcements</a>
        </div>
      </div>
    </div>
    <div class="row">
      <?php while($n=$allNews->fetch_assoc()): ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card news-card h-100">
          <div class="card-img-wrap position-relative">
            <i class="fas fa-<?php echo $n['type']==='event'?'calendar-alt':'newspaper'; ?>"></i>
            <span class="news-type-badge"><?php echo ucfirst($n['type']); ?></span>
          </div>
          <div class="card-body">
            <h6 style="font-weight:700;color:var(--primary);"><?php echo htmlspecialchars($n['title']); ?></h6>
            <p style="color:var(--muted);font-size:13px;"><?php echo substr(strip_tags($n['content']),0,120); ?>...</p>
          </div>
          <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
            <small style="color:var(--muted);"><i class="fas fa-clock mr-1"></i><?php echo date('d M Y', strtotime($n['created_at'])); ?></small>
            <a href="news.php?id=<?php echo $n['id']; ?>" class="btn btn-sm btn-primary">Read More</a>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>

  </div>
</main>

<?php require_once '../includes/footer.php'; ?>
