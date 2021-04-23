<?php

namespace Drupal\consum_iam_mdm\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\user\UserInterface;

/**
 * Defines the Api CALL entity.
 *
 * @ingroup consum_iam_mdm
 *
 * @ContentEntityType(
 *   id = "api_call",
 *   label = @Translation("Api Call entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\consum_iam_mdm\Controller\ApiCallListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\consum_iam_mdm\Access\ApiCallAccessControlHandler",
 *   },
 *   base_table = "api_call",
 *   admin_permission = "administer api call entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/api_call/{api_call}",
 *     "collection" = "/api_call/list"
 *   },
 * )
 *
 */
class ApiCallEntity extends ContentEntityBase {

  use EntityChangedTrait; // Implements methods defined by EntityChangedInterface.

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Api call entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Api call entity.'))
      ->setReadOnly(TRUE);

    // Field name of the request (random field).
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The label of the Api Call entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Field URL'))
      ->setDescription(t('The URL of the api request.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_body'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Field Body'))
    ->setDescription(t('The Body of the api response.'))
    ->setSettings(array(
      'default_value' => '',
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'text_default',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'text_textarea',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_method'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Field Method'))
    ->setDescription(t('The method of the api request.'))
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_response'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Field Response'))
    ->setDescription(t('The Response of the api request.'))
    ->setSettings(array(
      'default_value' => '',
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'text_default',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'text_textarea',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_retryed'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Field retryed'))
    ->setDescription(t('The number of request tries on Api with date.'))
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_status_code'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Field status code'))
    ->setDescription(t('The status code of the api request.'))
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_success'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Field URL'))
    ->setDescription(t('The success (or not) of the api request.'))
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_user_modified'] = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('User Name'))
    ->setDescription(t('The Name of the associated user.'))
    ->setSetting('target_type', 'user')
    ->setSetting('handler', 'default')
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'entity_reference_label',
      'weight' => -3,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'entity_reference_autocomplete',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'size' => 60,
        'autocomplete_type' => 'tags',
        'placeholder' => '',
      ),
      'weight' => -3,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // URL field from the request.
    $fields['field_error'] = BaseFieldDefinition::create('text_long')
    ->setLabel(t('Field error'))
    ->setDescription(t('The error of the api request.'))
    ->setSettings(array(
      'default_value' => '',
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'above',
      'type' => 'text_default',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'text_textarea',
      'weight' => -5,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);


    return $fields;
  }
}
