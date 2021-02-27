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
    $nodeType = $currNode->bundle();  // event

    // if ($nodeType === "event") {
    //   $title = $currNode->getTitle();
    // }
    // else { 
    //    // have block show something like it's not an event or not available
    // }

    $title = $currNode->getTitle();

    

    $currentUID = \Drupal::currentUser()->id();

    $entityTypeManager = \Drupal::entityTypeManager();
    $userStorage = $entityTypeManager->getStorage('user');

    $user = $userStorage->loadByProperties([
      'uid' => $currentUID
    ]);

    $currUsr = array_pop($user);
    $snap = $currUsr->get('field_snap_number')->getString();




    return [
      '#markup' => $this->t("Hello World! $snap"),
    ];
  }

}
