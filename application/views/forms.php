<?php
$title = isset($title) ? $title : '&nbsp;';
?>

<div class="app-page-header">
  <div>
    <div class="eyebrow">Form</div>
    <h1 class="page-headline"><?php echo $title; ?></h1>
    <p class="page-subtitle"><?php echo isset($subtitle) ? $subtitle : 'Same PHP logic. Cleaner shell. Faster reading.'; ?></p>
  </div>
  <?php if(isset($back_url)): ?>
    <a href="<?php echo $back_url; ?>" class="btn btn-ghost">Back</a>
  <?php endif; ?>
</div>

<?php if(isset($message) && !empty($message)): ?>
  <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<div class="x_panel app-card app-form-shell">
  <div class="x_content">
    <?php
      echo form_open(false, array(
        'class' => 'form-horizontal form-label-left app-form-grid',
        'method' => 'post',
      ));

      if(validation_errors()){
        echo '<div class="validations_error app-form-errors">';
        echo validation_errors();
        echo '</div>';
      }

      echo '<div class="app-grid-2">';
      foreach ($inputs as $key => $value) {
        $tempArg = array();
        if(isset($value['label'])) $tempArg['label'] = $value['label'];
        if(isset($value['id'])) $tempArg['id'] = $value['id'];
        if(isset($value['group_class'])) $tempArg['group_class'] = $value['group_class'];
        $tempArg['media'] = isset($value['media']) ? $value['media'] : false;

        $functionName = isset($value['fn']) ? $value['fn'] : 'form_input';
        rs_form_group($tempArg, $functionName($value['fn_arg']));
      }
      echo '</div>';
    ?>
      <div class="form-actions">
        <button type="reset" class="btn btn-ghost reset_form">Reset</button>
        <button type="submit" class="btn btn-primary"><?php echo (isset($submitTitle) ? $submitTitle : 'Submit'); ?></button>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>
