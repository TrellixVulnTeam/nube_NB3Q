<?php

namespace Drupal\consum_comunidad\Plugin\WebformHandler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\Entity\User;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "set_profile",
 *   label = @Translation("Set User Profile"),
 *   category = @Translation("Set User Profile"),
 *   description = @Translation("Set User Profile webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class ChangeProfileWebformHandler extends WebformHandlerBase {

  /**
   * The token manager.
   *
   * @var \Drupal\webform\WebformTokenManagerInterface
   */
  protected $tokenManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, WebformTokenManagerInterface $token_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->tokenManager = $token_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator'),
      $container->get('webform.token_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $values = $webform_submission->getData();
    $account = User::load(\Drupal::currentUser()->id());
    $account->set("user_picture", $values['avatar']);
    $account->set("field_nombre", $values['nombre_del_usuario']);
    $account->set("field_sobre_mi", $values['sobre_mi']);
    $account->set("field_intereses", $values['intereses']);
    $account->set("field_ver_perfil_de_facebook", $values['ver_perfil_facebook']);
    $account->set("field_perfil_de_facebook", $values['perfil_de_facebook']);
    $account->set("field_ver_perfil_de_twitter", $values['ver_perfil_twitter']);
    $account->set("field_perfil_de_twitter", $values['perfil_de_twitter']);
    $account->set("field_ver_perfil_de_instagram", $values['ver_perfil_instagram']);
    $account->set("field_perfil_de_instagram", $values['perfil_de_instagram']);
    $account->set("field_notificacion_comentario_mi", $values['notificacion_receta']);
    $account->set("field_notificacion_email_amigo", $values['notificacion_amigo']);
    $account->save();
  }
}
