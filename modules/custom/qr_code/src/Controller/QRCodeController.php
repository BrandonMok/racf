<?php

namespace Drupal\qr_code\Controller;

use Drupal\Core\Controller\ControllerBase;

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
  public function checkin($event, $uid) {

    // Get User's snap to show on template
    $entityTypeManager = \Drupal::entityTypeManager();
    $userStorage = $entityTypeManager->getStorage('user');

    $userResult = $userStorage->loadByProperties([
      'uid' => $uid
    ]);

    $currUsr = array_pop($userResult);
    $snap = $currUsr->get('field_snap_number')->getString();


    // Get the event and its date to show on template
    $events = $entityTypeManager->getStorage('node')->loadByProperties([
      'type' => 'event', 
      'title' => $event
    ]);
    $theEvent = array_pop($events);

    $eventDate = $theEvent->get('field_date')->getString(); // full date range of this event

    // Format start date from Y-d-m to d/m/Y
    $start = new \DateTime(substr($eventDate, 0, 9));
    $start = $start->format('m/d/Y');

    // Format end date from Y-d-m to m/d/Y
    $to = new \DateTime(substr($eventDate, 12, strlen($eventDate)));
    $to = $to->format('m/d/Y');

    // Get today's date
    $today = new \DateTime('now');
    $today = $today->format('m/d/Y');

    // if ($today >= $start && $today <= $to){
    //   $formattedDate = "$start - $to  (TODAY)";
    // }
    // else {
      $formattedDate = "$start - $to";
    // }

    return [
      '#theme' => 'qr_scan_pass',
      '#event' => $event,
      '#event_date' => $formattedDate,
      '#snap' => $snap,
      '#attached' => [
        'library' => [
          'qr_code/assets',
        ],
      ],
    ];
  }



/**
 * Display the markup.
 * Used for admin and organizer CheckIn menu link when there's no params
 * 
 * @return array
 *   Return markup array
 */
  public function checkinMenuLink() {
    return [
      '#theme' => 'checkin_page',
      '#attached' => [
        'library' => [
          'qr_code/assets',
        ],
      ],
    ];
  }




}
