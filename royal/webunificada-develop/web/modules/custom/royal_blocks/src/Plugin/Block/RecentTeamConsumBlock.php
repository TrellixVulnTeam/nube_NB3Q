<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Provides a 'RecentTeamConsumBlock' block.
 *
 * @Block(
 *  id = "recent_team_consum_block",
 *  admin_label = @Translation("Team Consum-Recientes"),
 * )
 */
class RecentTeamConsumBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\webprofiler\Entity\EntityManagerWrapper definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * The entity query service.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entity_manager,
                              QueryFactory $entityQuery) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
    $this->entityQuery = $entityQuery;
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
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'ocultar' => '',
      'offset' => 0,
      'maquetacion' => 'card',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $list_ocultar = [
      'movil' => $this->t('Ocultar en móvil'),
      'tablet' => $this->t('Ocultar en Tablet'),
      'escritorio' => $this->t('Ocultar en Escritorio'),
    ];
    $form['ocultar'] = [
      '#type' => 'checkboxes',
      '#title' => t('Ocultar en dispositivos'),
      '#default_value' => $this->configuration['ocultar'],
      '#options' => $list_ocultar,
    ];
    $range = range(0, 4);
    $form['offset'] = [
      '#type' => 'select',
      '#title' => t('Offset'),
      '#default_value' => $this->configuration['offset'],
      '#options' => array_combine($range, $range),
    ];
    $list_maquetacion = [
      'card' => 'Card',
      'card_img_izq_4_8' => 'Card imagen izquierda 1/3',
      'card_img_der_4_8' => 'Card imagen derecha 1/3',
      'card_img_izq_6_6' => 'Card imagen izquierda 1/2',
      'card_img_der_6_6' => 'Card imagen derecha 1/2',
    ];
    $form['maquetacion'] = [
      '#type' => 'select',
      '#title' => t('Selecciona el tipo de maquetación'),
      '#default_value' => $this->configuration['maquetacion'],
      '#options' => $list_maquetacion,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
    $this->configuration['offset'] = $form_state->getValue('offset');
    $this->configuration['maquetacion'] = $form_state->getValue('maquetacion');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $query = $this->entityQuery->get('node')
      ->condition('status', 1)
      ->condition('type', 'team_consum')
      ->range($this->configuration['offset'], 1)
      ->sort('created', 'DESC');

    $entity_ids = $query->execute();

    // Load node storage and objects.
    $node_storage = $this->entityManager->getStorage('node');
    $nodes = $node_storage->loadMultiple($entity_ids);

    // Load view builder for node entity.
    $view_builder = $this->entityManager->getViewBuilder('node');

    foreach ($nodes as $node) {
      $view = $view_builder->view($node, $this->configuration['maquetacion']);
    }

    return [
      '#theme' => 'content_recent_team_consum',
      '#vista' => $view,
    ];
  }

}
