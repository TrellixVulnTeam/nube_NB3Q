<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Provides a 'RecentGuidesBlock' block.
 *
 * @Block(
 *  id = "recent_guides_block",
 *  admin_label = @Translation("Guías-Recientes"),
 * )
 */
class RecentGuidesBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
      'category' => '',
      'ocultar' => '',
      'offset' => 0,
      'maquetacion' => 'card',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    // Load taxonomy storage and objects.
    $taxonomy_storage = $this->entityManager->getStorage('taxonomy_term');
    $terms = $taxonomy_storage->loadByProperties([
      'vid' => [
        'tipo_de_guia',
        'vino_tipologia',
      ],
    ]);

    foreach ($terms as $term) {
      $guideType[] = $term->get('name')->getValue()[0]['value'];
    }

    $form['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#description' => $this->t('You can choose the Category'),
      '#default_value' => $this->configuration['category'],
      '#empty_value' => '',
      '#required' => FALSE,
      '#options' => $guideType,
    ];
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
    $this->configuration['category'] = $form_state->getValue('category');
    $this->configuration['ocultar'] = $form_state->getValue('ocultar');
    $this->configuration['offset'] = $form_state->getValue('offset');
    $this->configuration['maquetacion'] = $form_state->getValue('maquetacion');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Load taxonomy storage and objects.
    $taxonomy_storage = $this->entityManager->getStorage('taxonomy_term');
    $terms = $taxonomy_storage->loadByProperties([
      'vid' => [
        'tipo_de_guia',
        'vino_tipologia',
      ],
    ]);

    /* Gets terms array again for use terms array index generated of
    configuration form. */
    foreach ($terms as $term) {
      $terms_childrens[] = $term;
    }

    /* Uses category's field value of configuration form like key to obtain
    the term. */
    if ($this->configuration['category'] !== '') {
      $term_id = $terms_childrens[$this->configuration['category']]->get('tid')->getValue()[0]['value'];

      if ($terms_childrens[$this->configuration['category']]->get('vid')->getValue()[0]['target_id'] === 'vino_tipologia') {
        $query = $this->entityQuery->get('node')
          ->condition('status', 1)
          ->condition('type', 'guia_de_vino')
          ->condition('field_tipo_vino', $term_id)
          ->range($this->configuration['offset'], 1)
          ->sort('created', 'DESC');
        $entity_ids = $query->execute();
      }
      if ($terms_childrens[$this->configuration['category']]->get('vid')->getValue()[0]['target_id'] === 'tipo_de_guia') {
        $query = $this->entityQuery->get('node')
          ->condition('status', 1)
          ->condition('type', 'guia_general')
          ->condition('field_tipo_de_guia', $term_id)
          ->range($this->configuration['offset'], 1)
          ->sort('created', 'ASC');
        $entity_ids = $query->execute();
      }
    }
    else {
      $query = $this->entityQuery->get('node')
        ->condition('status', 1)
        ->condition('type', ['guia_general', 'guia_de_vino'], 'IN')
        ->range($this->configuration['offset'], 1)
        ->sort('created', 'ASC');
      $entity_ids = $query->execute();
    }

    // Load node storage and objects.
    $node_storage = $this->entityManager->getStorage('node');
    $nodes = $node_storage->loadMultiple($entity_ids);

    // Load view builder for node entity.
    $view_builder = $this->entityManager->getViewBuilder('node');

    foreach ($nodes as $node) {
      $view = $view_builder->view($node, $this->configuration['maquetacion']);
    }

    return [
      '#theme' => 'content_recent_guides',
      '#vista' => $view,
    ];
  }

}
