<?php
/**
 * SiteLint plugin helpers
 *
 * @since 1.0
 *
 * @return string
 */
function sitelint_get_publish_cap()
{
    return apply_filters('sitelint_publish_cap', 'publish_posts');
}

function sitelint_get_score_class($score)
{
    $cssClass = 'fail';

    if ($score >= 90) {
        $cssClass = 'passed';
    } elseif ($score < 90 && $score >= 50) {
        $cssClass = 'average';
    }

    return $cssClass;
}

function sitelint_get_workspace_name($workspace_id, $workspaces)
{
    foreach ($workspaces as $workspace) {
        if ($workspace['_id'] == $workspace_id) {
            return $workspace['name'];
        }
    }
}

function sitelint_get_accessibility_level_title($titleLevel)
{
    switch ($titleLevel) {
        case 'A':
            return __('A', 'sitelint');
        case 'AA':
            return __('AA', 'sitelint');
        case 'AAA':
          return __('AAA', 'sitelint');
        case 'best_practices':
          return __('Best Practices', 'sitelint');
        default:
          break;
      }
}
