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
use Drupal\node\Entity\Node;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "new_recipe",
 *   label = @Translation("New Recipe"),
 *   category = @Translation("Recipe"),
 *   description = @Translation("New Recipe webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class NewRecipeWebformHandler extends WebformHandlerBase
{

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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator, WebformTokenManagerInterface $token_manager)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
    $this->tokenManager = $token_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
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
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE)
  {
    $values = $webform_submission->getData();
    $pasos = [];
    for ($i = 0; $i < count($values['pasos']); $i++) {
      if (isset($values['pasos'][$i]['titulo_del_paso']) && $values['pasos'][$i]['titulo_del_paso'] != null){
        $title = $values['titulo_de_la_receta']." - ".$values['pasos'][$i]['titulo_del_paso'];
      }else{
        $num_paso = $i + 1;
        $title = $values['titulo_de_la_receta']." - Paso ".$num_paso;
      }
      $paso = Node::create(array(
        'type' => 'paso_receta',
        'title' => $title,
        'field_descripcion' => $values['pasos'][$i]['descripcion_del_paso'],
        'field_image' => $values['pasos'][$i]['imagen_del_paso'],
        'uid' => \Drupal::currentUser()->id(),
        'moderation_state' => [
          'target_id' => 'published',
        ],
      ));
      $paso->save();
      $pasos[$i] = $paso->id();
    }

    for ($i = 0; $i < count($values['ingredientes']); $i++) {
      $ingredientes[$i] = [
        'nombre' => $values['ingredientes'][$i]['ingrediente'],
        'cantidad' => $values['ingredientes'][$i]['cantidad_ingrediente'],
        'unidad' => $values['ingredientes'][$i]['medida_ingrediente'],
      ];
    }

    $node = Node::create(array(
      'type' => 'receta',
      'title' => $values['titulo_de_la_receta'],
      'body' => $values['descripcion'],
      'field_image' => $values['fotografia_principal'],
      'field_dificultad' => $values['nivel_de_dificultad'],
      'field_tiempo_prep_total' => $values['tiempo_de_preparacion'],
      'field_video_de_la_receta' => $values['video_de_la_receta'],
      'field_tipo_de_plato' => $values['tipo_de_plato'],
      'field_tipo_de_comida' => $values['tipo_de_comida'],
      'field_tipo_de_dieta' => $values['dieta_especial'],
      'field_ingrediente' => $ingredientes,
      'field_paso' => $pasos,
      'field_extra' => $values['_tienes_algun_secreto_para_que_la_receta_salga_perfecta_'],
      'uid' => \Drupal::currentUser()->id(),
      'moderation_state' => [
        'target_id' => 'published',
      ],
    ));
    $node->save();
  }
}
