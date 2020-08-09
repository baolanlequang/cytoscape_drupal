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
        // $form['#attached']['drupalSettings']['cytoscape']['elements'] = [
        //     ['data'=> ['id'=>'a']],
        //     ['data'=> ['id'=>'b']],
        //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        // ];
        // $form['#attached']['drupalSettings']['cytoscape']['style'] = [
        //     [
        //         'selector'=>'node', 
        //         'style'=>[
        //             'background-color'=>'#666',
        //             'label'=>'data(id)'
        //         ],
        //     ],
        //     [
        //         'selector'=>'edge', 
        //         'style'=>[
        //             'width'=>3,
        //             'line-color'=>'#ccc',
        //             'target-arrow-color'=>'#ccc',
        //             'target-arrow-shape'=>'triangle',
        //             'curve-style'=>'bezier'
        //         ],
        //     ],
        // ];
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
            '#title' => t('Elements file *'),
            '#size' => 20,
            '#id' => 'bl_cytoscaple_elements_tmp_file',
            '#description' => t('json and cyjs format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://blcytoscape/files/',
        );

        $form['bl_cytoscaple_style'] = array(
            '#type' => 'managed_file',
            '#name' => 'bl_cytoscaple_style',
            '#title' => t('Style file *'),
            '#size' => 20,
            '#id' => 'bl_cytoscaple_style_file',
            '#description' => t('json format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://blcytoscape/files/',
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
        
        
        
        $messenger_service->addMessage(t('Thank for your RSVP, you are on the list for the event.'));
    }

    
}