<?php
$login_user = $this->session->userdata('login_user');
$login_user = is_array($login_user) ? $login_user : array();
$login_user_name = isset($login_user['full_name']) && $login_user['full_name'] !== '' ? $login_user['full_name'] : 'User';
$login_user_picture = isset($login_user['picture']) && $login_user['picture'] !== '' ? rs_media_url($login_user['picture'], $login_user_name) : rs_media_url(rs_profile_avatar('student', $login_user_name), $login_user_name);
if (!function_exists('rs_svg_icon')) {
  function rs_svg_icon($name) {
    $icons = array(
      'cross' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 3h4v5h5v4h-5v5h-4v-5H5V8h5z"/></svg>',
      'home' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10.5V20h14v-9.5"/><path d="M9 20v-5h6v5"/></svg>',
      'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 13h6V4H4z"/><path d="M14 20h6v-9h-6z"/><path d="M14 4h6v4h-6z"/><path d="M4 20h6v-4H4z"/></svg>',
      'department' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16v12H4z"/><path d="M8 6V4"/><path d="M16 6V4"/><path d="M4 10h16"/><path d="M8 14h8"/></svg>',
      'doctor' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>',
      'nurse' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6v4h4v6h-4v8H9v-8H5V7h4z"/></svg>',
      'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>',
      'invoice' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h10v18l-2-1-2 1-2-1-2 1-2-1z"/><path d="M9 8h6"/><path d="M9 12h6"/></svg>',
      'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15a3 3 0 1 0-3-3 3 3 0 0 0 3 3z"/><path d="M19.4 15a7.97 7.97 0 0 0 .1-2 7.97 7.97 0 0 0-.1-2l2-1.4-2-3.4-2.3.8a8.1 8.1 0 0 0-1.7-1l-.3-2.4h-4l-.3 2.4a8.1 8.1 0 0 0-1.7 1L5.6 6.2l-2 3.4 2 1.4a7.97 7.97 0 0 0-.1 2 7.97 7.97 0 0 0 .1 2l-2 1.4 2 3.4 2.3-.8a8.1 8.1 0 0 0 1.7 1l.3 2.4h4l.3-2.4a8.1 8.1 0 0 0 1.7-1l2.3.8 2-3.4z"/></svg>',
      'logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 21V3"/></svg>',
      'menu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></svg>',
      'bell' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17H5l1.4-1.4A3 3 0 0 0 7.3 14V10a4.7 4.7 0 0 1 9.4 0v4c0 .5.2 1 .6 1.4L19 17h-4"/><path d="M10 17a2 2 0 0 0 4 0"/></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : $icons['menu'];
  }
}
?>
<aside class="left_col app-sidebar">
  <div class="sidebar-brand">
    <a href="<?php echo base_url('dashboard'); ?>" class="brand-mark">
      <?php echo rs_svg_icon('cross'); ?>
      <span class="brand-copy sidebar-text">
        <span>HMS</span>
        <small>Hospital Management</small>
      </span>
    </a>
  </div>

  <div class="profile">
    <img src="<?php echo $login_user_picture; ?>" alt="<?php echo $login_user_name; ?>" class="avatar">
    <div class="profile_info sidebar-text">
      <span>Signed in</span>
      <h2><?php echo $login_user_name; ?></h2>
    </div>
  </div>

  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>Main</h3>
      <ul class="nav side-menu">
        <li><a href="<?php echo base_url(); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('home'); ?></span><span class="sidebar-text">Homepage</span></a></li>
        <li><a href="<?php echo base_url('dashboard'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('dashboard'); ?></span><span class="sidebar-text">Dashboard</span></a></li>
        <li><a href="<?php echo base_url('doctors'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('doctor'); ?></span><span class="sidebar-text">Doctors</span></a></li>
        <li><a href="<?php echo base_url('page/doctors'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('department'); ?></span><span class="sidebar-text">Public Doctors</span></a></li>
        <li><a href="<?php echo base_url('page/appoinments'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('invoice'); ?></span><span class="sidebar-text">Appointments</span></a></li>
      </ul>
    </div>

    <div class="menu_section">
      <h3>Management</h3>
      <ul class="nav side-menu">
        <li><a href="<?php echo base_url('department'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('department'); ?></span><span class="sidebar-text">Departments</span></a></li>
        <li><a href="<?php echo base_url('nurse'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('nurse'); ?></span><span class="sidebar-text">Nurses</span></a></li>
        <li><a href="<?php echo base_url('user'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('user'); ?></span><span class="sidebar-text">Users</span></a></li>
        <li><a href="<?php echo base_url('invoice'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('invoice'); ?></span><span class="sidebar-text">Billing</span></a></li>
        <li><a href="<?php echo base_url('settings'); ?>"><span class="sidebar-icon"><?php echo rs_svg_icon('settings'); ?></span><span class="sidebar-text">Settings</span></a></li>
      </ul>
    </div>
  </div>

  <div class="sidebar-footer app-sidebar-footer hidden-small">
    <button class="icon-button sidebar-toggle" type="button" data-sidebar-toggle aria-label="Collapse sidebar"><?php echo rs_svg_icon('menu'); ?></button>
    <a class="icon-button" href="<?php echo base_url('user/logout'); ?>" title="Logout"><?php echo rs_svg_icon('logout'); ?></a>
  </div>
</aside>

<header class="top_nav app-topbar">
  <button class="icon-button sidebar-toggle" type="button" data-sidebar-toggle aria-label="Toggle sidebar"><?php echo rs_svg_icon('menu'); ?></button>
  <div class="search-bar topbar-search">
    <div class="input-group">
      <span class="input-group-addon">⌘K</span>
      <input type="search" class="form-control" placeholder="Search records">
    </div>
  </div>
  <div class="topbar-actions">
    <button class="icon-button" type="button" aria-label="Notifications"><?php echo rs_svg_icon('bell'); ?></button>
    <button class="icon-button" type="button" data-theme-toggle aria-label="Toggle theme"></button>
    <a href="<?php echo base_url('user/logout'); ?>" class="icon-button user-profile" title="<?php echo $login_user_name; ?>">
      <img src="<?php echo $login_user_picture; ?>" alt="<?php echo $login_user_name; ?>" class="avatar">
    </a>
  </div>
</header>
