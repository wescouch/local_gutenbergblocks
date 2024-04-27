<?php
/**
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://sitelint.com
 * @since      1.0.0
 *
 * @package    SiteLint
 * @subpackage SiteLint/admin/partials
 */
?>
<div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
  <h2 class="visually-hidden"><?php echo __('Create an account', 'sitelint') ?></h2>

  <div class="main-form box-tab-content">

    <form action="" method="post" id="signUpForm" class="mb-5">
      <div class="alerts">
        <?php if (isset($message)) { ?>
        <div class="alert alert-danger">
          <?php echo esc_html($message) ?>
        </div>
        <?php } ?>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="frm-signUp-form-displayName" class="form-label" data-required="(required)"><?php echo __(
              'Display name:',
              'sitelint'
          ) ?></label>
          <input type="text" class="form-control" name="displayName" id="frm-signUp-form-displayName"
            data-form-displayName required value="<?php echo isset($displayName) ? esc_html($displayName) : '' ?>" title="<?php echo __(
          'A Display Name is how you want to be known to the other users. It can be different from your real name. It is often consisted of a first name and potentially the last name.',
          'sitelint'
      ) ?>" />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="frm-signUp-form-email" class="form-label"
            data-required="(required)"><?php echo __('Email:', 'sitelint') ?></label>
          <input type="email" class="form-control" name="email" id="frm-signUp-form-email" data-form-login
            required value="<?php echo isset($email) ? esc_html($email) : '' ?>" />
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label for="frm-signUp-form-password" class="form-label"
            data-required="(required)"><?php echo __('Password:', 'sitelint') ?></label>
          <input type="password" class="form-control" name="password" data-form-password autocomplete="off"
            id="frm-signUp-form-password" required />
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-end">
        <input type="hidden" name="_action" value="signup" />
        <button type="submit" class="btn btn-primary"><?php echo __('Create an account', 'sitelint') ?></button>
      </div>
    </form>

    <p class="text-center mb-0"><?php echo __(
        'By signing up, you agree with <a href="https://platform.sitelint.com/pages/terms-of-use" target="_blank">Terms</a>, <a href="https://platform.sitelint.com/pages/privacy-policy" target="_blank">Privacy Policy</a> and <a href="https://platform.sitelint.com/pages/cookie-policy" target="_blank">Cookie Policy</a>',
        'sitelint'
    ) ?></p>
  </div>

</div>
