<?php

namespace Drupal\qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'QR' Block.
 *
 * @Block(
 *   id = "qr_code",
 *   admin_label = @Translation("QR Code block"),
 *   category = @Translation("QR Code"),
 * )
 */
class QRBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $currNode = \Drupal::routeMatch()->getParameter('node');

    if (is_null($currNode)) {
      $nodeType = "";
    }
    else {
      $nodeType = $currNode->bundle();
    }

    $currentUID = \Drupal::currentUser()->id();

    if ($nodeType === "event" && $currentUID != 0) {
      $title = $currNode->getTitle();

      // $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin&event=$title&uid=$currentUID");
      $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin/$title/$currentUID");
      $markup = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded";

      $gen = "";
    }
    else { 
      $markup = "/modules/custom/qr_code/images/x-mark.png";
      $gen = "QR Code unavailable. Please login to generate pass.";
      $title = "";
    }

    return [
      '#theme' => 'qr_themeable_block',
      '#content' => $markup,
      '#generated' => $gen,
      '#event' => $title ? $title : "",
      '#title' => "",
      '#attached' => [
        'library' => [
          'qr_code/accordion',
        ],
      ],
    ];
    
  }
}
