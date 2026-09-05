<div class="app-page-header">
  <div>
    <div class="eyebrow">Appointments</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
  </div>
</div>

<?php echo form_open('', array('class' => 'form-horizontal app-card')); ?>
  <div class="x_content">
    <div class="app-grid-2">
      <div class="form-group">
        <label for="schedule-date">Select Date</label>
        <input id="schedule-date" class="form-control datepicker" name="date" type="text">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </div>
<?php echo form_close(); ?>

<?php if(isset($appoinments) && !empty($appoinments)): ?>
  <div class="table-wrap x_panel appointment-table">
    <div class="x_title">
      <h2>Appointments List</h2>
    </div>
    <div class="x_content">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Patients name</th>
            <th>Serial No</th>
            <th>Date</th>
            <th>Fees</th>
            <th>Details</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($appoinments as $appoinmentKay => $appoinment):
            $patient = get_users(array('id' => $appoinment->patient_id));
            $schedule = get_schedule(array('id' => $appoinment->schedule_id));
            $jsonData = array();
            $jsonData['apionment_id'] = $appoinment->id;
            $jsonData['prescription'] = $appoinment->prescription;
          ?>
            <tr>
              <td class="table-meta"><?php echo $appoinmentKay + 1; ?></td>
              <td><strong><?php echo (isset($patient[0]->full_name) ? $patient[0]->full_name : ''); ?></strong></td>
              <td class="table-meta"><?php echo $appoinment->serial_no; ?></td>
              <td class="table-meta"><?php echo $appoinment->date; ?></td>
              <td><span class="badge badge-primary"><?php echo (isset($schedule[0]->fees) ? $schedule[0]->fees : ''); ?></span></td>
              <td class="table-meta"><?php echo $appoinment->details; ?></td>
              <td>
                <div class="table-actions">
                  <a href="" data-title="Add Prescription" data-json="<?php echo htmlspecialchars(json_encode($jsonData), ENT_QUOTES, 'UTF-8'); ?>" data-url="Doctors/Addprescription" class="btn btn-danger btn-xs dialog_open">Add</a>
                  <a href="<?php echo base_url().'/doctors/appoinments/'.$doctorId.'/'.$patient[0]->id; ?>" class="btn btn-ghost btn-xs">List</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
