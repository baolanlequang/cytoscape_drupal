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

class BLCSSettingDisplayForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'blcytoscape_admin_settings';
    }

    /**
     * 
     */
    protected function loadListGraphs() {
        $select = Database::getConnection()->select('blcytoscape', 'blcy');
        $select
            ->fields('blcy', ['elements', 'nid']);
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
        // $types = node_type_get_names();
        // $config = $this->config('rsvplist.settings');
        // $form['rsvplist_types'] = array(
        //     '#type' => 'checkboxes',
        //     '#title' => $this->t('The content types to enable RSVP collection for'),
        //     '#default_value' => $config->get('allowed_types'),
        //     '#options' => $types,
        //     '#description' => t('On the specified node type, and RSVP option will be available and can be enabled while that node is being edited.'),
        // );
        // $form['array_filter'] = array(
        //     '#type' => 'value',
        //     '#value' => TRUE
        // );
        $list_graphs = $this->loadListGraphs();
        $form['blcytoscape_graph_list'] = array(
            '#title' => t('List of graphs'),
            '#type' => 'select',
            '#options' => $list_graphs,
        );
        return parent::buildForm($form, $form_state);
    }
}
