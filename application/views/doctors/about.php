<div class="app-page-header">
  <div>
    <div class="eyebrow">Doctor profile</div>
    <h1 class="page-headline"><?php echo $doctor[0]->name; ?></h1>
    <p class="page-subtitle"><?php echo $doctor[0]->about; ?></p>
  </div>
  <a class="btn btn-primary" href="<?php echo base_url('doctors/createSchedule/'.$doctor[0]->id); ?>">Create Schedule</a>
</div>

<div class="app-grid-2">
  <div class="doctor-card">
    <div class="doctor-card-head">
      <img src="<?php echo rs_media_url($doctor[0]->picture, $doctor[0]->name); ?>" alt="" class="doctor-avatar">
      <div>
        <div class="eyebrow">Profile</div>
        <h3 class="name"><?php echo $doctor[0]->name; ?></h3>
        <div class="badge badge-primary"><?php echo get_department(array('id' => $doctor[0]->department))[0]->name; ?></div>
      </div>
    </div>
    <ul class="doctor-meta">
      <li><span>Mobile</span><strong><?php echo $doctor[0]->phone; ?></strong></li>
      <li><span>Email</span><strong><?php echo $doctor[0]->email; ?></strong></li>
      <li><span>Country</span><strong><?php echo get_country($doctor[0]->country); ?></strong></li>
    </ul>
  </div>

  <div class="x_panel app-card">
    <div class="x_title"><h2>About</h2></div>
    <div class="x_content">
      <?php echo $doctor[0]->about; ?>
    </div>
  </div>
</div>

<div class="table-wrap x_panel">
  <div class="x_title">
    <h2>All Schedule</h2>
  </div>
  <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Day</th>
          <th>Time</th>
          <th>Maximum Patients</th>
          <th>Fees</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($allSchedule)): foreach ($allSchedule as $key => $value): ?>
          <tr>
            <td class="table-meta"><?php echo ($key + 1); ?></td>
            <td><strong><?php echo $value->day_of_week; ?></strong><div class="table-meta"><?php echo $value->comment; ?></div></td>
            <td class="table-meta"><?php echo $value->start_time.' To '.$value->end_time; ?></td>
            <td class="table-meta"><?php echo $value->max_num_of_patients; ?></td>
            <td><span class="badge badge-primary"><?php echo $value->fees; ?></span></td>
            <td>
              <a href="<?php echo base_url('doctors/deleteSchedule/'.$doctor[0]->id.'/'.$value->id); ?>" class="btn btn-danger btn-xs delete_confirm">Delete</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
