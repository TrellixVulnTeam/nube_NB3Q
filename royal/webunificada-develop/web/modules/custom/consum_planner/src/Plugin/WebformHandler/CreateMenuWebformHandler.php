<?php

namespace Drupal\consum_planner\Plugin\WebformHandler;

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
use Drupal\node\Entity\Node;

/**
 * Webform example handler.
 *
 * @WebformHandler(
 *   id = "example",
 *   label = @Translation("Example"),
 *   category = @Translation("Example"),
 *   description = @Translation("Example of a webform submission handler."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_IGNORED,
 * )
 */
class CreateMenuWebformHandler extends WebformHandlerBase {

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
  public function defaultConfiguration() {
    return [
      'message' => 'This is a custom message.',
      'debug' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // Message.
    $form['message'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Message settings'),
    ];
    $form['message']['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message to be displayed when form is completed'),
      '#default_value' => $this->configuration['message'],
      '#required' => TRUE,
    ];

    return $this->setSettingsParents($form);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['message'] = $form_state->getValue('message');
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $values = $webform_submission->getData();
    $recipes = $this->getNodesGenerator($values);
    if (strpos(' ', $values['comida'] !== FALSE)) {
      $values['comida'] = '5comidas';
    }

    $node = Node::create([
      'title' => 'Menu - ' . time(),
      'type' => 'menu',
      'field_days' => $values['dias'],
      'field_foods' => $values['comida'],
    ]);
    $node->save();

    // Add image.
    $uri = 'public://comida_planificador.JPG';

    // Check first if the file exists for the uri.
    $files = \Drupal::entityTypeManager()
      ->getStorage('file')
      ->loadByProperties(['uri' => $uri]);
    $file = reset($files);

    // If not create a file.
    if (!$file) {
      $file = File::create([
        'uri' => $uri,
      ]);
      $file->save();
    }

    $node->field_image[] = [
      'target_id' => $file->id(),
      'alt' => 'Alt text',
      'title' => 'Title',
    ];

    $node->save();

    for ($i = 0; $i < count($values['dieta']); $i++) {
      $node->set('field_diet', $values['dieta'][$i]);
    }

    for ($i = 1; $i <= $values['dias']; $i++) {
      $fieldName = 'field_recipes_' . $i;
      $node->set($fieldName, $recipes[$i]);
    }
    $node->save();
  }

  /**
   * {@inheritdoc}
   */
  protected function getNodesGenerator(array $values) {
    $food_types = $values['comida'];
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadByProperties(['vid' => 'tipo_de_plato']);

    // Select the food type from form.
    switch ($food_types) {
      case 'desayuno':
        foreach ($terms as $term) {
          if (strpos($term->label(), 'Desayuno') !== FALSE) {
            $newNodes = $this->getNodesLogic($term->id(), $values);
            if (!empty($newNodes)) {
              $nodes[] = $newNodes;
            }
          }
        }
        for ($i = 1; $i <= $values['dias']; $i++) {
          $nodeids[$i][] = array_rand($nodes[rand(0, (count($nodes) - 1))]);
          if (($rand = rand(1, 2)) == 2) {
            $nodeids[$i][] = array_rand($nodes[rand(0, count($nodes) - 1)]);
          }
        }
        break;

      case 'almuerzo':
        foreach ($terms as $term) {
          if (strpos($term->label(), 'Primero') !== FALSE) {
            $nodes_first = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Segundo') !== FALSE) {
            $nodes_second = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Postre') !== FALSE) {
            $nodes_dessert[] = $this->getNodesLogic($term->id(), $values);
          }
        }
        for ($i = 1; $i <= $values['dias']; $i++) {
          $nodeids[$i][] = array_rand($nodes_first);
          $nodeids[$i][] = array_rand($nodes_second);
          $nodeids[$i][] = array_rand($nodes_dessert[rand(0, count($nodes_dessert) - 1)]);
        }
        break;

      case 'merienda':
        foreach ($terms as $term) {
          if (strpos($term->label(), 'Merienda') !== FALSE) {
            $newNodes = $this->getNodesLogic($term->id(), $values);
            if (!empty($newNodes)) {
              $nodes[] = $newNodes;
            }
          }
        }
        for ($i = 1; $i <= $values['dias']; $i++) {
          $nodeids[$i][] = array_rand($nodes[rand(0, count($nodes) - 1)]);
          if (($rand = rand(1, 2)) == 2) {
            $nodeids[$i][] = array_rand($nodes[rand(0, count($nodes) - 1)]);
          }
        }
        break;

      case 'cena':
        foreach ($terms as $term) {
          if (strpos($term->label(), 'Guarnición') !== FALSE) {
            $nodes_guarn = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Primero') !== FALSE) {
            $nodes_first = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Postre') !== FALSE) {
            $nodes_dessert[] = $this->getNodesLogic($term->id(), $values);
          }
        }
        for ($i = 1; $i <= $values['dias']; $i++) {
          $nodeids[$i][] = array_rand($nodes_guarn);
          $nodeids[$i][] = array_rand($nodes_first);
          $nodeids[$i][] = array_rand($nodes_dessert[rand(0, count($nodes_dessert) - 1)]);
        }
        break;

      default:
        foreach ($terms as $term) {
          if (strpos($term->label(), 'Desayuno') !== FALSE) {
            $newNodes = $this->getNodesLogic($term->id(), $values);
            if (!empty($newNodes)) {
              $nodes_breakfast[] = $newNodes;
            }
          }
          elseif (strpos($term->label(), 'Primero') !== FALSE) {
            $nodes_first = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Segundo') !== FALSE) {
            $nodes_second = $this->getNodesLogic($term->id(), $values);
          }
          elseif (strpos($term->label(), 'Postre') !== FALSE) {
            $nodes_dessert[] = $this->getNodesLogic($term->id(), $values);
          }
          if (strpos($term->label(), 'Merienda') !== FALSE) {
            $newNodes = $this->getNodesLogic($term->id(), $values);
            if (!empty($newNodes)) {
              $nodes_evening[] = $newNodes;
            }
          }
          elseif (strpos($term->label(), 'Guarnición') !== FALSE) {
            $nodes_guarn = $this->getNodesLogic($term->id(), $values);
          }
        }

        for ($i = 1; $i <= $values['dias']; $i++) {
          $nodeids[$i][] = array_rand($nodes_breakfast[rand(0, (count($nodes_breakfast) - 1))]);
          if (($rand = rand(1, 2)) == 2) {
            $nodeids[$i][] = array_rand($nodes_breakfast[rand(0, count($nodes_breakfast) - 1)]);
          }
          $nodeids[$i][] = array_rand($nodes_first);
          $nodeids[$i][] = array_rand($nodes_second);
          $nodeids[$i][] = array_rand($nodes_dessert[rand(0, count($nodes_dessert) - 1)]);
          $nodeids[$i][] = array_rand($nodes_evening[rand(0, count($nodes_evening) - 1)]);
          if (($rand = rand(1, 2)) == 2) {
            $nodeids[$i][] = array_rand($nodes_evening[rand(0, count($nodes_evening) - 1)]);
          }
          $nodeids[$i][] = array_rand($nodes_guarn);
          $nodeids[$i][] = array_rand($nodes_first);
          $nodeids[$i][] = array_rand($nodes_dessert[rand(0, count($nodes_dessert) - 1)]);
        }

        break;

    }

    return $nodeids;
  }

  /**
   * {@inheritdoc}
   */
  protected function getNodesLogic($term_id, array $values) {
    if ((!empty($values['dieta'])) && (!empty($values['alergia']))) {
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
        'type' => 'receta',
        'field_tipo_de_plato' => $term_id,
        'field_tipo_de_dieta' => $values['dieta'],
        'field_necesidades_especificas' => $values['alergia'],
      ]);
    }
    elseif (!empty($values['dieta'])) {
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
        'type' => 'receta',
        'field_tipo_de_plato' => $term_id,
        'field_tipo_de_dieta' => $values['dieta'],
      ]);
    }
    elseif (!empty($values['alergia'])) {
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
        'type' => 'receta',
        'field_tipo_de_plato' => $term_id,
        'field_necesidades_especificas' => $values['alergia'],
      ]);
    }
    else {
      $nodes = $this->entityTypeManager->getStorage('node')->loadByProperties([
        'type' => 'receta',
        'field_tipo_de_plato' => $term_id,
      ]);
    }

    return $nodes;
  }

}
