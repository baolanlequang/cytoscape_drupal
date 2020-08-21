<?php
/**
 * @file
 * Contains \Drupal\blcytoscape\Form\BLCSUploadFileForm
 */
namespace Drupal\blcytoscape\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Symfony\Component\HttpFoundation\Request;


class BLCSUploadFileForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'blcytoscape_upload_file_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = array(
            '#attributes' => array('enctype' => 'multipart/form-data'),
        );
        
        $form['file_upload_details'] = array(
            '#markup' => t('<b>The graph</b><br /><div class="cy"></div>'),
        );

        $form['#attached']['library'][] = 'blcytoscape/blcytoscape_upload';
        $form['#attached']['drupalSettings']['cytoscape']['elements'] = array();
        $form['#attached']['drupalSettings']['cytoscape']['style'] = array();
        $form['#attached']['drupalSettings']['cytoscape']['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];
        
        $validators = array(
            'file_validate_extensions' => array('json cyjs'),
        );
        $form['bl_cytoscaple_elements'] = array(
            '#type' => 'managed_file',
            '#name' => 'bl_cytoscaple_elements',
            '#title' => t('Data file'),
            '#size' => 20,
            '#id' => 'bl_cytoscaple_elements_tmp_file',
            '#description' => t('json and cyjs format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://blcytoscape/files/',
            '#required' => TRUE,
        );

        $form['bl_cytoscaple_style'] = array(
            '#type' => 'managed_file',
            '#name' => 'bl_cytoscaple_style',
            '#title' => t('Style file'),
            '#size' => 20,
            '#id' => 'bl_cytoscaple_style_file',
            '#description' => t('json format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://blcytoscape/files/',
            '#required' => TRUE,
        );

        $form['bl_cytoscaple_graph_name'] = array(
            '#title' => t('Graph name'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t("Please input name for this graph"),
            '#required' => TRUE,
        );
        

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {    
        
        if ($form_state->getValue('bl_cytoscaple_elements') == NULL) {
            $form_state->setErrorByName('bl_cytoscaple_elements', $this->t('File.'));
        }
        else {
            $fid = $form_state->getValue('bl_cytoscaple_elements');
            $file = File::load($fid[0]);
            $file_uri = $file->getFileUri();
            $json_content = file_get_contents($file_uri);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $messenger_service = \Drupal::service('messenger');

        //get content of elements data
        $fid_elements = $form_state->getValue('bl_cytoscaple_elements');
        $file_elements = File::load($fid_elements[0]);
        $file_elements_uri = $file_elements->getFileUri();
        $json_content_elements = file_get_contents($file_elements_uri);
        $element_data = json_decode($json_content_elements, true);
        $element_json_to_save = json_encode($element_data['elements']);

        //get content of styles data
        $fid_styles = $form_state->getValue('bl_cytoscaple_style');
        $file_styles = File::load($fid_styles[0]);
        $file_styles_uri = $file_styles->getFileUri();
        $json_content_styles = file_get_contents($file_styles_uri);
        $styles_data = json_decode($json_content_styles, true);
        $styles_json_to_save = json_encode($styles_data[0]['style']);

        $connection = \Drupal::database();
        //save element data
        $graph_name = $form_state->getValue('bl_cytoscaple_graph_name');
        $graph_id = $connection->insert('blcytoscape')->fields(array(
            'name' => $graph_name,
            'elements' => $element_json_to_save,
            'created' => time(),
        ))->execute();

        if (isset($graph_id)) {
            $connection->insert('blcytoscape_style')->fields(array(
                'graphid' => $graph_id,
                'name' => $graph_name,
                'style' => $styles_json_to_save,
            ))->execute();
        }
        
        
        $messenger_service->addMessage(t('Thank for your submit graph: '.$graph_name));
    }

    
}