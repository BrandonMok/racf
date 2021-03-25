<?php

namespace Drupal\analytics\Controller; 

use Drupal\Core\Controller\ControllerBase;

//Defines Controller Class
class AnalyticsController extends ControllerBase {

    public function graphs() {

        $currentUID = \Drupal::currentUser()->id();    // current user's ID

        //$value = field_extract_value('node', $node, 'field_just_a_value');
        //$entity->get('field_name')->getValue();

        $events = $entityTypeManager->getStorage('node')->loadByProperties([
            'type' => 'event', 
            'author' => $currentUID
        ]);
        dump($events)


        $result = $query
            ->entityCondition('entity_type', 'node')
            ->propertyCondition('uid', $currentUID)
            ->execute();

        // Get the definitions
        $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'my_custom_content');

        // Load a node for which you want to get the field values
        $my_node = \Drupal\node\Entity\Node::load($my_nid);

        // Iterate through the definitions
        foreach (array_keys($definitions) as $field_name) {
            // Get the values for your node
            // Use getValue() if you want to get an array instead of text.
            $values[$field_name] = $my_node->get($field_name)->value;
        }

       
        // If you want this current user obect
        $entityTypeManager = \Drupal::entityTypeManager();
        $userStorage = $entityTypeManager->getStorage('user');

        //$userResult = $userStorage->loadByProperties([
        //    'uid' => $uid
        //]);
        //$user = array_pop($userResult);

        $events = $entityTypeManager->getStorage('node')->loadByProperties([
            'type' => 'event', 
            //'title' => $event
        ]);

        $values = \Drupal\Core\Entity\ContentEntityBase::get('field_generated_passes');(edited)


        return array(
            '#theme' => 'analytics_theme',
            '#title' => 'Analytics',
            '#attached' => [
                'library' => [
                    'analytics/accordion'
                ],
            ],
        );

    }
}
