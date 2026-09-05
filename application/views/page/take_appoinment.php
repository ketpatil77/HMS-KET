<?php
  $departments = get_department();
?>

<div class="app-page-header front-hero">
  <div>
    <div class="eyebrow">Booking</div>
    <h1 class="page-headline">Confirm Appointment</h1>
    <p class="page-subtitle">Pick a schedule, set a date, send the note. Clean flow, same backend.</p>
  </div>
</div>

<div class="app-grid-2 booking-layout">
  <?php if (!isset($doctors[0])): ?>
    <div class="x_panel app-card">
      <div class="x_content">No Doctors Found.</div>
    </div>
  <?php else: ?>
    <?php $doctor = $doctors[0]; ?>
    <div class="doctor-card doctor-card--booking">
      <div class="doctor-card-head">
        <img src="<?php echo rs_media_url($doctor->picture, $doctor->name); ?>" class="doctor-portrait" alt="<?php echo $doctor->name; ?>">
        <div>
          <div class="eyebrow">Doctor Information</div>
          <h3 class="name"><?php echo $doctor->name; ?></h3>
          <div class="badge badge-primary"><?php echo get_department_name($doctor->department); ?></div>
        </div>
      </div>
      <ul class="doctor-meta">
        <li><span>Country</span><strong><?php echo get_country($doctor->country); ?></strong></li>
        <li><span>Email</span><strong><?php echo $doctor->email; ?></strong></li>
      </ul>
    </div>

    <div class="booking-form-shell">
      <?php if(isset($message) && !empty($message)): ?>
        <div class="alert <?php echo ($type == 'error' ? 'alert-danger' : 'alert-success'); ?>">
          <?php echo $message; ?>
        </div>
      <?php endif; ?>

      <?php if(validation_errors()){ echo '<div class="validations_error">'.validation_errors().'</div>'; } ?>

      <?php echo form_open('', array('class' => 'app-form-grid booking-form')); ?>
        <div class="x_panel app-card booking-panel">
          <div class="x_title"><h2>Schedule</h2></div>
          <div class="x_content booking-panel-content">
            <div class="schedule-grid">
              <?php if(!empty($schedule)): foreach ($schedule as $valueSchedule): ?>
                <label class="schedule-card rs_single_schedule">
                  <input type="radio" name="schedule" value="<?php echo $valueSchedule->id; ?>">
                  <span class="schedule-day"><?php echo $valueSchedule->day_of_week; ?></span>
                  <span class="schedule-meta"><strong>Fee</strong><span><?php echo $valueSchedule->fees; ?></span></span>
                  <span class="schedule-meta"><strong>Time</strong><span><?php echo $valueSchedule->start_time.' to '.$valueSchedule->end_time; ?></span></span>
                  <span class="schedule-meta"><strong>Limit</strong><span><?php echo $valueSchedule->max_num_of_patients; ?></span></span>
                  <span class="btn btn-ghost btn_schedule_select_btn">Select</span>
                </label>
              <?php endforeach; endif; ?>
            </div>
          </div>
        </div>

        <div class="x_panel app-card booking-panel">
          <div class="x_title"><h2>Details</h2></div>
          <div class="x_content booking-panel-content">
            <div class="form-group">
              <label for="appt-details">Comments</label>
              <textarea id="appt-details" name="details" placeholder="Comments" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
              <label for="appt-date">Select Date</label>
              <input id="appt-date" class="form-control datepicker" name="date" type="text">
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
    </div>
  <?php endif; ?>
</div>
