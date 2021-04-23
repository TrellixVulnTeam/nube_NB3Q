<?php

namespace Drupal\royal_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Defines Carousel.
 *
 * @Block(
 *   id = "carousel_block",
 *   admin_label = @Translation("Carousel")
 * )
 */
class CarouselBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'image1' => '',
      'image2' => '',
      'image3' => '',
      'image4' => '',
      'image5' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['image1'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen 1'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image1']) ? $this->configuration['image1'] : '',
      '#description' => $this->t('Imagen 1'),
      '#required' => TRUE,
    ];

    $form['image2'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen 2'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image2']) ? $this->configuration['image2'] : '',
      '#description' => $this->t('Imagen 2'),
      '#required' => FALSE,
    ];

    $form['image3'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen 3'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image3']) ? $this->configuration['image3'] : '',
      '#description' => $this->t('Imagen 3'),
      '#required' => FALSE,
    ];

    $form['image4'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen 4'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image4']) ? $this->configuration['image4'] : '',
      '#description' => $this->t('Imagen 4'),
      '#required' => FALSE,
    ];

    $form['image5'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Imagen 5'),
      '#upload_validators'    => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['gif png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#theme' => 'image_widget',
      '#preview_image_style' => 'medium',
      '#default_value' => isset($this->configuration['image5']) ? $this->configuration['image5'] : '',
      '#description' => $this->t('Imagen 5'),
      '#required' => FALSE,
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Save image 1 as permanent.
    $image1 = $form_state->getValue('image1');
    if ($image1 != $this->configuration['image1']) {
      if (!empty($image1[0])) {
        if ($file = File::load($image1[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }

    // Save image 1 as permanent.
    $image2 = $form_state->getValue('image2');
    if ($image2 != $this->configuration['image2']) {
      if (!empty($image2[0])) {
        if ($file = File::load($image2[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }

    // Save image 1 as permanent.
    $image3 = $form_state->getValue('image3');
    if ($image3 != $this->configuration['image3']) {
      if (!empty($image3[0])) {
        if ($file = File::load($image3[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }

    // Save image 1 as permanent.
    $image4 = $form_state->getValue('image4');
    if ($image4 != $this->configuration['image4']) {
      if (!empty($image4[0])) {
        if ($file = File::load($image4[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }

    // Save image 1 as permanent.
    $image5 = $form_state->getValue('image5');
    if ($image5 != $this->configuration['image5']) {
      if (!empty($image5[0])) {
        if ($file = File::load($image5[0])) {
          $file->setPermanent();
          $file->save();
        }
      }
    }

    // Save configurations.
    $this->configuration['image1'] = $form_state->getValue('image1');
    $this->configuration['image2'] = $form_state->getValue('image2');
    $this->configuration['image3'] = $form_state->getValue('image3');
    $this->configuration['image4'] = $form_state->getValue('image4');
    $this->configuration['image5'] = $form_state->getValue('image5');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $file_url1 = "";
    $file_url2 = "";
    $file_url3 = "";
    $file_url4 = "";
    $file_url5 = "";

    $image1 = $this->configuration['image1'];
    if (!empty($image1[0])) {
      if ($file = File::load($image1[0])) {
        $file_url1 = $file->url();
      }
    }

    $image2 = $this->configuration['image2'];
    if (!empty($image2[0])) {
      if ($file = File::load($image2[0])) {
        $file_url2 = $file->url();
      }
    }

    $image3 = $this->configuration['image3'];
    if (!empty($image3[0])) {
      if ($file = File::load($image3[0])) {
        $file_url3 = $file->url();
      }
    }

    $image4 = $this->configuration['image4'];
    if (!empty($image4[0])) {
      if ($file = File::load($image4[0])) {
        $file_url4 = $file->url();
      }
    }

    $image5 = $this->configuration['image5'];
    if (!empty($image5[0])) {
      if ($file = File::load($image5[0])) {
        $file_url5 = $file->url();
      }
    }

    return [
      '#theme' => 'content_carousel',
      '#img1' => $file_url1,
      '#img2' => $file_url2,
      '#img3' => $file_url3,
      '#img4' => $file_url4,
      '#img5' => $file_url5,
    ];
  }
}
