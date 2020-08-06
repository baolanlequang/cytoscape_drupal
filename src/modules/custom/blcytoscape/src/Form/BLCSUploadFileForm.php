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
            '#markup' => t('<b>The File</b><br /><div class="cy"></div>'),
        );

        $form['#attached']['library'][] = 'blcytoscape/blcytoscape_upload';
        // $form['#attached']['drupalSettings']['cytoscape']['elements'] = [
        //     ['data'=> ['id'=>'a']],
        //     ['data'=> ['id'=>'b']],
        //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        // ];
        $form['#attached']['drupalSettings']['cytoscape']['elements'] = array();
        $form['#attached']['drupalSettings']['cytoscape']['style'] = [
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
        $form['#attached']['drupalSettings']['cytoscape']['layout'] = [
            'name'=>'grid',
            'rows'=>1,
        ];
        
        $validators = array(
            'file_validate_extensions' => array('json'),
        );
        $form['my_file'] = array(
            '#type' => 'managed_file',
            '#name' => 'my_file',
            '#title' => t('File *'),
            '#size' => 20,
            '#description' => t('json format only'),
            '#upload_validators' => $validators,
            '#upload_location' => 'public://blcytoscape/files/',
            '#ajax' => array(
                'event' => 'fade',
                'callback' => '::formSelectChanged',
                'wrapper' => 'cy',
            )
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
        
        if ($form_state->getValue('my_file') == NULL) {
            $form_state->setErrorByName('my_file', $this->t('File.'));
        }
        else {
            $fid = $form_state->getValue('my_file');
            $file = File::load($fid[0]);
            $file_uri = $file->getFileUri();
            $json_content = file_get_contents($file_uri);
            $messenger_service = \Drupal::service('messenger');
            $strMsg = "Thank for your RSVP, you are on the list for the event {$json_content}";
            $messenger_service->addMessage(t($strMsg));

            $form['#attached']['drupalSettings']['cytoscape']['elements'] = array(
                'addd' => 'ahiih',
            );
            
            // $response = new AjaxResponse();
            // $Text = 'My Text'; /* A string that contains the text to display as a JavaScript alert.*/
            // return $response->addCommand(new AlertCommand($Text));;
            // $this->formSelectChanged($form, $form_state);

            // $form['#attached']['library'][] = 'blcytoscape/blcytoscape_upload';
            // $form['#attached']['drupalSettings']['cytoscape']['elements'] = [
            //     ['data'=> ['id'=>'a']],
            //     ['data'=> ['id'=>'b']],
            //     ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
            // ];
            // $output = $renderer->renderRoot($form);
            // $response = new AjaxResponse();
            // $response->setAttachments($form['#attached']);
            // return $response->addCommand(new ReplaceCommand(NULL, $output));

            
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $messenger_service = \Drupal::service('messenger');
        
        
        
        $messenger_service->addMessage(t('Thank for your RSVP, you are on the list for the event.'));
    }

    public function formSelectChanged(array &$form, FormStateInterface $form_state) {
        $form['#attached']['drupalSettings']['cytoscape']['elements'] = [
            ['data'=> ['id'=>'a']],
            ['data'=> ['id'=>'b']],
            ['data'=> ['id'=>'ab', 'source'=>'a', 'target'=>'b']],
        ];
        $response = new AjaxResponse();
        $Text = 'My Text'; /* A string that contains the text to display as a JavaScript alert.*/
        return $form;
    }

    public static function uploadAjaxCallback(&$form, FormStateInterface &$form_state, Request $request) {

        // /** @var \Drupal\Core\Render\RendererInterface $renderer */
        // $renderer = \Drupal::service('renderer');
        // $form_parents = explode('/', $request->query
        //   ->get('element_parents'));
      
        // // Retrieve the element to be rendered.
        // $form = NestedArray::getValue($form, $form_parents);
      
        // // Add the special AJAX class if a new file was added.
        // $current_file_count = $form_state
        //   ->get('file_upload_delta_initial');
        // if (isset($form['#file_upload_delta']) && $current_file_count < $form['#file_upload_delta']) {
        //   $form[$current_file_count]['#attributes']['class'][] = 'ajax-new-content';
        // }
        // else {
        //   $form['#suffix'] .= '<span class="ajax-new-content"></span>';
        // }
        // $status_messages = [
        //   '#type' => 'status_messages',
        // ];
        // $form['#prefix'] .= $renderer
        //   ->renderRoot($status_messages);
        // $output = $renderer
        //   ->renderRoot($form);
        // $response = new AjaxResponse();
        // $response
        //   ->setAttachments($form['#attached']);
        // return $response
        //   ->addCommand(new ReplaceCommand(NULL, $output));
        $Text = 'My Text'; /* A string that contains the text to display as a JavaScript alert.*/
        return $response->addCommand(new AlertCommand($Text));;
      }
}