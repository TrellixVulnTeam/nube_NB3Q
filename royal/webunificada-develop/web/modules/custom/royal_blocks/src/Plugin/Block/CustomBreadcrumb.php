<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a 'CustomBreadcrumb' block.
 *
 * @Block(
 *  id = "custom_breadcrumb",
 *  admin_label = @Translation("Custom Breadcrumb"),
 * )
 */
class CustomBreadcrumb extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager,
                              ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'container' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load nodes storage.
    $node_storage = $this->entityManager->getStorage('node');

    $form['container'] = [
      '#type' => 'container',
      '#prefix' => '<div id="field-wrapper">',
      '#suffix' => '</div>',
    ];

    // Count and sets total number items if exists.
    if ($this->configuration['container']) {
      $num = $form_state->get('num');
      $total = count($this->configuration['container']);
      if (!$num) {
        $form_state->set('num', $total);
      }
      else {
        $form_state->set('num', $num);
      }
    }

    // Sets default value for $num if not exists.
    $num = $form_state->get('num');
    if (empty($num)) {
      $num = 1;
      $form_state->set('num', $num);
    }

    // Created fields with $num like limit.
    for ($i = 0; $i < $num; $i++) {

      $form['container'][$i] = [
        '#type'  => 'entity_autocomplete',
        '#title' => $this->t('Select Pages Titles'),
        '#description' => t('Select page title that wants to include on custom breadcrumb.'),
        '#target_type' => 'node',
        '#default_value' => isset($this->configuration['container'][$i]) ? $node_storage->load($this->configuration['container'][$i]) : '',
        '#bundles' => ['page', 'landing_page'],
      ];
    }

    // Get config container value.
    $config = $this->configFactory->getEditable('block.block.custombreadcrumb');
    $container = $config->get('settings.container');

    if (!empty($container)) {
      foreach ($container as $key => $item) {
        // Get not null values.
        if ($item !== NULL) {
          $items[] = $item;
        }
      }
    }
    // Clear all values (null and not null) of config variable.
    $config->clear('settings.container');
    if (isset($items)) {
      // Set only not empty (not null) values on config variable.
      $config->set('settings.container', $items);
    }
    else {
      $config->set('settings.container', '');
    }
    // Save new values.
    $config->save();
    $form_state->setRebuild();

    // Button add more.
    $form['container']['check_add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add More'),
      '#submit' => [
        [$this, 'checkMore'],
      ],
      '#id' => 'checkAdd',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function checkMore(array &$form, FormStateInterface $form_state) {

    // Has items previous.
    if ($this->configuration['container'] && !$form_state->get('num')) {
      $num = $form_state->get('num');
      $form_state->set('num', $num + 1);
    }
    // Hasn't items previous and $num isn't initialitated.
    if (!$form_state->get('num')) {
      $form_state->set('num', $num + 2);
    }
    else {
      $num = $form_state->get('num') + 1;
      $form_state->set('num', $num);
    }
    $form_state->setRebuild();

  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $form_state->setRebuild();
    $this->configuration['container'] = $form_state->getValue('container');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // If not empty load node objects from storage.
    $node_storage = $this->entityManager->getStorage('node');
    if (!empty($this->configuration['container'])) {

      $nodes = $node_storage->loadMultiple($this->configuration['container']);

      foreach ($nodes as $node) {
        $pagesTitles[] = [
          $node->getTitle(),
          $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()]),
        ];
      }
    }

    return [
      '#theme' => 'content_custom_breadcrumb',
      '#pagesTitles' => $pagesTitles,
    ];
  }

}
