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

<div class="main-content" id="content">
  <?php
  if (isset($options['email']) && $options['email']) {

    if ($options['apiToken']) {
      include_once 'sitelint-results.php';
    } else {
      include_once 'sitelint-setup.php';
    }

  } else {
    include_once 'sitelint-public.php';
  }

  include_once 'sitelint-footer.php';
  ?>
</div>
