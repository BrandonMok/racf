<?php

namespace Drupal\analytics\Controller; 

use Drupal\Core\Controller\ControllerBase;

//Defines Controller Class
class AnalyticsController extends ControllerBase {

    public function graphs() {

        $currentUID = \Drupal::currentUser()->id();             // current user's ID.
        $currentUserRole = \Drupal::currentUser()->getRoles();  // array of roles.
        $entityTypeManager = \Drupal::entityTypeManager();

        // Analytic data to show depends on user.
        if (in_array('administrator', $currentUserRole)) {
            $events = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'event', 
            ]);

            $generalEvents = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'general_event', 
            ]);
        }
        else {
            // Not an admin, so get all events for the current organizer.
            $events = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'event', 
                'uid' => $currentUID
            ]);

            $generalEvents = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'general_event', 
                'uid' => $currentUID
            ]);
        }


        // Analytic fields
        $totalGen = 0;
        $totalScanned = 0;
        $attendees = [];    // array of each uid - don't want to count the same ZIP more than once if user appears again for multiple events.
        $allZipcodes = [];  // zipcodes of attendees.

        if (!empty($events)) {
            $etm = \Drupal::entityTypeManager();

            foreach($events as $e) {
                $genPasses = $e->get('field_generated_passes')->getValue();
                if (!empty($genPasses)) {
                    $genPasses = $genPasses[0]["value"];
                    $totalGen = $totalGen + $genPasses;
                }
    
                $scannedPasses = $e->get('field_scanned_passes')->getValue();
                if (!empty($scannedPasses)) {
                    $scannedPasses = $scannedPasses[0]["value"];
                    $totalScanned = $totalScanned + $scannedPasses;
                }

                $attendeeListField = $e->get('field_attendees')->getValue();
                if (!empty($attendeeListField)) {
                    $attendeeList = $attendeeListField[0]['value'];
                    $attendeeArr = explode("\r\n", $attendeeList);

                    foreach($attendeeArr as $id) {
                        // make sure that user with this ID still exists.
                        if ($id !== "") {
                            $userResult = $etm->getStorage('user')->loadByProperties([
                                'uid' => intval($id)
                            ]);
                            $user = array_pop($userResult);
    
                            // check to make sure not to add an attendee more than once in the attendees array.
                            if (!is_null($user) && !in_array($id, $attendees)) {
                                array_push($attendees, $id);    // add this uid of user to attendees array.
    
                                // SNAP users only have a zipcode.
                                if ($user->hasField('field_address')) {
                                    $address = $user->get('field_address')->getValue();
                                    $zipcode = $address[0]["postal_code"];
                                    array_push($allZipcodes, $zipcode); // add this zipcode to allZipCodes array for later display.
                                }
                            }
                        }
                    }
                }
            }
        }
        
        /**
         * Gathers Total Scanned/Generated Events Data
         */
        $totalGenB = 0;
        $totalScannedB = 0;
        if (!empty($generalEvents)) {
            foreach($generalEvents as $genEvent) {
                $genPasses = $genEvent->get('field_generated_passes')->getValue();
                if (!empty($genPasses)) {
                    $genPasses = $genPasses[0]["value"];
                    $totalGenB = $totalGenB + $genPasses;
                }

                $scannedPasses = $genEvent->get('field_scanned_passes')->getValue();
                if (!empty($scannedPasses)) {
                    $scannedPasses = $scannedPasses[0]["value"];
                    $totalScannedB = $totalScannedB + $scannedPasses;
                }
            }
        }

        //Return Array
        return array(
            '#theme' => 'analytics_theme',
            '#title' => 'Analytics',
            '#attached' => [
                'library' => [
                    'analytics/accordion'
                ],
                'drupalSettings' => [
                    'analytics' => [
                        'graph_data' => [
                            'events_all' => [
                                $totalGen, $totalScanned
                            ],
                            'events_general' => [
                                $totalGenB ?? 0, $totalScannedB ?? 0
                            ],
                            'attendees' => $attendees,
                            'zip_codes' => $allZipcodes
                        ]
                    ],
                ],
            ],
        );

    }
}
