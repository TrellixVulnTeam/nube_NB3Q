<?php

namespace Drupal\consum_cta_blocks\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

/**
 * Defines Popup Custom block.
 *
 * @Block(
 *   id = "consum_popup_custom_block",
 *   admin_label = @Translation("Popup TEST")
 * )
 */

class ConsumPopupCustomBlock extends ConsumCtaBlockBase {

  /**
   * {@inheritdoc}
   */

  public function defaultConfiguration() {

    $config =  parent::defaultConfiguration();

    /* set default for new attributes */
   
    $config['modal_text'] = FALSE;

    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    unset($form['border']);
    unset($form['target']);
    unset($form['autocomplete']);
    unset($form['link']);
  

    $form['modal_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Modal Text'),
      '#default_value' => $this->configuration['modal_text']['value'],
      '#format' => 'rich_text',
    ];


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    // Save configurations.

    $this->configuration['modal_text'] = $form_state->getValue('modal_text');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $render = parent::build();
    $render['#theme'] = 'consum_popup_custom';

    $modal_text = $this->configuration['modal_text'];

    $render['#modal_text'] = $modal_text;

    unset($render['#border']);
    unset($render['#target']);
    unset($render['#autocomplete']);
    unset($render['#link']);

    return $render;
  }

}
