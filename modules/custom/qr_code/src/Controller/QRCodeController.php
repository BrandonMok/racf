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

  
    // if not empty, then it's an event
    // if it is empty, then it's a general event
    if (!empty($events)) { 
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

      $formattedDate = "$start - $to";
    }
    else {
      // not an event - is a General Event
      $formattedDate = "";
    }


    // if ($today >= $start && $today <= $to){
    //   $formattedDate = "$start - $to  (TODAY)";
    // }
    // else {
      // $formattedDate = "$start - $to";
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


  /**
   * Used for updating the number of passes generated
   */
  public function passGenerated($eventTitle) {
    $etm = \Drupal::entityTypeManager();

    $allEvents = $etm->getStorage('node')->loadByProperties([
      'title' => $eventTitle
    ]);

    // If event is found, then update its generated passes field
    if (!empty($allEvents)) {
      $thisEvent = array_pop($allEvents);
  
      $genPasses = $thisEvent->get('field_generated_passes')->getString();
      $incremented = intval($genPasses) + 1;
  
      $thisEvent = $thisEvent->set('field_generated_passes', strval($incremented));
      $thisEvent = $thisEvent->save();
    }


    return [
      NULL
    ];
  }
}
