<?php
$pageTitle = 'Unauthorized';
require_once 'includes/config.php';
require_once 'includes/header.php';
?>
<div class="container text-center py-5">
    <i class="fas fa-ban fa-5x text-danger mb-4"></i>
    <h2 class="text-danger">Access Denied</h2>
    <p class="lead text-muted">You do not have permission to access this page.</p>
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><i class="fas fa-home"></i> Go Home</a>
</div>
<?php require_once 'includes/footer.php'; ?>
