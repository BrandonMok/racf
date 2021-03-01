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
    // $params = $request->getParameters;
    // $query = \Drupal::request()->getQueryString();


    $event = \Drupal::request()->query->get('event');
    $uid = \Drupal::request()->query->get('uid');

    // $event = $request->query->get('event');
    // $uid = $request->query->get('uid');

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
