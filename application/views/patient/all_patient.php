<div class="app-page-header">
  <div>
    <div class="eyebrow">Patients</div>
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
    <h2>List Of All Patient</h2>
  </div>
  <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>Descriptions</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($patients as $value): ?>
          <tr>
            <td><strong><?php echo $value->name; ?></strong></td>
            <td class="table-meta"><?php echo $value->phone; ?></td>
            <td class="table-meta"><?php echo $value->descriptions; ?></td>
            <td>
              <div class="table-actions">
                <a href="<?php echo base_url(); ?>patient/about/<?php echo $value->id; ?>" class="btn btn-ghost btn-xs">Details</a>
                <a href="<?php echo base_url(); ?>patient/update/<?php echo $value->id; ?>" class="btn btn-info btn-xs">Edit</a>
                <a href="<?php echo base_url(); ?>patient/delete/<?php echo $value->id; ?>" class="btn btn-danger btn-xs delete_confirm">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
