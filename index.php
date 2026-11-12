<?php
$pageTitle = 'Home';
require_once 'includes/config.php';

$news   = $conn->query("SELECT n.*,u.fname,u.lname FROM news_events n LEFT JOIN users u ON n.posted_by=u.id WHERE n.status='published' AND n.type='news' ORDER BY n.created_at DESC LIMIT 3");
$events = $conn->query("SELECT * FROM news_events WHERE status='published' AND type='event' ORDER BY event_date ASC LIMIT 3");

$totalStudents = $conn->query("SELECT COUNT(*) c FROM users WHERE user_type='student' AND status='active'")->fetch_assoc()['c'];
$totalBlocks   = $conn->query("SELECT COUNT(*) c FROM blocks WHERE status='active'")->fetch_assoc()['c'];
$totalRooms    = $conn->query("SELECT COUNT(*) c FROM rooms")->fetch_assoc()['c'];
$totalAlloc    = $conn->query("SELECT COUNT(*) c FROM student_allocations WHERE status='active'")->fetch_assoc()['c'];
?>
<?php require_once 'includes/header.php'; ?>

<!-- HERO -->
<section class="hero-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <p style="color:var(--accent-lt);font-weight:600;font-size:14px;letter-spacing:2px;text-transform:uppercase;">
          <i class="fas fa-star"></i> Atse Tewodros Campus
        </p>
        <h1>University of Gondar<br><span style="color:var(--accent-lt);">Dormitory Management</span></h1>
        <p class="mt-3" style="font-size:1.1rem;color:rgba(255,255,255,.85);max-width:600px;margin:0 auto;">
          A modern web-based platform for seamless dormitory allocation, real-time updates, and efficient campus accommodation management.
        </p>
        <div class="mt-5 d-flex justify-content-center flex-wrap" style="gap:14px;">
          <a href="login.php" class="hero-btn"><i class="fas fa-sign-in-alt mr-2"></i>Access Portal</a>
          <a href="pages/view-dorm.php" class="hero-btn-outline"><i class="fas fa-search mr-2"></i>Check My Dorm</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="container">
    <div class="row">
      <div class="col-6 col-md-3 stat-item py-2">
        <div class="stat-num"><?php echo $totalStudents; ?>+</div>
        <div class="stat-lbl">Students</div>
      </div>
      <div class="col-6 col-md-3 stat-item py-2">
        <div class="stat-num"><?php echo $totalBlocks; ?></div>
        <div class="stat-lbl">Dorm Blocks</div>
      </div>
      <div class="col-6 col-md-3 stat-item py-2">
        <div class="stat-num"><?php echo $totalRooms; ?></div>
        <div class="stat-lbl">Rooms</div>
      </div>
      <div class="col-6 col-md-3 stat-item py-2">
        <div class="stat-num"><?php echo $totalAlloc; ?></div>
        <div class="stat-lbl">Allocations</div>
      </div>
    </div>
  </div>
</div>

<main>
  <!-- QUICK DORM SEARCH -->
  <div class="container" style="margin-top:-30px;position:relative;z-index:10;">
    <div class="search-box">
      <div class="row align-items-center">
        <div class="col-md-5">
          <h4><i class="fas fa-search-location text-primary mr-2"></i>Find Your Dormitory</h4>
          <p class="text-muted mb-0" style="font-size:13.5px;">Enter your Student ID to instantly view your block, room &amp; bed assignment.</p>
        </div>
        <div class="col-md-7 mt-3 mt-md-0">
          <form action="pages/view-dorm.php" method="GET" class="d-flex" style="gap:10px;">
            <input type="text" name="student_id" class="form-control form-control-lg" placeholder="Student ID (e.g. GUR/01867/09)" required style="flex:1;">
            <button type="submit" class="btn btn-primary btn-lg text-nowrap" style="padding:0 24px;">
              <i class="fas fa-search mr-1"></i> Search
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- FEATURES -->
  <div class="container py-5 mt-3">
    <div class="section-heading">
      <h2>System Features</h2>
      <div class="divider"></div>
      <p>Everything you need for efficient dormitory management — all in one platform.</p>
    </div>

    <div class="row">
      <?php
      $features = [
        ['icon'=>'fas fa-bed',          'title'=>'Smart Allocation',    'text'=>'Automated student-to-room assignment based on gender, year, faculty and department.'],
        ['icon'=>'fas fa-newspaper',    'title'=>'News & Events',       'text'=>'Real-time announcements, event schedules and important notices from management.'],
        ['icon'=>'fas fa-chart-pie',    'title'=>'Reports',             'text'=>'Generate detailed allocation reports, attendance summaries and statistical analyses.'],
        ['icon'=>'fas fa-clipboard-list','title'=>'Attendance',         'text'=>'Proctors record and monitor student attendance directly from the web interface.'],
        ['icon'=>'fas fa-shield-alt',   'title'=>'Role-Based Security', 'text'=>'Five user roles each with strict access controls to protect sensitive data.'],
        ['icon'=>'fas fa-comments',     'title'=>'Feedback System',     'text'=>'Students and proctors can submit feedback and view responses within the portal.'],
      ];
      foreach ($features as $f): ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="feature-box">
          <div class="feat-icon"><i class="<?php echo $f['icon']; ?>"></i></div>
          <h5><?php echo $f['title']; ?></h5>
          <p><?php echo $f['text']; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- HOW IT WORKS -->
  <div style="background:#fff;padding:60px 0;">
    <div class="container">
      <div class="section-heading">
        <h2>How It Works</h2>
        <div class="divider"></div>
      </div>
      <div class="row justify-content-center">
        <?php
        $steps = [
          ['num'=>'01','icon'=>'fas fa-user-plus',    'title'=>'Admin Creates Account','text'=>'The administrator creates accounts for proctors, proctor managers, student deans and students.'],
          ['num'=>'02','icon'=>'fas fa-building',     'title'=>'Register Blocks & Rooms','text'=>'The proctor manager registers dormitory blocks and proctors register individual rooms.'],
          ['num'=>'03','icon'=>'fas fa-random',       'title'=>'Allocate Students',   'text'=>'Proctors assign students to specific blocks, rooms and bed numbers based on their details.'],
          ['num'=>'04','icon'=>'fas fa-eye',          'title'=>'Students View Info',  'text'=>'Students log in and instantly view their dormitory allocation, news and events online.'],
        ];
        foreach ($steps as $s): ?>
        <div class="col-lg-3 col-md-6 mb-4 text-center">
          <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#2a5ba8);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.6rem;color:#fff;box-shadow:0 8px 20px rgba(26,58,107,.3);">
            <i class="<?php echo $s['icon']; ?>"></i>
          </div>
          <span style="display:inline-block;background:var(--accent);color:#fff;font-size:10px;font-weight:800;padding:2px 10px;border-radius:20px;letter-spacing:1px;margin-bottom:10px;"><?php echo $s['num']; ?></span>
          <h6 style="font-weight:700;color:var(--primary);"><?php echo $s['title']; ?></h6>
          <p style="color:var(--muted);font-size:13px;"><?php echo $s['text']; ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- LATEST NEWS -->
  <?php $newsArr = []; while ($n = $news->fetch_assoc()) $newsArr[] = $n; ?>
  <?php if (!empty($newsArr)): ?>
  <div class="container py-5">
    <div class="section-heading">
      <h2>Latest News &amp; Announcements</h2>
      <div class="divider"></div>
    </div>
    <div class="row">
      <?php foreach ($newsArr as $n): ?>
      <div class="col-md-4 mb-4">
        <div class="card news-card h-100">
          <div class="card-img-wrap position-relative">
            <i class="fas fa-newspaper"></i>
            <span class="news-type-badge"><?php echo ucfirst($n['type']); ?></span>
          </div>
          <div class="card-body">
            <h6 style="font-weight:700;color:var(--primary);"><?php echo htmlspecialchars($n['title']); ?></h6>
            <p style="color:var(--muted);font-size:13px;"><?php echo substr(strip_tags($n['content']),0,110); ?>...</p>
          </div>
          <div class="card-footer bg-transparent d-flex justify-content-between align-items-center" style="border-top:1px solid var(--border);">
            <small style="color:var(--muted);"><i class="fas fa-clock mr-1"></i><?php echo date('d M Y', strtotime($n['created_at'])); ?></small>
            <a href="pages/news.php?id=<?php echo $n['id']; ?>" class="btn btn-sm btn-primary">Read More</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-2">
      <a href="pages/news.php" class="btn btn-outline-primary">View All News <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
  </div>
  <?php endif; ?>

  <!-- PORTAL ACCESS CARDS -->
  <div style="background:#fff;padding:60px 0;">
    <div class="container">
      <div class="section-heading">
        <h2>Portal Access</h2>
        <div class="divider"></div>
        <p>Log in with your credentials to access your dedicated dashboard.</p>
      </div>
      <div class="row justify-content-center">
        <?php
        $portals = [
          ['icon'=>'fas fa-user-shield','title'=>'Administrator', 'desc'=>'Manage all user accounts and oversee the full system.', 'color'=>'linear-gradient(135deg,#1a1a2e,#1e3c72)'],
          ['icon'=>'fas fa-user-tie',  'title'=>'Proctor Manager','desc'=>'Register blocks, allocate proctors and generate reports.', 'color'=>'linear-gradient(135deg,#1a6b35,#27ae60)'],
          ['icon'=>'fas fa-hard-hat',  'title'=>'Proctor',        'desc'=>'Assign students to rooms, record attendance and reports.', 'color'=>'linear-gradient(135deg,#b7510a,#e67e22)'],
          ['icon'=>'fas fa-chalkboard-teacher','title'=>'Student Dean','desc'=>'Post news, announcements and view student feedback.', 'color'=>'linear-gradient(135deg,#4a235a,#9b59b6)'],
          ['icon'=>'fas fa-user-graduate','title'=>'Student',     'desc'=>'View dormitory allocation, news and submit comments.', 'color'=>'linear-gradient(135deg,#1a5276,#2980b9)'],
        ];
        foreach ($portals as $p): ?>
        <div class="col-lg col-md-4 col-6 mb-3">
          <a href="login.php" style="text-decoration:none;">
            <div style="background:<?php echo $p['color']; ?>;border-radius:14px;padding:24px 16px;text-align:center;color:#fff;transition:transform .2s,box-shadow .2s;box-shadow:0 4px 15px rgba(0,0,0,.15);"
                 onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.2)'"
                 onmouseout="this.style.transform='';this.style.boxShadow='0 4px 15px rgba(0,0,0,.15)'">
              <i class="<?php echo $p['icon']; ?>" style="font-size:2rem;opacity:.9;margin-bottom:12px;display:block;"></i>
              <strong style="font-size:13px;"><?php echo $p['title']; ?></strong>
              <p style="font-size:11px;opacity:.75;margin:6px 0 0;"><?php echo $p['desc']; ?></p>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</main>

<?php require_once 'includes/footer.php'; ?>
