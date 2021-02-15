<?php

namespace Drupal\logo_image_style;

use Drupal\Core\Render\Element\RenderCallbackInterface;

/**
 * Provides a trusted callback to alter the system branding block.
 *
 * @see logo_image_style_block_view_system_branding_block_alter()
 */
class LogoBlockViewBuilder implements RenderCallbackInterface {

  /**
   * #pre_render callback: Sets color preset logo.
   */
  public static function preRender($build) {
    $logo_style = \Drupal::config('system.site')->get('logo_image_style_logo_style');
    $logo_image = \Drupal::config('system.site')->get('logo_image_style_logo_path');
    if (!empty($logo_style) && $logo_style != '_none') {
      $original_image = $logo_image;
      $style = \Drupal::entityTypeManager()->getStorage('image_style')->load($logo_style);
      $url = $style->buildUrl('public://' . $original_image);
      // Replace the logo url with the url to the image style.
      $build['content']['site_logo']['#uri'] = $url;
    }
    return $build;
  }

}
