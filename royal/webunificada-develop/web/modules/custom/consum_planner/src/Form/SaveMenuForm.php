<?php

namespace Drupal\consum_planner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements save menu form.
 */
class SaveMenuForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'savemenu_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');

    $form['title'] = [
      '#type' => 'markup',
      '#markup' => '<h2>' . $this->t('Set your menu name') . '</h2>',
    ];

    $form['title_node'] = [
      '#type' => 'textfield',
      '#placeholder' => $this->t('Menu name'),
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['mt-4', 'col-5'],
      ],
      '#required' => TRUE,
    ];

    $fid = $node->field_image->target_id;
    $form['image'] = [
      '#title' => $this
        ->t('Image'),
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#default_value' => $fid ? [$fid] : NULL,
      '#upload_validators' => [
        'file_validate_extensions' => [
          'gif png jpg jpeg',
        ],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#required' => FALSE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $node->setTitle($form_state->getValue('title_node'));
    $node->set('moderation_state', "published");
    $node->set('field_image', $form_state->getValue('image'));
    $node->save();
    $form_state->setRedirect('entity.node.canonical', ['node' => $node->id()]);
  }

}
