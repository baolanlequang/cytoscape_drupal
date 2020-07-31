<?php
/**
 * @file
 * Contains \Drupal\blcytoscape\Controller\BLCSDisplayController
 */
namespace Drupal\blcytoscape\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;
use Drupal\Component\Serialization\Json;

class BLCSDisplayController extends ControllerBase {
    public function content() {
        $build = array(
            '#type' => 'markup',
            '#markup' => '<div class="cy">' . $this->t('This is my BLCSDisplayController') . '</div>',
        );
        $build['#attached']['library'][] = 'blcytoscape/cytoscapelib';
        $build['#attached']['library'][] = 'blcytoscape/blcytoscape';
        $build['#attached']['drupalSettings']['cytoscape']['elements'] = [
            ['data'=> ['id'=>'a']],
            ['data'=> ['id'=>'b']],
            ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        ];
        $build['#attached']['drupalSettings']['cytoscape']['style'] = [
            [
                'selector'=>'node', 
                'style'=>[
                    'background-color'=>'#666',
                    'label'=>'data(id)'
                ],
            ],
            [
                'selector'=>'edge', 
                'style'=>[
                    'width'=>3,
                    'line-color'=>'#ccc',
                    'target-arrow-color'=>'#ccc',
                    'target-arrow-shape'=>'triangle',
                    'curve-style'=>'bezier'
                ],
            ],
        ];
        $build['#attached']['drupalSettings']['cytoscape']['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];
        return $build;
    }

    public function displayAjax() {
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;

        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select->fields('blcy', ['elements', 'nid']);
        $select->condition('nid', 1);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        $result = array();
        $result['elements'] = [
            ['data'=> ['id'=>'a']],
            ['data'=> ['id'=>'b']],
            ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        ];
        // foreach ($entries as $entry) {
        //     // $data = Json::decode($entry['elements']);
        //     // $contents = utf8_encode($entry['elements']);
        //     // // $contents = '{"data":"aaa"}';
        //     // $decodedText = html_entity_decode($entry['elements']);
        //     // $data = json_decode($decodedText, true);
        //     $result['elements'] = $entry['elements'];
        // }
        $result['style'] = [
            [
                'selector'=>'node', 
                'style'=>[
                    'background-color'=>'#ff0000',
                    'label'=>'data(id)'
                ],
            ],
            [
                'selector'=>'edge', 
                'style'=>[
                    'width'=>3,
                    'line-color'=>'#ccc',
                    'target-arrow-color'=>'#ccc',
                    'target-arrow-shape'=>'triangle',
                    'curve-style'=>'bezier'
                ],
            ],
        ];
        $result['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];
        $response = new JsonResponse();
        $response->setData($result);
        return $response;
    }
}