<div class="app-page-header">
  <div>
    <div class="eyebrow">Doctors</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
  </div>
  <div class="search-form">
    <form action="" method="get" class="input-group">
      <input type="text" class="form-control" name="s" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">Search</button>
      </span>
    </form>
  </div>
</div>

<div class="table-wrap x_panel">
  <div class="x_title">
    <h2>List Of All Doctors</h2>
  </div>
  <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>Phone</th>
          <th>Name</th>
          <th>Department</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($all_doctors)): foreach ($all_doctors as $value):
          $departmentData = get_department(array('id' => $value->department));
        ?>
          <tr>
            <td class="table-meta"><?php echo $value->phone; ?></td>
            <td><strong><?php echo $value->name; ?></strong></td>
            <td><span class="badge badge-primary"><?php echo (isset($departmentData[0]->name) ? $departmentData[0]->name : ''); ?></span></td>
            <td>
              <div class="table-actions">
                <a href="<?php echo base_url('doctors/about/'.$value->id); ?>" class="btn btn-ghost btn-xs">Details</a>
                <a href="<?php echo base_url('doctors/update/'.$value->id); ?>" class="btn btn-info btn-xs">Edit</a>
                <a href="<?php echo base_url('doctors/delete/'.$value->id); ?>" class="btn btn-danger btn-xs delete_confirm">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
