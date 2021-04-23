<?php

/**
 * @file
 * Contains \Drupal\consum_mundoconsum\Plugin\Block\HomeUserBlock.
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
 *   id = "homeuser_mundoconsum_block",
 *   admin_label = @Translation("Home MundoConsum User Block")
 * )
 */
class HomeUserBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
        'class' => 'homeuser_mundoconsum_block row',
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

    $elements['row']['user_data']['user-save-wrapper']['title'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-title"><h3>5\'54€</h3><p>' . $this->t('You have saved this month') . '<p><hr></div>',
    ];

    $elements['row']['user_data']['user-save-wrapper']['my-present'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-element">
        <p>' . $this->t('My present ticket') . '</p><p>3\'15€' . ' ' . $this->t('disponibles') . '<p>
      </div>',
    ];

    $elements['row']['user_data']['user-save-wrapper']['my-coupons'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-element">
        <p>' . $this->t('My coupons') . '</p><p>9' . ' ' . $this->t('disponibles') . '<p>
      </div>',
    ];

    $elements['row']['user_data']['user-save-wrapper']['my-favourites'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-element">
        <p>' . $this->t('My Favourites') . '</p><p>9' . ' ' . $this->t('disponibles') . '<p>
      </div>',
    ];

    // User purchase on this month data wrapper.
    $elements['row']['user_data']['user-purchase-wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'user-purchase-wrapper col-md-4',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data']['user-purchase-wrapper']['title'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-save-title"><h3>125€</h3><p>' . $this->t('This month you have bought') . '<p><hr></div>',
    ];

    $elements['row']['user_data']['user-purchase-wrapper']['my-purchase'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-purchase-discount">
      <div class="progress">
      <div class="progress-bar bg-warning w-75" ></div>
    </div>125€<p class="message-small">' . $this->t('You have achieved the maximum discount!') . '</p>
      </div>',
    ];

    $elements['row']['user_data']['user-purchase-wrapper']['accumulate'] = [
      '#type' => 'markup',
      '#markup' => '<div class="user-purchase-accumulate">
        <p>' . $this->t('You accumulate') . '<span class="big-accumulate"> 1,25% </span>' . $this->t('of you have bought this month') . '</p>
        <p class="message-small">' . $this->t('Keep buying and you will get other advantages') . '</p>
      </div>',
    ];

    // User links container.
    $elements['row']['user_data']['view-all'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'view-all-wrapper offset-md-3 col-8',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['user_data']['view-all']['link'] = [
      '#type' => 'link',
      '#attributes' => [
        'class' => 'boton1',
      ],
      '#url' => Url::fromUri('internal:/mi-ahorro'),
      '#title' => $this->t('View All'),
    ];

    // User links container.
    $elements['row']['links'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'links-block col-md-3',
      ],
      '#tree' => TRUE,
    ];

    $elements['row']['links']['my-messages'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/mi-comunidad'),
      '#attributes' => [
        'class' => 'btn-ci ci-messages',
      ],
      '#title' => $this->t('My messages'),
    ];

    $elements['row']['links']['my-community'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/mi-comunidad'),
      '#attributes' => [
        'class' => 'btn-ci ci-charla',
      ],
      '#title' => $this->t('My community'),
    ];

    $elements['row']['links']['my-recipes'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/node/2894'),
      '#attributes' => [
        'class' => 'btn-ci ci-wishlist',
      ],
      '#title' => $this->t('My recipes'),
    ];


    $elements['row']['links']['content-save'] = [
      '#type' => 'link',
      '#url' => Url::fromUri('internal:/node/2893'),
      '#attributes' => [
        'class' => 'btn-ci ci-tickets',
      ],
      '#title' => $this->t('Content Save'),
    ];

    return $elements;
  }

}
