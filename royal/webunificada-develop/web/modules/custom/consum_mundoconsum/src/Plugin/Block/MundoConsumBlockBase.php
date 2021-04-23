<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\MundoConsumBlockBase.
 */

namespace Drupal\consum_mundoconsum\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use stdClass;

/**
 * Defines a base block implementation that most Mundoconsum blocks plugins will extend.
 *
 * This abstract class provides the generic block configuration form, default
 * block settings, and handling for general user-defined block visibility
 * settings.
 *
 * @ingroup mundoconsum
 */
abstract class MundoConsumBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  // Injection services

 public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }

  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = [];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save configurations.
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $elements = [];

    // Data container.
    $elements['title'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];

    // Data container.
    $elements['articles'] = [
      '#type' => 'container',
      '#tree' => TRUE,
    ];

    return $elements;
  }

  public function getAux() {
    // Articles container.
    $aux = [
      'value' => [
        '0' => '3€',
        '1' => '0,45€',
        '2' => '20%',
        '3' => '1,25€',
        '4' => '18%',
        '5' => '3€',
        '6' => '0,45€',
        '7' => '20%',
        '8' => '1,25€',
        '9' => '18%',
        '10' => '3€',
        '11' => '20%',
      ],
      'article' => [
        '0' => '5182',
        '1' => '16075',
        '2' => '5074',
        '3' => '5182',
        '4' => '4968',
        '5' => '5182',
        '6' => '16075',
        '7' => '5074',
        '8' => '5182',
        '9' => '4968',
        '10' => '5182',
        '11' => '16075',
      ],
      'selection' => [
        '0' => 'Te gusta',
        '1' => 'Te puede gustar',
        '2' => 'Tu selección',
        '3' => 'Te puede Gustar',
        '4' => 'Tu selección',
        '5' => 'Te gusta',
        '6' => 'Te puede gustar',
        '7' => 'Tu selección',
        '8' => 'Te puede Gustar',
        '9' => 'Tu selección',
        '10' => 'Te puede Gustar',
        '11' => 'Tu selección',
      ],
    ];

    return $aux;
  }

}
