<?php

namespace Drupal\analytics\Controller; 

use Drupal\Core\Controller\ControllerBase;

//Defines Controller Class
class AnalyticsController extends ControllerBase {

    public function graphs() {

        $currentUID = \Drupal::currentUser()->id();    // current user's ID
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


        /**
         * Both $events and $generalEvents are arrays of nodes
         * Would do a foreach through all to get the specific info
         */
        // Uncomment the two below lines if you want / need to see the structure of the nodes
        // dump($events);
        // dump($generalEvents);

        $totalGen = 0;
        $totalScanned = 0;

        // Note: VSCODE may show red squiggly line under "->get". Just ignore it bc it's a php thing apparently, it works so don't worry about it
        foreach($events as $e) {
            $genPasses = $e->get('field_generated_passes')->getValue();
            $genPasses = $genPasses[0]["value"];
            $totalGen = $totalGen + $genPasses;


            $scannedPasses = $e->get('field_scanned_passes')->getValue();
            $scannedPasses = $scannedPasses[0]["value"];
            $totalScanned = $totalScanned + $scannedPasses;
        }
        
        
        
        /**
         * FINISH THIS BELOW
         */
        // foreach($generalEvents as $genEvent) {
            //     // do the samething for the above for regular events. (General Events has the same 'field_generated_passes' and 'field_scanned_passes' fields)
            
            // }
            
            
        /**
         * TODO:
         * Do something with the number of generated and scanned passes
         */






         

        /**
         * Leaving this here just in case, but don't think we'd need it
         */
        //$value = field_extract_value('node', $node, 'field_just_a_value');
        //$entity->get('field_name')->getValue();
      
        // $result = $query
        //     ->entityCondition('entity_type', 'node')
        //     ->propertyCondition('uid', $currentUID)
        //     ->execute();

        // // Get the definitions
        // $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'my_custom_content');

        // Load a node for which you want to get the field values
        // $my_node = \Drupal\node\Entity\Node::load($my_nid);

        // // Iterate through the definitions
        // foreach (array_keys($definitions) as $field_name) {
        //     // Get the values for your node
        //     // Use getValue() if you want to get an array instead of text.
        //     $values[$field_name] = $my_node->get($field_name)->value;
        // }


        // $values = \Drupal\Core\Entity\ContentEntityBase::get('field_generated_passes');


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
