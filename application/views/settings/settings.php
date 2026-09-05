<div class="app-page-header">
  <div>
    <div class="eyebrow">Settings</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
    <p class="page-subtitle">Section switching stays server-side. No bootstrap tab JS needed.</p>
  </div>
</div>

<div class="app-grid-2">
  <div class="x_panel app-card">
    <div class="x_title">
      <h2>Sections</h2>
    </div>
    <div class="x_content">
      <div class="doctor-meta">
        <?php foreach($tab as $tabkey => $tab_item): ?>
          <div>
            <span>Section</span>
            <strong><a href="<?php echo base_url(); ?>settings/options/<?php echo $tabkey; ?>"><?php echo $tab_item['title']; ?></a></strong>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="x_panel app-card">
    <div class="x_title">
      <h2>Active Section</h2>
    </div>
    <div class="x_content">
      <?php
        $active_tab = (isset($tab_active) && isset($tab[$tab_active])) ? $tab_active : 'basic_tab';
        $view_path = __DIR__ . DIRECTORY_SEPARATOR . $active_tab . '.php';
        if (is_file($view_path)) {
          include $view_path;
        } else {
          echo '<div class="alert alert-danger">Settings section file missing.</div>';
        }
      ?>
    </div>
  </div>
</div>
