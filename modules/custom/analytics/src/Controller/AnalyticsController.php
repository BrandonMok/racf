<?php

namespace Drupal\analytics\Controller; 

use Drupal\Core\Controller\ControllerBase;

//Defines Controller Class
class AnalyticsController extends ControllerBase {

    public function graphs() {

        $currentUID = \Drupal::currentUser()->id();             // current user's ID
        $currentUserRole = \Drupal::currentUser()->getRoles();  // array of roles
        $entityTypeManager = \Drupal::entityTypeManager();

        // Analytic data to show depends on user
        if (in_array('administrator', $currentUserRole)) {
            $events = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'event', 
            ]);

            $generalEvents = $entityTypeManager->getStorage('node')->loadByProperties([
                'type' => 'general_event', 
            ]);
        }
        else {
            // Not an admin, so get all events for the current user
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
        $attendees = [];    // array of each uid - don't want to count the same ZIP more than once if user appears again for multiple events
        $allZipcodes = [];  // zipcodes of attendees

        if (!empty($events)) {
            $etm = \Drupal::entityTypeManager();

            foreach($events as $e) {
                $genPasses = $e->get('field_generated_passes')->getValue();
                $genPasses = $genPasses[0]["value"];
                $totalGen = $totalGen + $genPasses;
    
                $scannedPasses = $e->get('field_scanned_passes')->getValue();
                $scannedPasses = $scannedPasses[0]["value"];
                $totalScanned = $totalScanned + $scannedPasses;

                $attendeeListField = $e->get('field_attendees')->getValue();
                $attendeeList = $attendeeListField[0]['value']; // list field of user IDs of users that generated pass to attend
                $attendeeArr = explode("\r\n", $attendeeList);

                foreach($attendeeArr as $id) {
                    if ($id !== "") {
                        $userResult = $etm->getStorage('user')->loadByProperties([
                            'uid' => intval($id)
                        ]);
                        $user = array_pop($userResult);

                        // check to make sure not to add an attendee more than once in the attendees array
                        if (!in_array($id, $attendees)) {
                            array_push($attendees, $id);    // add this uid of user to attendees array

                            $address = $user->get('field_address')->getValue();
                            $zipcode = $address[0]["postal_code"];

                            array_push($allZipcodes, $zipcode); // add this zipcode to allZipCodes array for further analytics
                        }
                    }
                }
            }
        }
        
        
        /**
         * FINISH THIS BELOW
         */
        // if (!empty($generalEvents)) {
        //     foreach($generalEvents as $genEvent) {
        //         // do the samething for the above for regular events. (General Events has the same 'field_generated_passes' and 'field_scanned_passes' fields)
        //     }
        // }




        /**
         * TODO:
         * Do something with the number of generated and scanned passes
         * ALSO - figure out / tally the number each zipcode appears in the allZipcodes array for the chart
         *  - could be however you think of to do it or could be a simple foreach with tallys 
         */


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
                            'test_array' => [
                                5, 10, 20
                            ],
                            'test_array2' => [
                                1,2,3,4,5
                            ],
                        ],
                    ],
                ],
            ],
        );

    }
}
