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

    /** 
     * Do logic to grab current user's snap and the event name
     */
    $currentUID = \Drupal::currentUser()->id();

    $entityTypeManager = \Drupal::entityTypeManager();
    $userStorage = $entityTypeManager->getStorage('user');

    $user = $userStorage->loadByProperties([
      'uid' => $currentUID
    ]);

    return [
      '#markup' => $this->t("Hello World!"),
    ];
  }

}
