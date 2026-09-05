<?php
	$jsonAr = json_decode($_REQUEST["data"],true);
?>
<form id="rs_form_prescription" data-json="<?php echo htmlspecialchars($_REQUEST['data'], ENT_QUOTES, 'UTF-8'); ?>">
  <div class="form-group">
  	<textarea class="form-control" id="prescription" placeholder="Details" rows="15"><?php echo $jsonAr['prescription']; ?></textarea>
  </div>
  <button type="submit" id="form_add_prescription" class="btn btn-primary">Save</button>
</form>
