<?php
/**
 * @file
 * Contains \Drupal\blcytoscape\Form\BLCSSettingDisplayForm
 */
namespace Drupal\blcytoscape\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\InvokeCommand;

class BLCSSettingDisplayForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'blcytoscape_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return ['blcytoscape.settings'];
    }

    /**
     * 
     */
    protected function loadListGraphs() {
        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select
            ->fields('blcy', ['id','name']);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        // $entries = $select->execute()->fetchAll();
        // var_dump($entries);
        return $entries;
    }

    /**
     * 
     */
    protected function loadGraphInfo($graph_id) {
        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select->join('blcytoscape_style', 'blcy_style', 'blcy.id = blcy_style.graphid');
        $select
            ->fields('blcy', ['elements'])
            ->fields('blcy_style', ['style'])
            ->condition('blcy.id', $graph_id);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    /**
     * 
     */
    protected function loadNodeInfo($node_id) {
        $select = Database::getConnection()->select('node_field_data', 'nfd');
        $select
            ->fields('nfd', ['nid','title'])
            ->condition('nfd.nid', $node_id);
        $entries = $select->execute()->fetchAssoc();
        return $entries;
    }

    /**
     * 
     */
    protected function loadListNodes() {
        $select = Database::getConnection()->select('node_field_data', 'nfd');
        $select
            ->fields('nfd', ['nid','title']);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
        $list_graphs = $this->loadListGraphs();
        $options_graph = array('0' => t('Select a graph'));
        foreach ($list_graphs as $row) {
            $options_graph[$row['id']] = $row['name'];
        }
        $form['type_options'] = array(
            '#type' => 'value',
            '#value' => $options_graph,
        );
        $form['blcytoscape_graph_list'] = array(
            '#title' => t('List of graphs'),
            '#type' => 'select',
            '#options' => $form['type_options']['#value'],
            '#ajax' => array(
                'event' => 'change',
                'callback' => '::formSelectChanged',
                'wrapper' => 'edit-output',
            )
        );
        $form['output'] = [
            '#type' => 'textfield',
            '#title' => t('Node is using graph'),
            '#size' => '60',
            '#disabled' => TRUE,   
            '#prefix' => '<div id="edit-output">',
            '#suffix' => '</div>',
        ];

        $form['graph_display'] = array(
            '#markup' => t('<b>The graph</b><br /><div class="cy"></div>'),
        );
        $form['#attached']['library'][] = 'blcytoscape/blcytoscape_settings';
        $form['#attached']['drupalSettings']['cytoscape']['elements'] = array();
        $form['#attached']['drupalSettings']['cytoscape']['style'] = array();
        $form['#attached']['drupalSettings']['cytoscape']['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];

        $list_nodes = $this->loadListNodes();
        $options_nodes = array('0' => t('Select a node to display graph'));
        foreach ($list_nodes as $row) {
            $options_nodes[$row['nid']] = $row['title'];
        }
        $form['type_options_node'] = array(
            '#type' => 'value',
            '#value' => $options_nodes,
        );
        $form['blcytoscape_node_list'] = array(
            '#title' => t('List of nodes'),
            '#type' => 'select',
            '#options' => $form['type_options_node']['#value']
        );

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $messenger_service = \Drupal::service('messenger');
        
        // $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        // $connection = \Drupal::database();
        // $connection->insert('rsvplist')->fields(array(
        //     'mail' => $form_state->getValue('email'),
        //     'nid' => $form_state->getValue('nid'),
        //     'uid' => $user->id(),
        //     'created' => time(),
        // ))->execute();

        if ($selectedGraph = $form_state->getValue('blcytoscape_graph_list')) {
            // $messenger_service->addMessage($selectedGraph);
            if ($selectedNode = $form_state->getValue('blcytoscape_node_list')) {
                // $messenger_service->addMessage($selectedNode);
                $update = Database::getConnection()->update('blcytoscape');
                $update
                    ->fields([
                    'nid' => $selectedNode,
                    ])
                    ->condition('id', $selectedGraph);
                $update->execute();
            }
        }
    }

    public function formSelectChanged(array &$form, FormStateInterface $form_state) {
        $data = array();
        if ($selectedValue = $form_state->getValue('blcytoscape_graph_list')) {
            // Get the text of the selected option. 
            $selectedText = $form['blcytoscape_graph_list']['#options'][$selectedValue];
            $nodeInfo = $this->loadNodeInfo($selectedValue);
            $form['output']['#value'] = $nodeInfo['title'];
            $entries = $this->loadGraphInfo($selectedValue);
            foreach ($entries as $entry) {
                $data['elements'] = json_decode($entry['elements'], true);
                $data['style'] = json_decode($entry['style'], true);
            }
            // $data['entries'] = $entries;
            $data['node_title'] = $nodeInfo['title'];
        }
        // // $form['#attached']['library'][] = 'blcytoscape/cytoscapelib';
        // // $form['#attached']['library'][] = 'blcytoscape/blcytoscape';
        // $form['#attached']['drupalSettings']['cytoscape']['elements'] = [
        //     ['data'=> ['id'=>'a']],
        //     ['data'=> ['id'=>'b']],
        //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        // ];
        
        // // Return the prepared textfield.
        // // return $form['output']; 
        // // return [$form['output'], $form['#attached']];
        // $response = new AjaxResponse();
        // return $response;

        $response = new AjaxResponse();

        
        // $data['elements'] = [
        //     ['data'=> ['id'=>'a']],
        //     ['data'=> ['id'=>'b']],
        //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        // ];

        $response->addCommand(new InvokeCommand(NULL, 'displayCytoScape', [$data]));
        return $response;
    }
}
