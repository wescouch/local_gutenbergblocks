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
<div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
  <h2 class="visually-hidden"><?php echo __('Log in', 'sitelint') ?></h2>

  <div class="main-form box-tab-content">

    <form action="" method="post" id="logInForm" class="mb-5">
      <div class="alerts">
        <?php if (isset($message)) { ?>
        <div class="alert alert-danger">
          <?php echo esc_html($message) ?>
        </div>
        <?php } ?>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="frm-logIn-form-email" class="form-label"
            data-required="(required)"><?php echo __('Email:', 'sitelint') ?></label>
          <input type="email" class="form-control" name="email" id="frm-logIn-form-email" data-form-login
            required value="<?php echo isset($email) ? esc_html($email) : '' ?>" />
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label for="frm-logInForm-form-password" class="form-label"
            data-required="(required)"><?php echo __('Password:', 'sitelint') ?></label>
          <input type="password" class="form-control" name="password" data-form-password autocomplete="off"
            id="frm-logInForm-form-password" required />
        </div>
      </div>

      <div class="d-flex align-items-center justify-content-between">
        <a href="https://platform.sitelint.com/account-recovery" target="_blank"><small><?php echo __(
            'I forgot my password',
            'sitelint'
        ) ?></small></a>
        <input type="hidden" name="_action" value="login" />
        <button type="submit" class="btn btn-primary"><?php echo __('Log in', 'sitelint') ?></button>
      </div>
    </form>

    <p class="text-center mb-0"><?php echo __(
        'By signing up, you agree with <a href="https://platform.sitelint.com/pages/terms-of-use" target="_blank">Terms</a>, <a href="https://platform.sitelint.com/pages/privacy-policy" target="_blank">Privacy Policy</a> and <a href="https://platform.sitelint.com/pages/cookie-policy" target="_blank">Cookie Policy</a>',
        'sitelint'
    ) ?></p>
  </div>
</div>
