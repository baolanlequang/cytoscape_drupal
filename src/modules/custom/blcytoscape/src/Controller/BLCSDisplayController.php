<?php
/**
 * @file
 * Contains \Drupal\blcytoscape\Controller\BLCSDisplayController
 */
namespace Drupal\blcytoscape\Controller;

use Drupal\Core\Controller\ControllerBase;

class BLCSDisplayController extends ControllerBase {
    public function content() {
        return array(
            '#type' => 'markup',
            '#markup' => t('This is my BLCSDisplayController'),
        );
    }
}