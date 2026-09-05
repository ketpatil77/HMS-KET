<?php
  $departments = get_department();
?>

<div class="app-page-header front-hero">
  <div>
    <div class="eyebrow">Directory</div>
    <h1 class="page-headline">Doctors</h1>
    <p class="page-subtitle">Search specialists, filter by department, and open booking in one clean pass.</p>
  </div>
</div>

<div class="x_panel app-card">
  <div class="x_content">
    <?php echo form_open('', array('method' => 'get', 'class' => 'app-inline-filter search-form')); ?>
      <select class="form-control" name="department" onchange="this.form.submit();">
        <option value="">All departments</option>
        <?php foreach ($departments as $department): ?>
          <option value="<?php echo $department->id; ?>" <?php echo (isset($_GET['department']) && $_GET['department'] == $department->id) ? 'selected' : ''; ?>>
            <?php echo $department->name; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="input-group">
        <input type="text" class="form-control" name="s" placeholder="Search doctors">
        <span class="input-group-btn">
          <button class="btn btn-primary" type="submit">Search</button>
        </span>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="doctor-grid" data-stagger>
  <?php if (empty($doctors)): ?>
    <div class="x_panel app-card">
      <div class="x_content">No Doctors Found.</div>
    </div>
  <?php else: ?>
    <?php foreach ($doctors as $doctor): ?>
      <div class="doctor-card doctor-card--directory">
        <div class="doctor-card-head">
          <img class="doctor-avatar" src="<?php echo rs_media_url($doctor->picture, $doctor->name); ?>" alt="<?php echo $doctor->name; ?>">
          <div>
            <div class="eyebrow">Doctor</div>
            <h3 class="name"><?php echo $doctor->name; ?></h3>
            <div class="badge badge-primary"><?php echo get_department_name($doctor->department); ?></div>
          </div>
        </div>
        <ul class="doctor-meta">
          <li><span>Country</span><strong><?php echo get_country($doctor->country); ?></strong></li>
          <li><span>Email</span><strong title="<?php echo $doctor->email; ?>"><?php echo $doctor->email; ?></strong></li>
        </ul>
        <a href="<?php echo base_url('page/TakeAppoinment/'.$doctor->id); ?>" class="btn btn-primary btn-block">Take Appointment</a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
