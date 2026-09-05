<?php if($appoinments): ?>
  <div class="doctor-grid doctor-grid--list">
    <?php foreach ($appoinments as $key => $appoinment):
      $doctor = get_doctors(array('id' => $appoinment->doctor_id));
      $schedule = get_schedule(array('id' => $appoinment->schedule_id));
      if(!isset($doctor[0]) || !isset($schedule[0])) continue;
      $doctor = $doctor[0];
      $schedule = $schedule[0];
    ?>
      <div class="appointment-card">
        <div class="appointment-card-head">
          <img class="appointment-avatar" src="<?php echo rs_media_url($doctor->picture, $doctor->name); ?>" alt="<?php echo $doctor->name; ?>">
          <div>
            <div class="eyebrow">Appointment #<?php echo $appoinment->id; ?></div>
            <h3 class="name"><?php echo $doctor->name; ?></h3>
            <div class="small-muted"><?php echo get_department_name($doctor->department); ?> · <?php echo $doctor->email; ?></div>
          </div>
          <div class="badge badge-success"><?php echo $appoinment->status; ?></div>
        </div>
        <div class="appointment-grid">
          <div><span>Date</span><strong><?php echo $appoinment->date; ?></strong></div>
          <div><span>Time</span><strong><?php echo $schedule->start_time.' - '.$schedule->end_time; ?></strong></div>
          <div><span>Serial</span><strong><?php echo $appoinment->serial_no; ?></strong></div>
          <div><span>Note</span><strong><?php echo $appoinment->details; ?></strong></div>
        </div>
        <div class="form-actions">
          <a href="<?php echo base_url().'page/appoinmentsDelete/'.$appoinment->id; ?>" class="btn btn-danger delete_confirm">Delete</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="alert alert-danger">No Appointment found</div>
<?php endif; ?>
