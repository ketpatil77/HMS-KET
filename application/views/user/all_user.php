<div class="app-page-header">
  <div>
    <div class="eyebrow">Users</div>
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
    <h2>List Of All Users</h2>
  </div>
  <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>Photo</th>
          <th>About</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(isset($all_user) && !empty($all_user)): foreach ($all_user as $value): ?>
          <tr>
            <td><img class="avatar" src="<?php echo rs_media_url($value->picture, $value->full_name); ?>" alt=""></td>
            <td>
              <div class="table-meta"><strong><?php echo $value->full_name; ?></strong></div>
              <div class="table-meta"><?php echo $value->email; ?></div>
              <div class="badge badge-primary user-role"><?php echo $value->role; ?></div>
            </td>
            <td>
              <div class="table-actions">
                <a href="<?php echo base_url(); ?>user/update/<?php echo $value->id; ?>" class="btn btn-info btn-xs">Edit</a>
                <a href="<?php echo base_url(); ?>user/delete/<?php echo $value->id; ?>" class="btn btn-danger btn-xs delete_confirm">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
