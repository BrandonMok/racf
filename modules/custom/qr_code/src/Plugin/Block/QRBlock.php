<?php

namespace Drupal\qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use GuzzleHttp\Exception\RequestException;

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
    $nodeType = $currNode->bundle();

    $currentUID = \Drupal::currentUser()->id();


    if ($nodeType === "event" && $currentUID != 0) {
      $title = $currNode->getTitle();

      $urlEncoded = urlencode("https://dev-racf.pantheonsite.io/checkin&event=$title&uid=$currentUID");
      $markup = "http://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$urlEncoded";
    }
    else { 
      $markup = $this->t("QR code unavailable. Please login to access this pass!");
    }

    return [
      '#theme' => 'qr_themeable_block',
      '#content' => $markup,
      '#title' => "",
    ];
    
  }
}
