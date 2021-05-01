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


        // Analytic fields.
        $totalGen = 0;
        $totalScanned = 0;
        $attendees = [];    // array of each uid - don't want to count the same ZIP more than once if user appears again for multiple events.
        $allZipcodes = [];  // zipcodes of attendees.

        if (!empty($events)) {
            $regularEventArr = $this->getGraphData($events);

            $totalGen = $regularEventArr['totalGen'];
            $totalScanned = $regularEventArr['totalScanned'];
            $attendees = $regularEventArr['attendees'];
            $allZipcodes = $regularEventArr['allZipCodes'];
        }

        $totalGenB = 0;
        $totalScannedB = 0;
        $generalEventAttendees = [];
        $allGeneralZipcodes = [];

        if (!empty($generalEvents)) {
            $generalEventArr = $this->getGraphData($generalEvents);

            $totalGenB = $generalEventArr['totalGen'];
            $totalScannedB = $generalEventArr['totalScanned'];
            $generalEventAttendees = $generalEventArr['attendees'];
            $allGeneralZipcodes = $generalEventArr['allZipCodes'];
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
    
    
    /**
     * getGraphData
     * @param $arr - either array of events or general events
     * @return array - array containing all the keys necesssary
     */
    public function getGraphData($arr) {
        $totalGen = 0;
        $totalScanned = 0;
        $attendees = [];    // array of each uid - don't want to count the same ZIP more than once if user appears again for multiple events.
        $allZipcodes = [];  // zipcodes of attendees.

        $etm = \Drupal::entityTypeManager();
        
        if (!empty($arr)) {
            foreach($arr as $e) {
                
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
                        // Make sure that user with this ID still exists. - as in the account wasn't deleted.
                        if ($id !== "") {
                            $userResult = $etm->getStorage('user')->loadByProperties([
                                'uid' => intval($id)
                            ]);
                            $user = array_pop($userResult);
                            
                            // Check to make sure not to add an attendee more than once in the attendees array.
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
        
        return [
            "totalGen" => $totalGen,
            "totalScanned" => $totalScanned,
            "attendees" => $attendees,
            "allZipCodes" => $allZipcodes
        ];
    }
}