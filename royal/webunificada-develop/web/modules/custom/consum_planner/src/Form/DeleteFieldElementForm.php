<?php

namespace Drupal\consum_planner\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Generates a delete field element form.
 */
class DeleteFieldElementForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'deletefieldelementform_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $field = \Drupal::routeMatch()->getParameter('refid');

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
    $field = \Drupal::routeMatch()->getParameter('refid');
    $node->save();
    $form_state->setRedirect('entity.node.canonical', ['node' => $node->id()]);
  }

}
