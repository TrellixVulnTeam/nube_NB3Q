<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'CarouselTaxonomy' block.
 *
 * @Block(
 *  id = "carousel_taxonomy",
 *  admin_label = @Translation("Carousel taxonomy"),
 * )
 */
class CarouselTaxonomy extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
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
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'terms' => '',
      'ids' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load vocabularies.
    $vocabulary_storage = $this->entityManager->getStorage('taxonomy_vocabulary');
    $vocabularies = $vocabulary_storage->loadMultiple();

    // Create an array with vocabularies.
    foreach ($vocabularies as $vocabulary) {
      $list[] = $vocabulary->label();
    }

    $form['taxonomy'] = [
      '#type' => 'select',
      '#title' => $this->t('Choose Vocabulary'),
      '#options' => $list,
      '#default_value' => $this->configuration['taxonomy'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['taxonomy'] = $form_state->getValue('taxonomy');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Load vocabularies.
    $vocabulary_storage = $this->entityManager->getStorage('taxonomy_vocabulary');
    $vocabularies = $vocabulary_storage->loadMultiple();

    // Create an array with vocabularies.
    foreach ($vocabularies as $vocabulary) {
      $list[] = $vocabulary->id();
    }

    // Load terms.
    $terms_storage = $this->entityManager->getStorage('taxonomy_term');
    $terms = $terms_storage->loadTree($list[$this->configuration['taxonomy']]);

    foreach ($terms as $term_name) {
      $terms_names[] = [$term_name->tid => $term_name->name];
    }

    return [
      '#theme' => 'content_carousel_taxonomy',
      '#terms' => $terms_names,
    ];
  }

}
