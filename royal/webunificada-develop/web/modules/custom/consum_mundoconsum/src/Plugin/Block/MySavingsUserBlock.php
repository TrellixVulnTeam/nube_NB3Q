<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\MySavingsUserBlock.
 */

namespace Drupal\consum_mundoconsum\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User as EntityUser;
use stdClass;
use User;

/**
 *
 * @Block(
 *   id = "mysavings_user_mundoconsum_block",
 *   admin_label = @Translation("My Savings User Block")
 * )
 */
class MySavingsUserBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
  public function defaultConfiguration() {
    return [
      'limit' => '',
    ];
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
    if (\Drupal::currentUser()->id() == '0') {
      $user = EntityUser::load(1);
      $user_image = File::load($user->user_picture->target_id);
    }
    else {
      $user = EntityUser::load(\Drupal::currentUser()->id());
      $user_image = File::load($user->user_picture->target_id);
    }

    // User data container.
    $elements['row'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'mysavings_user_block row',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'user-data-block col-md-9 row',
      ],
      '#tree' => TRUE,
    ];

    // User image data wrapper.
    $elements['row']['user_data']['user-image-wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'user-image-wrapper col-md-3',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data']['user-image-wrapper']['image'] = [
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => $user_image->getFileUri(),
    ];

    $elements['row']['user_data']['user-image-wrapper']['name'] = [
      '#type' => 'markup',
      '#markup' => '<p class="user-name"><b>' . $user->get('field_nombre')->value . ' ' . $user->get('field_apellido1')->value . '</b></p>',
    ];

    /*$elements['row']['user_data']['user-image-wrapper']['level'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-level">Nivel 2</div>',
    ];*/

    // User save money data wrapper.
    $elements['row']['user_data']['user-save-wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'user-save-wrapper col-md-4',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data']['user-save-wrapper']['ticket'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-ticket"><p>' . $this->t('My present ticket') . '<p><h3>3,15€</h3></div>',
    ];

    $elements['row']['user_data']['user-save-wrapper']['coupon'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-element">
        <p>' . $this->t('Code') . ': 00789</p>
      </div>',
    ];

    // User purchase on this month data wrapper.
    $elements['row']['user_data']['user-accumulate-wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'user-accumulate-wrapper col-md-4',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data']['user-accumulate-wrapper']['title'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-title"><p' . $this->t('You accumulate') . '<b> 114€ </b>' .
        $this->t('from January 2020.') . '</p></div>',
    ];

    $elements['row']['user_data']['user-accumulate-wrapper']['my-coupons'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => 'boton1',
          'data-toggle' => 'modal',
          'data-target' => '#modal-detail-cheque-regalo-1',
      ],
      '#url' => Url::fromUri('http://consum.docker.localhost/'),
      '#title' => $this->t('View my present coupon'),
    ];

    // User links container.
    $elements['row']['user_data']['view-all'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'view-all-wrapper offset-md-3 col-8',
      ],
      '#tree' => TRUE,
    ];


    // User links container.
    $elements['row']['links'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'links-block col-md-3',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['links']['my-coupons'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('http://consum.docker.localhost/'),
      '#attributes' => [
        'data-toggle' => 'modal',
        'data-target' => '#modal-cheques-regalo',
      ],
      '#title' => $this->t('My available coupons'),
    ];

    $elements['row']['links']['my-buy-items'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('http://consum.docker.localhost/'),
      '#attributes' => [
        'data-toggle' => 'modal',
        'data-target' => '#modal-my-shopping',
      ],
      '#title' => $this->t('My shopping'),
    ];

    // modal cheques regalo
    $elements['modal'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal fade',
        'id' => 'modal-cheques-regalo',
      ],
      '#tree' => TRUE,
    ];

    $elements['modal']['user_data'] = [
      '#type' => 'markup',
      '#markup' => '<div class="modal-dialog">
        <p class="image-header"><img src="/themes/custom/consum_es/assets/img/mi_ahorro/cheque_regalo_ico.png" alt="Cheque regalo"></p>
        <div class="modal-content"><div class="modal-body">
        <div class="modal-title">
        <h3>Mis Cheques regalo</h3>
        <p>Tienes un cheque disponible para canjear</p>
        </div>
        <div class="row cheque-data d-flex align-items-center">
          <div class="col-md-4"><h6>Marzo 2020</h6><p>Fecha de caducidad: 03/2021</p></div>
          <div class="col-md-4"><h3>8,95€</h3></div>
          <div class="col-md-4"><a class="boton1" data-toggle="modal" data-target="#modal-detail-cheque-regalo-1"
          href="#modal-detail-cheque-regalo-1">Ver</a></div>
        </div>
        <div class="row cheque-data d-flex align-items-center">
          <div class="col-md-4"><h6>Marzo 2020</h6><p>Fecha de caducidad: 03/2021</p></div>
          <div class="col-md-4"><h3>8,95€</h3></div>
          <div class="col-md-4"><span class="boton3" ">Cheque canjeado</span></div>
        </div>
        <div class="row cheque-data d-flex align-items-center">
          <div class="col-md-4"><h6>Marzo 2020</h6><p>Fecha de caducidad: 03/2021</p></div>
          <div class="col-md-4"><h3>8,95€</h3></div>
          <div class="col-md-4"><span class="boton3" ">Cheque canjeado</span></div>
        </div>
        <div class="row cheque-data d-flex align-items-center">
          <div class="col-md-4"><h6>Marzo 2020</h6><p>Fecha de caducidad: 03/2021</p></div>
          <div class="col-md-4"><h3>8,95€</h3></div>
          <div class="col-md-4"><span class="boton3" ">Cheque canjeado</span></div>
        </div>
      </div></div></div>',
    ];

    // modal detalle cheque regalo
    $elements['modal-detail'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal modal-detail-cheque fade',
        'id' => 'modal-detail-cheque-regalo-1',
      ],
      '#tree' => TRUE,
    ];

    $elements['modal-detail']['user_data'] = [
      '#type' => 'markup',
      '#markup' => '<div class="modal-dialog">
        <p class="image-header">
          <img src="/themes/custom/consum_es/assets/img/mi_ahorro/cheque_regalo_ico.png" alt="Cheque regalo">
        </p>
        <div class="modal-content">
        <div class="modal-body">

        <div class="modal-title">
        <h3>Aquí tienes tu Cheque Regalo</h3>
        </div>
        <div class="row details-1 d-flex align-items-center">
          <div class="col-md-6"><h2>8,95€</h2><p>Fecha de caducidad: 03/2020</div>
          <div class="col-md-6"><p>Muestra en caja este código</p>
          <h3>00789</h3>
          <p><a href="#imprimir">Imprimir este cheque regalo</a></p>
        </div>
        </div>
        <h6>Detalle del cheque</h6>
        <div class="row details-2 d-flex align-items-center">
          <div class="col-md-3"><h3>0€</h3><p>Descuentos anteriores pendientes de aplicar</p></div>
          <div class="col-md-auto"><h3>+</h3></div>
          <div class="col-md-3"><h3>5,85€</h3><p>Descuento mensual</p></div>
          <div class="col-md-auto"><h3>+</h3></div>
          <div class="col-md-3"><h3>3,10€</h3><p>Cheque Crece + Mis Favoritos</p></div>
        </div>
        <p class="clause">Cálculo Descuento Mensual: 468,12€ (compra) x 1,25% (descuento) = 5,85€</p>

      </div></div></div>',
    ];

    // modal my shopping

    $elements['modal-my-shopping'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'modal modal-my-shopping fade',
        'id' => 'modal-my-shopping',
      ],
      '#tree' => TRUE,
    ];

    $elements['modal-my-shopping']['user_data'] = [
      '#type' => 'markup',
      '#markup' => '<div class="modal-dialog">
        <p class="image-header">
          <img src="/themes/custom/consum_es/assets/img/mi_ahorro/cheque_regalo_ico.png" alt="Cheque regalo">
        </p>
        <div class="modal-content">
        <div class="modal-body">

        <div class="modal-title">
        <h3>Mis compras</h3>
        <p>Compras del mes y % de descuento conseguido</p>
        <br>
        <br>
        <h1>Contenido pendiente de desarrollo</h1>
        </div>
      </div></div></div>',
    ];

    return $elements;
  }

}
