<?php

namespace Drupal\analytics\Controller; 

use Drupal\Core\Controller\ControllerBase;

//Defines Controller Class
class AnalyticsController extends ControllerBase {
   
    public function graphs() {
        $graphs = array(
            array('type' => 'bar'),
            array('type' => 'pie')
        );

        $labels = array(
            array('set' => 'set1'),
            array('set' => 'set2'),
            array('set' => 'set3')
        );

        $values = array( 
            array('val' => 6),
            array('val' => 10),
            array('val' => 4)
        );

        return array(
            '#theme' => 'analytics_theme',
            '#title' => 'Analytics',
            '#graphs' => $graphs,
            '#labels' => $labels,
            '#values' => $values
        );

    }
}