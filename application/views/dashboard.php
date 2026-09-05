<?php
  $quickLinks = array(
    array('label' => 'Doctors', 'href' => base_url('doctors'), 'meta' => 'Manage roster'),
    array('label' => 'Appointments', 'href' => base_url('page/appoinments'), 'meta' => 'Check bookings'),
    array('label' => 'Invoice', 'href' => base_url('invoice'), 'meta' => 'Review billing'),
    array('label' => 'Departments', 'href' => base_url('department'), 'meta' => 'Edit structure'),
    array('label' => 'Settings', 'href' => base_url('settings'), 'meta' => 'Control system'),
  );
?>

<div class="dashboard-shell">
  <div class="app-page-header dashboard-hero">
    <div>
      <div class="eyebrow">Dashboard</div>
      <h1 class="page-headline">Hospital control room</h1>
      <p class="page-subtitle">Compact view of appointments, beds, staff, and billing. Same data, cleaner frame.</p>
    </div>
  </div>

  <div class="dashboard-cards" data-stagger>
    <?php foreach ($stats as $stat): ?>
      <div class="stat-card">
        <div class="stat-label"><?php echo $stat['label']; ?></div>
        <div class="stat-value" data-count-to="<?php echo (int)$stat['value']; ?>"><?php echo (int)$stat['value']; ?></div>
        <div class="stat-footer">
          <span class="badge badge-<?php echo $stat['tone']; ?>"><?php echo $stat['trend']; ?></span>
          <svg class="sparkline" viewBox="0 0 120 34" fill="none" stroke="currentColor" stroke-width="2" opacity="0.5" aria-hidden="true">
            <path d="M2 26 L20 18 L38 22 L56 10 L74 14 L92 7 L118 12" />
          </svg>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="dashboard-lower dashboard-layout">
    <div class="dashboard-main-column">
      <div class="x_panel app-card dashboard-panel">
        <div class="x_title">
          <h2>Quick Actions</h2>
          <span class="small-muted">Direct shortcuts for daily work</span>
        </div>
        <div class="x_content dashboard-panel-content">
          <div class="quick-actions-grid dashboard-actions-grid">
            <?php foreach ($quickLinks as $link): ?>
              <a class="quick-action-card app-card" href="<?php echo $link['href']; ?>">
                <span class="dashboard-action-title"><?php echo $link['label']; ?></span>
                <span class="dashboard-action-meta"><?php echo $link['meta']; ?></span>
                <span class="badge badge-primary">Open</span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="dashboard-side-column">
      <div class="x_panel app-card dashboard-panel">
        <div class="x_title">
          <h2>Recent Activity</h2>
          <span class="small-muted"><?php echo count($recent_activity); ?> latest items</span>
        </div>
        <div class="x_content dashboard-panel-content">
          <div class="timeline dashboard-timeline">
            <?php if (empty($recent_activity)): ?>
              <div class="small-muted">No appointments yet.</div>
            <?php else: ?>
              <?php foreach ($recent_activity as $item): ?>
                <div class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div>
                    <div><strong>Appointment #<?php echo htmlspecialchars($item->id); ?></strong></div>
                    <div class="timeline-meta"><?php echo htmlspecialchars($item->date); ?>, status <?php echo htmlspecialchars($item->status); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
