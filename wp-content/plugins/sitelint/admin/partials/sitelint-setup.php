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

<header class="site-header">
  <div class="row">
    <div class="col-6">
      <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/sitelint-logo.svg" alt="<?php echo esc_attr($pluginName) ?> logo" />
    </div>
    <div class="col-6 d-flex align-items-center justify-content-end site-header__user">
      <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/avatar-grey.png" alt="" class="me-2"/>
      <span class="d-none d-sm-block"><?php echo isset($options['email']) ? esc_html($options['email']) : '' ?></span>
      <button type="button" id="logOutBtn" class="btn btn-sm btn-primary btn-center ms-2">
        <?php echo __('Log out', 'sitelint') ?>
      </button>
    </div>
  </div>
</header>

<main>
  <div class="row">
    <div class="col-md-5">
      <h1 class="visually-hidden">
        <?php echo __('Setup token first', 'sitelint') ?>
      </h1>
      <p class="main-all-set__text">
        <?php echo __('Select workspace and API token to start sending reports.', 'sitelint') ?>
      </p>
      <form action="" method="post" id="apiTokenForm" class="form-horizontal js-api-token-form">
        <?php if (empty($options['workspace'])) { ?>
        <div>
          <label for="workspace" class="d-block form-label">Workspace</label>
          <select name="workspace" id="workspace" data-form-workspace>
            <option value="null" selected disabled>Select workspace</option>
            <?php foreach ($options['workspaces'] as $workspace) {
                if ($workspace['_id'] === $options['workspace']) {
                    echo '<option value=' . esc_attr($workspace['_id']) . ' selected>' . esc_html($workspace['name']) . '</option>';
                } else {
                    echo '<option value=' . esc_attr($workspace['_id']) . '>' . esc_html($workspace['name']) . '</option>';
                }
            } ?>
          </select>
          <button class="btn btn--sm btn-primary btn-center" type="submit"><?php echo __('Select', 'sitelint'); ?></button>
        </div>
        <?php } else { ?>

          <div class="row mb-4">
            <div class="col-md-9">
              <div class="site-report__workspace-item mb-2"><small><?php echo __('Selected Workspace', 'sitelint') ?>:</small></div>
              <?php echo esc_html(sitelint_get_workspace_name($options['workspace'], $options['workspaces'])) ?>
            </div>
            <div class="col-md-3 d-flex align-items-center">
              <button class="btn btn-sm btn-secondary" id="clearWorkspace"
                type="button"><?php echo __('Change', 'sitelint'); ?></button>
            </div>
          </div>

        <?php }

        if ($options['workspace'] && empty($options['apiToken'])) { ?>

          <div class="row">
            <div class="col-md-9">
              <label for="apiToken" class="d-block form-label">API token</label>
              <select name="apiToken" id="apiToken" data-form-api-token>
                <option value="null" selected disabled>Select api token</option>
                <?php foreach ($options['apiTokens'] as $token) {
                    if ($token['tokenId'] . '-' . $token['prefix'] === $options['apiToken']) {
                        echo '<option value=' . esc_attr($token['tokenId']) . '-' . esc_attr($token['prefix']) . ' selected>' . esc_html($token['name']) . '</option>';
                    } else {
                        echo '<option value=' . esc_attr($token['tokenId']) . '-' . esc_attr($token['prefix']) . '>' . esc_html($token['name']) . '</option>';
                    }
                } ?>
              </select>
            </div>
            <div class="col-md-3 d-flex align-items-center">
              <button class="btn btn-sm btn-secondary ms-2" type="submit">Select</button>
            </div>
          </div>

        <?php } ?>
      </form>
    </div>
  </div>
</main>
