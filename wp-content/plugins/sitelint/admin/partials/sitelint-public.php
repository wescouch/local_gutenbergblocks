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
<header class="mb-5">
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/sitelint-logo.svg" alt="<?php echo esc_html($pluginName) ?> logo" />
    </div>
  </div>
</header>

<main class="main" id="connect">

  <div class="row mb-8">

    <div class="col-md-3 offset-md-1">

      <ul class="nav nav-tabs" id="sitelintTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link <?php echo (isset($_POST['_action']) && sanitize_text_field($_POST['_action']) != 'register') || empty($_POST['_action']) ? ' active' : ''; ?>" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button"
            role="tab" aria-controls="login" aria-selected="true"><?php echo __('Log in', 'sitelint') ?></button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link <?php echo isset($_POST['_action']) === true && sanitize_text_field($_POST['_action']) == 'register' ? ' active' : '' ?>" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button"
            role="tab" aria-controls="signup"
            aria-selected="false"><?php echo __('Create an account', 'sitelint') ?></button>
        </li>
      </ul>

      <div class="tab-content">

        <?php include_once('sitelint-login.php'); ?>
        <?php include_once('sitelint-signup.php'); ?>

      </div>

    </div>

    <div class="col-md-6 offset-md-1">
      <div class="box-image-primary align-items-start flex-column">
        <p><?php echo __(
            'Get access to our <a href="https://platform.sitelint.com/" target="_blank" aria-describedby="opens-an-external-site-in-new-window">SiteLint Audits Platform</a> and perform active real-time automated audits: Accessibility, SEO, Performance, Security, Privacy, Technical issues. No random scanning. Just pages and reports used by your users!',
            'sitelint'
        ) ?></p>
        <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/sitelint-screenshot-results.png" alt="<?php echo __('SiteLint screenshot results', 'sitelint') ?>" class="mw-100p h-100 mt-2" />
      </div>
    </div>

  </div>

  <div class="row mb-5 content-visibility-auto">

    <div class="col-md-3 offset-md-1 mb-4">
      <div class="box-secondary">
        <h2><?php echo __('Support', 'sitelint') ?></h2>

        <p class="mb-4"><?php echo __(
            'Have a question? Concern? Request? We\'d love to hear from you. Connect with us through the following ways.',
            'sitelint'
        ) ?></p>

        <ul class="list-unstyled">
          <li class="mb-3"><span class="fw-bold">Email</span>: <a
              href="mailto:support@sitelint.com">support@sitelint.com</a></li>
          <li class="mb-3"><span class="fw-bold">Social</span>: <a href="https://twitter.com/SiteLint"
              aria-describedby="opens-an-external-site">Twitter</a></li>
          <li><span class="fw-bold">Answers Forum</span>: Share solutions and get help from the others on <a
              href="https://platform.sitelint.com/answers-forum" aria-describedby="opens-an-external-site">Answers
              Forum</a>.</li>
        </ul>

      </div>
    </div>

    <div class="col-md-3 offset-md-1 mb-4">
      <div class="box-secondary">
        <h2><?php echo __('Support in Development', 'sitelint') ?></h2>
        <p>
          <?php echo __('Have you found issues, but not sure how to fix them? Want to include SiteLint into your CI/CD pipeline?', 'sitelint') ?>
        </p>
        <p><?php echo __(
            'We are truly integrated part of your team. <a href="https://www.sitelint.com/contact/" target="_blank">Get in touch</a> so we can help you.',
            'sitelint'
        ) ?></p>
      </div>
    </div>

    <div class="col-md-3 mb-4">
      <div class="box-secondary">
        <h2><?php echo __('UX / UI Support', 'sitelint') ?></h2>
        <p><?php echo __(
            'Applying UX/UI rules that includes Accessibility can be done before software development starts. Follow our UX / UI checklist and get support on how to implement solutions.',
            'sitelint'
        ) ?></p>
      </div>
    </div>

  </div>

</main>
