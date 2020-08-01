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
use Drupal\node\NodeInterface;

class BLCSDisplayController extends ControllerBase {
    public function content() {

        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;

        $build = array(
            '#type' => 'markup',
            // '#markup' => '<div class="cy">' . $this->t('This is my BLCSDisplayController') . '</div>',
            '#markup' => '<div class="cy"></div>',
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

    public function displayAjax($type, NodeInterface $node) {

        $nid = 1;
        if (isset($node) && is_numeric($node->id())) {
            $nid = $node->id();
        }
        $result["node"] = $node->id();

        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select->fields('blcy', ['elements', 'nid']);
        $select->condition('nid', $nid);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);

        $result = array();
        // $result['elements'] = [
        //     ['data'=> ['id'=>'a']],
        //     ['data'=> ['id'=>'b']],
        //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        // ];

        // $connection = \Drupal::database();
        // $connection->insert('blcytoscape')->fields(array(
        //     'nid' => 1,
        //     'elements' => json_encode($result['elements']),
        //     'created' => time(),
        // ))->execute();

        foreach ($entries as $entry) {
            $result['elements'] = json_decode($entry['elements']);
        }
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
        
        // $result['nid'] = $nid;
        $response = new JsonResponse();
        $response->setData($result);
        return $response;
    }
}