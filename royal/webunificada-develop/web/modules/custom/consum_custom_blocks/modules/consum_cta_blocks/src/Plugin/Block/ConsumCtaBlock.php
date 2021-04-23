<?php

namespace Drupal\consum_cta_blocks\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines CTA block.
 *
 * @Block(
 *   id = "consum_cta_block",
 *   admin_label = @Translation("Click To Action TEST")
 * )
 */
class ConsumCtaBlock extends ConsumCtaBlockBase {

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
    unset($form['image2']);
    unset($form['border']);
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
    $render['#theme'] = 'consum_cta';
    unset($render['#img2']);
    unset($render['#border']);
    return $render;
  }

}
