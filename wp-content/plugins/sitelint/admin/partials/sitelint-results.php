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

$pluginName = 'SiteLint';
$pluginUrl = plugins_url('', __DIR__);
?>

<header class="site-header">
  <div class="row">

    <div class="col-6">
      <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/sitelint-logo.svg" alt="<?php echo esc_attr($pluginName) ?> logo" />
    </div>

    <div class="col-6 d-flex align-items-center justify-content-end site-header__user">
      <img src="<?php echo esc_url($pluginUrl) ?>/assets/images/avatar-grey.png" alt="" class="me-2" />
      <span class="d-none d-sm-block"><?php echo isset($options['email']) ? esc_html($options['email']) : '' ?></span>
      <button type="button" id="logOutBtn" class="btn btn-sm btn-primary btn-center ms-2">
        <?php echo __('Log out', 'sitelint') ?>
      </button>
    </div>

  </div>
</header>

<main class="site-report">
  <div class="row mb-5">

    <div class="col-md-6">

      <div class="row mb-5">
        <div class="col-md-12">

          <div class="box-secondary">

            <h1 class="visually-hidden"><?php echo __('Setup has been completed', 'sitelint') ?></h1>

            <h2><?php echo __('Your Workspace', 'sitelint') ?></h2>

            <div class="row mb-4">
              <div class="col-md-9">
                <div class="site-report__workspace-item mb-2"><small><?php echo __('Selected Workspace', 'sitelint') ?>:</small></div>
                <?php echo esc_html(sitelint_get_workspace_name($options['workspace'], $options['workspaces'])) ?>
              </div>
              <div class="col-md-3 d-flex align-items-center">
                <button class="btn btn-sm btn-secondary ms-2" id="clearWorkspace" type="button">Change</button>
              </div>
            </div>

            <div class="row">
              <div class="col-md-9">
                <div class="site-report__workspace-item mb-2"><small><?php echo __('Selected API token', 'sitelint') ?>:</small></div>
                <pre class="ws-break-line"><?php echo esc_html($options['apiToken']) ?></pre>
              </div>
              <div class="col-md-3 d-flex align-items-center">
                <button class="btn btn-sm btn-secondary ms-2" id="clearToken" type="button">Change</button>
              </div>
            </div>

          </div>

        </div>
      </div>

      <div class="row">
        <div class="col-md-12">

          <div class="box-secondary">
            <h2><?php echo __('Options', 'sitelint') ?></h2>

            <?php
            if (isset($options['email'])) {
            ?>

              <div class="d-flex align-items-center mb-3">
                <input type="checkbox" value="" id="allowSiteLint" <?php echo $options['active'] ? ' checked' : '' ?> />
                <label for="allowSiteLint" class="ms-2"><?php echo __('Send audit reports to SiteLint', 'sitelint') ?></label>
              </div>

            <?php
            }
            ?>

            <div class="d-flex align-items-center mb-4">
              <input type="checkbox" value="" id="addSiteLintLogo" <?php echo $options['addLogo'] ? ' checked' : '' ?> />
              <label for="addSiteLintLogo" class="ms-2"><?php echo __('Add SiteLint logo to the page footer', 'sitelint') ?></label>
            </div>

          </div>

        </div>
      </div>

    </div>

    <div class="col-md-6">

      <?php if (isset($options['audits']['summary'])) { ?>

        <div class="box-secondary">

          <div class="row mb-5">
            <div class="col-md-12">

              <h2><?php echo __('Audit results', 'sitelint'); ?></h2>

              <div class="row audit-score-horizontal audit-score-<?php echo esc_attr(sitelint_get_score_class($options['audits']['score'])); ?> mb-5">
                <div class="col-md-6 audit-score-horizontal__progress">
                  <progress max="100" value="<?php echo esc_attr($options['audits']['score']) ?>" aria-label="Page score" aria-describedby="scale_<?php echo esc_attr(sitelint_get_score_class($options['audits']['score'])); ?>" aria-valuenow="<?php echo esc_attr($options['audits']['score']) ?>" aria-valuemax="100"></progress>
                </div>
                <div aria-hidden="true" class="col-md-6 score-title">
                  <p><strong>Page score</strong><em><?php echo esc_html($options['audits']['score']) ?> %</em></p>
                </div>
              </div>

              <?php foreach ($options['audits']['summary']['byStandard'] as $title => $standard) {
                echo '<div class="row audit-score-horizontal audit-score-' .
                  esc_attr(sitelint_get_score_class($standard['score'])) .
                  '">' .
                  '<div class="col-md-6 audit-score-horizontal__progress"><progress max="100" value="' .
                  esc_attr($standard['score']) .
                  '" aria-label="' .
                  esc_attr(ucfirst($title)) .
                  '" aria-describedby="scale_' .
                  esc_attr(sitelint_get_score_class($standard['score'])) .
                  '" aria-valuenow="' .
                  esc_attr($standard['score']) .
                  '" aria-valuemax="100"></progress></div>' .
                  '<div aria-hidden="true" class="col-md-6 score-title"><p><strong>' .
                  esc_html(ucfirst($title)) .
                  '</strong><em>' .
                  esc_html($standard['score']) .
                  ' %</em></p></div>' .
                  '</div>';

                if ($title === 'accessibility') {
                  echo '<div class="row audit-score-vertical mb-5 mt-5">';

                  foreach ($options['audits']['summary']['byStandardLevel'] as $titleLevel => $standardLevel) {
                    echo '<div class="col-md-3 audit-score-vertical__progress audit-score-vertical__progress-' .
                      esc_attr(sitelint_get_score_class($standardLevel['score'])) .
                      '">' .
                      '<h3>' .
                      esc_html(sitelint_get_accessibility_level_title($titleLevel)) .
                      '</h3><progress max="100" value="' .
                      esc_attr($standardLevel['score']) .
                      '" aria-label="Level ' .
                      esc_attr($titleLevel) .
                      '" aria-describedby="scale_' .
                      esc_attr(sitelint_get_score_class($standardLevel['score'])) .
                      '"' .
                      'aria-valuenow="' .
                      esc_attr($standardLevel['score']) .
                      '" aria-valuemax="100"></progress><span>' .
                      esc_html($standardLevel['score']) .
                      '%</span>' .
                      '</div>';
                  }

                  echo '</div>';
                }
              } ?>

            </div>
          </div>

          <div class="row mb-5">
            <div class="col-md-12">
              <div class="site-report__score-scale">
                <h3 class="visually-hidden">Score scale</h3>
                <ul>
                  <li><span id="scale_fail" class="status status-fail"><em class="visually-hidden">Score result:
                      </em>Fail</span>
                    0-49 </li>
                  <li><span id="scale_average" class="status status-average"><em class="visually-hidden">Score result:
                      </em>Average</span> 50-89 </li>
                  <li><span id="scale_passed" class="status status-pass"><em class="visually-hidden">Score result:
                      </em>Passed</span> 90-100 </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
              <a href="https://platform.sitelint.com/login" rel="noopender" target="_blank" class="me-2"><?php echo __('Go to SiteLint Platform', 'sitelint') ?></a> <?php echo __('to see all issues details', 'sitelint') ?>
            </div>
          </div>

        </div>

      <?php } else { ?>

        <div class="box-secondary">
          <div class="row">
            <div class="col-md-12">

              <div class="alert alert-info">
                <?php echo __('No information about your site received yet', 'sitelint') ?>
              </div>

            </div>
          </div>
        </div>

      <?php } ?>

    </div>

  </div>

</main>