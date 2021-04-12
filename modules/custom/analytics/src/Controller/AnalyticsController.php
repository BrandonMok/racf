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

        $etm = \Drupal::entityTypeManager();

        if (!empty($events)) {
            foreach($events as $e) {
                
                if ($e->hasField('field_generated_passes') && !$e->get('field_generated_passes')->isEmpty()) {
                    $genPasses = $e->get('field_generated_passes')->getValue();
                    $genPasses = $genPasses[0]["value"];
                    $totalGen = $totalGen + $genPasses;
                }
    
                if ($e->hasField('field_scanned_passes') && !$e->get('field_scanned_passes')->isEmpty()) {
                    $scannedPasses = $e->get('field_scanned_passes')->getValue();
                    $scannedPasses = $scannedPasses[0]["value"];
                    $totalScanned = $totalScanned + $scannedPasses;
                }

                if ($e->hasField('field_attendees') && !$e->get('field_attendees')->isEmpty()) {
                    $attendeeListField = $e->get('field_attendees')->getValue();
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
                                if ($user->hasField('field_address') && !$user->get('field_address')->isEmpty()) {
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
        $generalEventAttendees = [];
        $allGeneralZipcodes = [];

        if (!empty($generalEvents)) {
            foreach ($generalEvents as $ge) {
                if ($ge->hasField('field_generated_passes') && !$ge->get('field_generated_passes')->isEmpty()) {
                    $genPassesB = $ge->get('field_generated_passes')->getValue();
                    $genPassesB = $genPassesB[0]["value"];
                    $totalGenB = $totalGenB + $genPassesB;
                }

                if ($ge->hasField('field_scanned_passes') && !$ge->get('field_scanned_passes')->isEmpty()) {
                    $scannedPassesB = $ge->get('field_scanned_passes')->getValue();
                    $scannedPassesB = $scannedPassesB[0]["value"];
                    $totalScannedB = $totalScannedB + $scannedPassesB;
                }

                if ($ge->hasField('field_attendees') && !$ge->get('field_attendees')->isEmpty()) {
                    $generalAttendeeListField = $ge->get('field_attendees')->getValue();
                    $generalAttendeeList = $generalAttendeeListField[0]['value'];
                    $generalAttendeeArr = explode("\r\n", $generalAttendeeList);

                    foreach($generalAttendeeArr as $uid) {
                        if (!empty($uid)) {
                            $userRes = $etm->getStorage('user')->loadByProperties([
                                'uid' => intval($uid),
                            ]);
                            $user = array_pop($userRes);

                            if (!is_null($user) && !in_array($uid, $generalEventAttendees)) {
                                array_push($generalEventAttendees, $uid);

                                // SNAP users only have a zipcode.
                                if ($user->hasField('field_address') && !$user->get('field_address')->isEmpty()) {
                                    $address = $user->get('field_address')->getValue();
                                    $zipcode = $address[0]['postal_code'];
                                    array_push($allGeneralZipcodes, $zipcode);
                                }
                            }
                        }
                    }
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
                                $totalGenB, $totalScannedB
                            ],
                            'attendees' => $attendees,
                            'general_attendees' => $generalEventAttendees,
                            'zip_codes' => $allZipcodes,
                            'zip_codes_general' => $allGeneralZipcodes, 
                        ]
                    ],
                ],
            ],
            '#cache' => [
                'max-age' => 0
            ]
        );
    }
}
