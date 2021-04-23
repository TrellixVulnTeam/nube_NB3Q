<?php
/**
 * @file
 * Contains \Drupal\simple_ldap_role\Form\SimpleLdapRoleSettingsForm
 */

namespace Drupal\simple_ldap_role\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\simple_ldap\SimpleLdapServer;
use Drupal\simple_ldap\SimpleLdapServerSchema;

class SimpleLdapRoleSettingsForm extends ConfigFormBase {

  /**
   * @var SimpleLdapServer
   */
  protected $server;

  /**
   * @var SimpleLdapServerSchema
   */
  protected $schema;

  /**
   * {@inheritdoc}
   *
   * Overwrite default constructor so we can also grab the server.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
    $this->server = \Drupal::service('simple_ldap.server');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_ldap_role_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'simple_ldap.role',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('simple_ldap.role');

    // Only display the form if we have a successful binding.
    if ($this->server->bind()) {
      $this->schema = \Drupal::service('simple_ldap.schema');
      $object_classes = $this->getObjectClassOptions();
      $schema_defaults = $this->schema->getDefaultAttributeSettings();
      $selected_object_classes = $config->get('object_class') ? $config->get('object_class') : $schema_defaults['object_class'];

      // If this is Active Directory, we lock the main attribute options.
      $readonly = ($this->server->getServerType() == 'Active Directory') ? TRUE : FALSE;
      if ($readonly) {
        $this->messenger()->addWarning($this->t('Your server is Active Directory, so some settings have been disabled.'));
      }

      // If there is user input via an ajax callback, set it here
      $selected_object_classes = $form_state->getValue('object_class') ? $form_state->getValue('object_class') : $selected_object_classes;

      $form['role'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('LDAP Authorization'),
        '#open' => TRUE,
      );

      $form['role']['basedn_group'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Base DN Group Search'),
        '#description' => $this->t('The Base DN that will be searched for group member. Ex: ou=groups,dc=example,dc=com'),
        '#required' => TRUE,
        '#default_value' => $config->get('basedn_group'),
      );

      $form['role']['basedn_user'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Base DN User Search'),
        '#description' => $this->t('The Base DN that will be searched for user accounts. Ex: ou=users,dc=example,dc=com'),
        '#required' => TRUE,
        '#default_value' => $config->get('basedn_user'),
      );

      $form['role']['scope'] = array(
        '#type' => 'radios',
        '#title' => $this->t('Search scope'),
        '#options' => array(
          'sub' => $this->t('Subtree') . ' - ' . t('Search the base DN and all of its children for user accounts.'),
          'one' => $this->t('One-level') . ' - ' . t('Do not include children of the base DN while searching for user accounts.'),
        ),
        '#required' => TRUE,
        '#default_value' => $config->get('scope'),
      );

      $form['role']['object_class'] = array(
        '#type' => 'select',
        '#title' => $this->t('Group ObjectClass'),
        '#options' => $object_classes,
        '#default_value' => $selected_object_classes,
        '#required' => TRUE,
        '#multiple' => TRUE,
        '#size' => 10,
        '#description' => $this->t('Which LDAP objectClass should be used when searching for a group. This also determines which attributes you have available to map below.'),
        '#disabled' => $readonly,
      );

      $role_names= user_role_names(TRUE);

      foreach($role_names as $key => $roles) {
        if ($key == 'authenticated') {
          continue;
        }
        $value = $config->get('roles.' . $key);
        $default_value =  is_array($value) ? implode("\r\n", $value) : $value;
        $form['role']['ldap_roles'][$key] = array(
          '#type' => 'textarea',
          '#title' => t($roles),
          '#attributes' => array('placeholder' => 'One LDAP Group per line for ' . $roles),
          '#default_value' => $default_value,
        );
      }

    }
    else {
      $this->messenger()->addWarning($this->t('There is a problem with your LDAP Server connection. As a result, this form has been disabled. Please <a href="@url">check your settings</a>.',
        array('@url' => Url::fromRoute('simple_ldap.server')->toString())));
    }

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory()->getEditable('simple_ldap.role');
    $values = $form_state->getValues();
    $this->configFactory()->getEditable('simple_ldap.role')->delete();
    foreach($values as $key => $value) {
      if (empty($value) || strpos($key,'form_') === 0 || $key == 'submit' || $key == 'op') {
        continue;
      }
      if (in_array($key, ['basedn_user', 'basedn_group', 'scope', 'object_class'])) {
        $config->set($key, $value);
      } else {
        $value = str_replace("\r\n", "\n", $value);
        $set_value =  array_filter(explode("\n", $value), 'trim');
        $config->set('roles.' . $key, $set_value);
      }
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * @return array
   *  A array of objectClasses formatted for use as options in a Form API element.
   */
  private function getObjectClassOptions() {
    $object_classes = $this->schema->getSchemaItem('objectClasses');
    foreach ($object_classes as $key => $object_class) {
      $object_classes[$key] = $object_class['name'];
    }

    asort($object_classes);
    return $object_classes;
  }

}
