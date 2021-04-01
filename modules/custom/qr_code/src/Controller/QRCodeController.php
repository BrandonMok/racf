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
  public function checkin($eventid, $uid) {

    // Get User's snap to show on template
    $entityTypeManager = \Drupal::entityTypeManager();
    $userStorage = $entityTypeManager->getStorage('user');
    $userResult = $userStorage->loadByProperties([
      'uid' => $uid
    ]);

    $currUsr = array_pop($userResult);
    if (isset($currUsr) && !empty($currUsr)) {

      if ($currUsr->hasRole('Authenticated User')) {
        $snap = $currUsr->get('field_snap_number')->getString();
      }

      // Get the event and its date to show on template
      $events = $entityTypeManager->getStorage('node')->loadByProperties([
        'type' => 'event', 
        'nid' => $eventid
      ]);
    
      // if not empty, then it's an event
      // if it is empty, then it's a general event
      if (!empty($events)) { 
        $theEvent = array_pop($events);
        $eventTitle = $theEvent->getTitle();
        
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

        // Event Time 
        $fullTime = $theEvent->get('field_time')->getString();
        $start = substr($fullTime, 0, 5);
        $end = substr($fullTime, 7, strlen($fullTime));
        $startTimeDate = gmdate('h:m A', $start);
        $endTimeDate = gmdate('h:m A', $end);
        $eventTime = "$startTimeDate - $endTimeDate";

        // UPDATE passes scanned field
        $scannedPasses = $theEvent->get('field_scanned_passes')->getString();
        $incremented = intval($scannedPasses) + 1;

        $theEvent = $theEvent->set('field_scanned_passes', strval($incremented));
        $theEvent = $theEvent->save();
      }
      else {
        // not an event - is a General Event
        $formattedDate = "";

        $generalEvents = $entityTypeManager->getStorage('node')->loadByProperties([
          'type' => 'general_event', 
          'nid' => $eventid
        ]);
        $theEvent = array_pop($generalEvents);

        // CHECK: if event exists
        if (isset($theEvent) && !empty($theEvent)) {
          $eventTitle = $theEvent->getTitle();

          $scannedPasses = $theEvent->get('field_scanned_passes')->getString();
          $incremented = intval($scannedPasses) + 1;
  
          $theEvent = $theEvent->set('field_scanned_passes', strval($incremented));
          $theEvent = $theEvent->save();
        }
        else {
          // ERROR - Event not found from the ID in the pass.
          $returnArr = $this->errorScan();
        }
      }
    }
    else {
      // ERROR - User not found from the ID in the pass.
      $returnArr = $this->errorScan();
    }

    // RETURN arr to templates
    if (!isset($returnArr)) {
      // Good array
      return [
        '#theme' => 'qr_scan_pass',
        '#event' => $eventTitle ?? '',
        '#event_date' => $formattedDate,
        '#event_time' => $eventTime ?? '',
        '#snap' => $snap ?? '',
        '#attached' => [
          'library' => [
            'qr_code/assets',
          ],
        ],
      ];
    }
    else {
      return $returnArr;
    }
  }


  /**
   * errorScan
   * @return Array
   */
  public function errorScan() {
    return [
      '#theme' => 'qr_scan_pass_error',
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
   * passGenerated
   * @return NULL
   * Used for updating the number of passes generated and adding user to attendee list
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

      // Also add the user to the event's attendee list
      $attendeeList = $thisEvent->get('field_attendees')->getString();
      $currentUID = \Drupal::currentUser()->id();

      if ( str_contains($attendeeList, $currentUID . "\r\n") === false ) {
        // Not on the list, so add them to the list
        $attendeeList = $attendeeList . "$currentUID\r\n";
        $thisEvent = $thisEvent->set('field_attendees', $attendeeList);
      }

      $thisEvent = $thisEvent->save();  // save changes
    }

    return [
      NULL
    ];
  }
}
