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
            '#markup' => t('<b>The File</b>'),
        );
        
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