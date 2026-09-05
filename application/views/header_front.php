<?php
if (!isset($sidebar) || (isset($sidebar) && $sidebar)) {
    $sidebar = true;
} else {
    $sidebar = false;
}
$show_admin_panel_link = is_admin_user();
$theme = 'dark';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">

    <title><?php echo (isset($title) ? $title : 'Hospital Management System'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <script>
      (function() {
        try {
          var theme = localStorage.getItem('hms-theme');
          if (theme === 'dark' || theme === 'light') {
            document.documentElement.setAttribute('data-theme', theme);
          }
        } catch (e) {}
      })();
    </script>
  </head>
  <body class="app-body front-shell <?php echo (isset($body_class) ? $body_class : ''); ?>">
    <div class="front-topbar">
      <a class="brand-mark" href="<?php echo base_url(); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M10 3h4v5h5v4h-5v5h-4v-5H5V8h5z"/>
        </svg>
        <span>HMS</span>
      </a>
      <nav class="front-nav">
        <a href="<?php echo base_url('page/doctors'); ?>">Doctors</a>
        <?php if(is_login()): ?>
          <a href="<?php echo base_url('page/appoinments'); ?>">Appointments</a>
          <a href="<?php echo base_url('page/profile'); ?>">Profile</a>
          <a href="<?php echo base_url('user/logout'); ?>">Logout</a>
        <?php else: ?>
          <a href="<?php echo base_url('login'); ?>">Login</a>
          <a href="<?php echo base_url('page/register'); ?>">Register</a>
        <?php endif; ?>
      </nav>
      <div class="front-nav-actions">
        <?php if ($show_admin_panel_link): ?>
          <a class="btn btn-ghost front-admin-link" href="<?php echo base_url('dashboard'); ?>">Admin Panel</a>
        <?php endif; ?>
        <button class="icon-button" type="button" data-theme-toggle aria-label="Toggle theme"></button>
      </div>
    </div>
    <main class="app-main">
      <div class="app-page">
