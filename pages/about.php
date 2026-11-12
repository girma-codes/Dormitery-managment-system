<?php
$pageTitle = 'About';
require_once '../includes/config.php';
?>
<?php require_once '../includes/header.php'; ?>
<div style="background:linear-gradient(135deg,var(--primary-dk),var(--primary));padding:50px 0;text-align:center;color:#fff;">
  <h2 style="font-weight:800;"><i class="fas fa-university mr-3"></i>About the System</h2>
</div>
<main class="py-5">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4">
        <h3 style="font-weight:800;color:var(--primary);">University of Gondar</h3>
        <p style="color:var(--muted);">University of Gondar, until 2003 known as the Gondar College of Medical Sciences, is the oldest medical school in Ethiopia. Established as the Public Health College in 1954, it is located in Gondar, the former capital of Ethiopia.</p>
        <p style="color:var(--muted);">As of 2016, the University offers 56 undergraduate and 64 postgraduate programs organized under multiple colleges and faculties.</p>
        <div class="row mt-4">
          <div class="col-4 text-center"><div style="font-size:2rem;font-weight:800;color:var(--primary);">56+</div><div style="font-size:12px;color:var(--muted);">Undergraduate Programs</div></div>
          <div class="col-4 text-center"><div style="font-size:2rem;font-weight:800;color:var(--primary);">64+</div><div style="font-size:12px;color:var(--muted);">Postgraduate Programs</div></div>
          <div class="col-4 text-center"><div style="font-size:2rem;font-weight:800;color:var(--primary);">1954</div><div style="font-size:12px;color:var(--muted);">Year Founded</div></div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card" style="background:linear-gradient(135deg,var(--primary),#2a5ba8);color:#fff;">
          <div class="card-body p-4">
            <h5 style="color:var(--accent-lt);font-weight:700;">About the DMS</h5>
            <p>This Web-Based Dormitory Management System was developed by students of the Department of Information Science, Faculty of Informatics for Atse Tewodros Campus.</p>
            <p>The system addresses the challenges of the existing manual dormitory management system including data redundancy, misallocation of dorms, and inaccessibility of information.</p>
            <hr style="border-color:rgba(255,255,255,.2);">
            <strong>Developed by:</strong>
            <ul style="margin-top:8px;">
              <li>Almaz Birhane</li><li>Aynalem Berhanu</li><li>Bezawit Tadesse</li>
              <li>Habtamu Tesfaye</li><li>Meron Yizelkal</li><li>Tsion Endale</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="section-heading"><h2>Key Benefits</h2><div class="divider"></div></div>
    <div class="row">
      <?php $benefits = [
        ['fas fa-bolt','Fast Access','Students can view their dorm info online instantly from anywhere.'],
        ['fas fa-shield-alt','Secure','Role-based access ensures data security and privacy.'],
        ['fas fa-clock','Time Saving','Reduces the time for allocation from 15 days to 3 days.'],
        ['fas fa-coins','Cost Effective','Reduces the number of proctors needed, saving significant costs.'],
        ['fas fa-database','Centralized Data','All dormitory data stored in a single secure database.'],
        ['fas fa-sync','Real-time Updates','News and events are posted and viewable in real time.'],
      ];
      foreach ($benefits as $b): ?>
      <div class="col-md-4 mb-4">
        <div class="feature-box">
          <div class="feat-icon"><i class="<?php echo $b[0]; ?>"></i></div>
          <h5><?php echo $b[1]; ?></h5>
          <p><?php echo $b[2]; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>
<?php require_once '../includes/footer.php'; ?>
