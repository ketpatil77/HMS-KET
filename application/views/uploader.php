<div class="app-page-header">
  <div>
    <div class="eyebrow">Media</div>
    <h1 class="page-headline">Uploader</h1>
    <p class="page-subtitle">Drag a file, post it, get back a URL. No plugin stack needed.</p>
  </div>
</div>

<div class="x_panel app-card">
  <div class="x_content">
    <?php echo form_open_multipart('ajax_query/do_upload', array('id' => 'upload', 'class' => 'app-form-grid')); ?>
      <div id="drop" class="schedule-card upload-drop">
        <strong>Drop Here</strong>
        <span class="small-muted">or click browse</span>
        <button type="button" class="btn btn-ghost" data-upload-browse>Browse</button>
        <input type="file" name="upl" hidden />
      </div>
      <ul class="upload-list" aria-live="polite"></ul>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="x_panel app-card">
  <div class="x_title">
    <h2>From Library</h2>
  </div>
  <div class="x_content">
    <div class="doctor-grid">
      <a href="#" data-id="" class="schedule-card">
        <strong>Name</strong>
        <span class="small-muted">Placeholder media card</span>
      </a>
      <a href="#" data-id="" class="schedule-card">
        <strong>Name</strong>
        <span class="small-muted">Placeholder media card</span>
      </a>
    </div>
  </div>
</div>
