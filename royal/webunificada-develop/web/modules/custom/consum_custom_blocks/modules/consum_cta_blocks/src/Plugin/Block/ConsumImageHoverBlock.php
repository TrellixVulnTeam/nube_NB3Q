<?php

namespace Drupal\consum_cta_blocks\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines Image Rollover block.
 *
 * @Block(
 *   id = "consum_image_hover_block",
 *   admin_label = @Translation("Image Rollover TEST")
 * )
 */
class ConsumImageHoverBlock extends ConsumCtaBlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    unset($form['button_color']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $render = parent::build();
    $render['#theme'] = 'content_consum-imagenhover';
    unset($render['button_color']);
    return $render;
  }

}
