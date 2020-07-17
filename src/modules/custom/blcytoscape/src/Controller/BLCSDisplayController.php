<?php
/**
 * @file
 * Contains \Drupal\blcytoscape\Controller\BLCSDisplayController
 */
namespace Drupal\blcytoscape\Controller;

use Drupal\Core\Controller\ControllerBase;

class BLCSDisplayController extends ControllerBase {
    public function content() {
        $build = array(
            '#type' => 'markup',
            '#markup' => '<div id="cy">' . $this->t('This is my BLCSDisplayController') . '</div>',
        );
        $build['#attached']['library'][] = 'blcytoscape/cytoscapelib';
        $build['#attached']['library'][] = 'blcytoscape/blcytoscape';
        return $build;
    }
}