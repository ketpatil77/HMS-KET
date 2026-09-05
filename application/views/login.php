      <div class="login-shell">
        <div class="login-card">
          <a class="brand-mark login-brand" href="<?php echo base_url(); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M10 3h4v5h5v4h-5v5h-4v-5H5V8h5z"/>
            </svg>
            <span>HMS</span>
          </a>
          <h1>Login</h1>
          <p>Clean local access. JSON-backed data. No SQL server required.</p>

          <?php echo form_open(); ?>
            <?php
              if (isset($message)) {
                echo '<div class="validations_error">' . $message . '</div>';
              }
              if (validation_errors()) {
                echo '<div class="validations_error">' . validation_errors() . '</div>';
              }
            ?>

            <div class="form-group">
              <label for="login-user">Username</label>
              <?php
                echo form_input(array(
                  'name' => 'u_name',
                  'value' => set_value('u_name'),
                  'class' => 'form-control',
                  'placeholder' => 'Username',
                  'id' => 'login-user',
                  'autocomplete' => 'username',
                ));
              ?>
            </div>

            <div class="form-group">
              <label for="login-pass">Password</label>
              <?php
                echo form_password(array(
                  'name' => 'u_pass',
                  'value' => set_value('u_pass'),
                  'class' => 'form-control',
                  'placeholder' => 'Password',
                  'id' => 'login-pass',
                  'autocomplete' => 'current-password',
                ));
              ?>
            </div>

            <button class="login-submit" type="submit">Log in</button>
          <?php echo form_close(); ?>
        </div>
      </div>
