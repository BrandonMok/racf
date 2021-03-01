<?php

namespace Drupal\qr_code\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines QRCodeController class.
 */
class QRCodeController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function checkin(Request $request) {
    $event = urldecode($request->get('event'));
    $uid = urldecode($request->get('uid'));

    $entityTypeManager = \Drupal::entityTypeManager();
    $userStorage = $entityTypeManager->getStorage('user');

    $userResult = $userStorage->loadByProperties([
      'uid' => $uid
    ]);

    $currUsr = array_pop($userResult);
    $snap = $currUsr->get('field_snap_number')->getString(); 

    $retVal = $this->t("Event: $event\n User Snap: $snap");
  
    return [
      '#type' => 'markup',
      '#markup' => $retVal,
    ];
  }

}
