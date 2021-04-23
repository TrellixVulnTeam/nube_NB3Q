<?php

namespace Drupal\consum_custom_blocks\Plugin\Block;

use Drupal\consum_custom_blocks\Plugin\Block\ConsumCustomBlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines CustomText block.
 *
 * @Block(
 *   id = "consum_customtext_block",
 *   admin_label = @Translation("Custom Text TEST")
 * )
 */
class ConsumCustomtextBlock extends ConsumCustomBlockBase {

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
    unset($form['image1']);
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
    $render['#theme'] = 'consum_customtext';
    unset($render['#img1']);
    unset($render['#img2']);
    unset($render['#border']);
    return $render;
  }

}
