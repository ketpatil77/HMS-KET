<?php if(validation_errors()): ?>
  <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
<?php endif; ?>
<?php if(isset($message) && !empty($message)): ?>
  <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="app-page-header">
  <div>
    <div class="eyebrow">Indian Medical Billing</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
    <p class="page-subtitle">Create an Indian medical bill with INR amounts and clean clinical line items.</p>
  </div>
</div>

<div class="x_panel app-card">
  <div class="x_content">
    <form method="post" class="form-horizontal form-label-left app-form-grid">
      <div class="app-grid-2">
        <div class="form-group">
          <label for="invoice_title">Bill Title <span class="required">*</span></label>
          <input name="title" id="invoice_title" value="<?php echo set_value('title'); ?>" required="required" class="form-control" type="text" placeholder="Consultation and pharmacy bill">
        </div>
        <div class="form-group">
          <label for="patient">Select Care Recipient <span class="required">*</span></label>
          <?php
            $options = array();
            if(isset($patients) && !empty($patients)){
              foreach ($patients as $key => $patientValue) {
                $options[$patientValue->id] = $patientValue->full_name;
              }
            }
            echo form_dropdown(array(
                'class' => 'form-control',
                'name' => 'patient',
                'options' => $options,
                'value' => set_value('patient'),
            ));
          ?>
        </div>
      </div>

      <div class="invoice-items app-card invoice-items-shell">
        <div class="section-title">Medical Bill Items</div>
        <div id="invoice_items">
          <div class="row">
            <div class="col-md-6">
              <input name="items_name[]" required="required" class="form-control" type="text" placeholder="Consultation, lab test, medicine">
            </div>
            <div class="col-md-6">
              <input name="items_price[]" class="form-control" type="number" min="0" step="0.01" placeholder="Amount in INR">
            </div>
          </div>
        </div>
        <div class="invoice-actions">
          <a href="javascript:void(0);" id="btn_new_item" class="btn btn-ghost">Add medical item</a>
        </div>
      </div>

      <div class="form-actions">
        <button class="btn btn-ghost" type="button">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
