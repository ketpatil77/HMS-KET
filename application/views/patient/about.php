<div class="app-page-header">
  <div>
    <div class="eyebrow">Patient</div>
    <h1 class="page-headline"><?php echo $patient[0]->name; ?></h1>
    <p class="page-subtitle"><?php echo $patient[0]->descriptions; ?></p>
  </div>
</div>

<div class="app-grid-2">
  <div class="x_panel app-card">
    <div class="x_title"><h2>Patient Info</h2></div>
    <div class="x_content">
      <div class="info-grid">
        <?php
          $fields = array(
              'name' => 'Patient Name',
              'phone' => 'Phone',
              'blood_group' => 'Blood Group',
              'department' => 'Department',
              'birth_date' => 'Date Of Birth',
              'age' => 'Age',
              'sex' => 'Gender',
              'email' => 'Email',
              'county' => 'Country',
              'city' => 'City',
              'address' => 'Address',
            );
          foreach ($fields as $key => $value):
        ?>
          <div>
            <span><?php echo $value; ?></span>
            <strong>
              <?php
                if ($key == 'sex') {
                  echo ($patient[0]->$key == 0 ? 'Male' : 'Female');
                } elseif ($key == 'department') {
                  echo get_department(array('id' => $patient[0]->$key))[0]->name;
                } elseif ($key == 'county') {
                  echo get_country($patient[0]->$key);
                } else {
                  echo $patient[0]->$key;
                }
              ?>
            </strong>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="x_panel app-card">
    <div class="x_title"><h2>Guardian</h2></div>
    <div class="x_content">
      <div class="info-grid">
        <?php
          $fields = array(
              'guardian_name' => 'Gurdian Name',
              'guardian_phone' => 'Gurdian Phone',
              'guardian_details' => 'Gurdian Details',
              'bad_no' => 'Patient Bed No.',
              'referred_by' => 'Referred By',
              'reg_date' => 'Admitted Date',
              'descriptions' => 'Descriptions',
            );
          foreach ($fields as $key => $value):
        ?>
          <div>
            <span><?php echo $value; ?></span>
            <strong><?php echo $patient[0]->{$key}; ?></strong>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
