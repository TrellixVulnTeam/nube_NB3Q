<?php

namespace Drupal\consum_theme_blocks\Plugin\WebformHandler;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\media\Entity\Media;
use Drupal\node\NodeInterface;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "new_image_diario_viaje",
 *   label = @Translation("New Image Diario de Viaje"),
 *   category = @Translation("Image Diario de Viaje"),
 *   description = @Translation("New Image Diario de Viaje webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class NewImageDiarioViajeWebformHandler extends WebformHandlerBase
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
    //Está desarrollado para que se guarde en el ultimo diario (activo) del current node (viaje).
    $values = $webform_submission->getData();
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface) {
      $formImage = $values["imagen"];
      if ($formImage) {
        $NewImage = File::load($formImage);
        $NewImage->setPermanent();
        $NewImage->save();
      }
      $did = $node->get("field_diario_de_viaje")->get(count($node->get("field_diario_de_viaje"))-1)->target_id;
      $image_media = Media::create([
        'bundle' => 'image',
        'name' => "Image ".$node->getTitle()." ".$node->id()." -> ".$did,
        'uid' => \Drupal::currentUser()->id(),
        'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
        'moderation_state' => [
          'target_id' => 'published',
        ],
        'image' => [
          'target_id' => $NewImage->id(),
          'alt' => $node->getTitle(),
          'title' => $values["titulo"],
        ],
      ]);
      $image_media->save();

      $diario = Node::load($did);
      $diario->field_imagenes_usrs_diario_viaje[] = $image_media;
      $diario->save();
    }
  }
}