<?php

namespace Drupal\consum_openid\Form;

use \Drupal\openid_connect\Form\OpenIDConnectLoginForm;
use Drupal\Core\Form\FormStateInterface;


class ConsumOpenIDLoginForm extends OpenIDConnectLoginForm
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'consum_openid_connect_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildForm($form, $form_state);

    $form['openid_connect_client_consum_login']['#value'] = $this->t('ACCESS Partners-Customers');

    return $form;
  }

}
