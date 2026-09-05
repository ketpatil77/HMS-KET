<div class="app-page-header">
  <div>
    <div class="eyebrow">Indian Medical Billing</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
  </div>
  <div class="search-form">
    <?php echo form_open(base_url().'/invoice', array('method' => 'GET', 'class' => 'input-group')); ?>
      <input type="text" name="s" class="form-control" placeholder="Bill ID">
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">Search</button>
      </span>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="table-wrap x_panel">
  <div class="x_title">
    <h2>All Medical Bills</h2>
  </div>
  <div class="x_content">
    <table class="table">
      <thead>
        <tr>
          <th>Bill ID</th>
          <th>Medical Bill</th>
          <th>Date</th>
          <th>Total (INR)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if(isset($all_invoice)): foreach ($all_invoice as $value): ?>
          <tr>
            <td class="table-meta"><?php echo $value->id; ?></td>
            <td><strong><?php echo $value->title; ?></strong></td>
            <td class="table-meta"><?php echo $value->date; ?></td>
            <td><span class="badge badge-primary">&#8377; <?php echo number_format((float)$value->total, 2); ?></span></td>
            <td>
              <div class="table-actions">
                <a target="blank" href="<?php echo base_url(); ?>invoice/print/<?php echo $value->id; ?>" class="btn btn-ghost btn-xs">Print</a>
                <a href="<?php echo base_url(); ?>invoice/delete/<?php echo $value->id; ?>" class="btn btn-danger btn-xs delete_confirm">Delete</a>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
