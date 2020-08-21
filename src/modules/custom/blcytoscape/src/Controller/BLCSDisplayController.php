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
        // $nid = $node->nid->value;
        // $nid = 0;

        $build = array();
        if (isset($node) && is_numeric($node->id())) {
            $nid = $node->id();
            $build = array(
                '#type' => 'markup',
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
            
            $build['bl_cytoscaple_graph_name'] = array(
                '#title' => t('Graph name '.$nid),
                '#type' => 'textfield',
                '#size' => 25,
                '#description' => t("Please input name for this graph"),
                '#required' => TRUE,
            );
        }

        

        return $build;
    }

    protected function load($node_id) {
        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select->join('blcytoscape_style', 'blcy_style', 'blcy.id = blcy_style.graphid');
        $select
            ->fields('blcy', ['elements', 'nid'])
            ->fields('blcy_style', ['style'])
            ->condition('blcy.nid', $node_id);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    public function displayAjax($type, NodeInterface $node) {

        // $this->insertDemoData();

        $nid = 1;
        if (isset($node) && is_numeric($node->id())) {
            $nid = $node->id();
        }
        $result["node"] = $node->id();

        $entries = $this->load($nid);

        $result = array();

        foreach ($entries as $entry) {
            $result['elements'] = json_decode($entry['elements'], true);
            $result['style'] = json_decode($entry['style'], true);
        }

        $result['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];
        
        $response = new JsonResponse();
        $response->setData($result);
        return $response;
    }

    private function insertDemoData() {

        $result = array();
        $nid = 1;

        $result = array();
        $result['elements'] = [
            ['data'=> ['id'=>'a']],
            ['data'=> ['id'=>'b']],
            ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        ];
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
        $connection = \Drupal::database();
        $connection->insert('blcytoscape')->fields(array(
            'nid' => 1,
            'name' => 'graph 1',
            'elements' => json_encode($result['elements']),
            'created' => time(),
        ))->execute();
        $connection->insert('blcytoscape_style')->fields(array(
            'graphid' => 1,
            'name' => 'style 1',
            'style' => json_encode($result['style']),
        ))->execute();
    }
}