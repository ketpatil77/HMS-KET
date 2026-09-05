<?php
if (!isset($sidebar) || (isset($sidebar) && $sidebar)) {
    $sidebar = true;
} else {
    $sidebar = false;
}
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
    <link rel="stylesheet" href="/assets/css/style.css?v=20260905-1">
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
  <body class="app-body <?php echo (isset($body_class) ? $body_class : ''); ?> <?php echo ($sidebar ? 'nav-md' : ''); ?>">
    <div class="container body app-shell">
      <div class="main_container">
        <?php if ($sidebar) require_once('sidebar.php'); ?>
        <div class="right_col app-main" role="main">
          <div class="app-page">
