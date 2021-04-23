<?php

namespace Drupal\consum_theme_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class ConsumThemeMenuSettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'consum_theme_blocks_admin_menu_settings';
  }

  protected function getEditableConfigNames() {
    return [
      'consum_theme_blocks.settings'
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('consum_theme_blocks.settings');

    $node_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();

    $list_content_types = [];
    foreach ($node_types as $node_type) {
      $list_content_types[$node_type->id()] = $node_type->label();
    }

    /*$vid = 'vocabulary_name';*/
    /*$terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);*/
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree(0);

    $list_taxonomy_types = [];
    foreach ($terms as $term) {
      $list_taxonomy_types[$term->id()] = $term->label();
    }

    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Tipos de contenido'),
      '#description' => $this->t('Indica el tipo de contenido a controlar'),
      '#default_value' => $config->get('menusettings.content_types') ? $config->get('menusettings.content_types') : '',
      '#options' => $list_content_types,
      '#attributes' => [
        'id' => 'target',
      ],
    ];

    if (!$form_state->has('items_temp_level_1')) {
      $form_state->set('items_temp_level_1', $config->get('menusettings.items_level_1.items') ? $config->get('menusettings.items_level_1.items') : '');
    }
    $items_level_1 = $form_state->get('items_temp_level_1');

    if (!$form_state->has('items_temp_level_2')) {
      $form_state->set('items_temp_level_2', $config->get('menusettings.items_level_2.items') ? $config->get('menusettings.items_level_2.items') : '');
    }
    $items_level_2 = $form_state->get('items_temp_level_2');

    $form['#tree'] = TRUE;

    $form['items_level_1'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('<h3>Items level 1</h3>'),
      '#prefix' => '<div id="items-level-1-wrapper">',
      '#suffix' => '</div>',
    ];

    if (!$form_state->has('num_items_level_1')) {
      if (count($config->get('menusettings.items_level_1.items')) < 1){
        $form_state->set('num_items_level_1', 1);
      }else{
        $form_state->set('num_items_level_1', count($config->get('menusettings.items_level_1.items')));
      }
    }

    $num_items_level_1 = $form_state->get('num_items_level_1');

    for ($i = 1; $i <= $num_items_level_1; $i++) {

      $form_state->set('key_change','key_change_'.$form_state->get('num_items_level_1'));

      $form['items_level_1']['item'.$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Item '.$i),
      ];


      $form['items_level_1']['item'.$i]['content_type_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Content type'),
        '#default_value' => isset($items_level_1['item'.$i]['content_type']) ? $items_level_1['item'.$i]['content_type'] : '',
        '#options' => $list_content_types,
      ];

      $form['items_level_1']['item'.$i]['autocomplete_'.$form_state->get('key_change')] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Item de menú'),
        '#target_type' => 'node',
        '#description' => $this->t('Indica el item de menú a controlar.'),
        '#default_value' => isset($items_level_1['item'.$i]['node']) ? Node::load($items_level_1['item'.$i]['node']) : '',
      ];

      if ($num_items_level_1 >= 2){
        $form['items_level_1']['item'.$i]['eliminar'.$i] = [
          '#type' => 'submit',
          '#value' => t('Eliminar'),
          '#name' => 'delete_lvl1-'.$i,
          '#submit' => [[$this, 'removeOneLevel1']],
          '#ajax' => [
            'callback' => [$this, 'removeOneLevel1Callback'],
            'wrapper' => 'items-level-1-wrapper',
          ]
        ];
      }
    }

    $form['items_level_1']['actions'] = [
      '#type' => 'actions',
    ];

    $form['items_level_1']['actions']['add_item'] = [
      '#type' => 'submit',
      '#value' => t('Añadir item'),
      '#submit' => [[$this, 'addMoreLevel1']],
      '#ajax' => [
        'callback' => [$this, 'addMoreLevel1Callback'],
        'wrapper' => 'items-level-1-wrapper',
      ],
    ];

    //LEVEL 2
    $list_types = [
      'no_sub' => $this->t('Sin subcategorías'),
      'yes_sub' => $this->t('Con subcategorías'),
    ];


    $form['items_level_2'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('<h3>Items level 2</h3>'),
      '#prefix' => '<div id="items-level-2-wrapper">',
      '#suffix' => '</div>',
    ];

    if (!$form_state->has('num_items_level_2')) {
      if (count($config->get('menusettings.items_level_2.items')) < 1){
        $form_state->set('num_items_level_2', 1);
      }else{
        $form_state->set('num_items_level_2', count($config->get('menusettings.items_level_2.items')));
      }
    }

    $num_items_level_2 = $form_state->get('num_items_level_2');

    for ($i = 1; $i <= $num_items_level_2; $i++) {

      $form_state->set('key_change','key_change_'.$form_state->get('num_items_level_2'));

      $form['items_level_2']['item'.$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Item '.$i),
      ];

      $form['items_level_2']['item'.$i]['content_type_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Content type'),
        '#default_value' => isset($items_level_2['item'.$i]['content_type']) ? $items_level_2['item'.$i]['content_type'] : '',
        '#options' => $list_content_types,
      ];

      $form['items_level_2']['item'.$i]['type_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Type'),
        '#default_value' => isset($items_level_2['item'.$i]['type']) ? $items_level_2['item'.$i]['type'] : '',
        '#options' => $list_types,
        '#attributes' => [
          'id' => 'target_level2_'.$i,
        ],
      ];

      $form['items_level_2']['item'.$i]['autocomplete_'.$form_state->get('key_change')] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Item de menú'),
        '#target_type' => 'node',
        '#description' => $this->t('Indica el item de menú a controlar.'),
        '#default_value' => isset($items_level_2['item'.$i]['node']) ? Node::load($items_level_2['item'.$i]['node']) : '',
        '#states' => [
          'visible' => [
            ':input[id="target_level2_'.$i.'"]' => ['value' => 'no_sub'],
          ],
        ],
      ];

      $form['items_level_2']['item'.$i]['type_taxonomy_'.$form_state->get('key_change')] = [
        '#type' => 'select',
        '#title' => $this->t('Type taxonomy'),
        '#default_value' => isset($items_level_2['item'.$i]['type_taxonomy']) ? $items_level_2['item'.$i]['type_taxonomy'] : '',
        '#options' => $list_taxonomy_types,
        '#states' => [
          'visible' => [
            ':input[id="target_level2_'.$i.'"]' => ['value' => 'yes_sub'],
          ],
        ],
      ];

      if ($num_items_level_2 >= 2){
        $form['items_level_2']['item'.$i]['eliminar'.$i] = [
          '#type' => 'submit',
          '#value' => t('Eliminar'),
          '#name' => 'delete_lvl2-'.$i,
          '#submit' => [[$this, 'removeOneLevel2']],
          '#ajax' => [
            'callback' => [$this, 'removeOneLevel2Callback'],
            'wrapper' => 'items-level-2-wrapper',
          ]
        ];
      }
    }

    $form['items_level_2']['actions'] = [
      '#type' => 'actions',
    ];

    $form['items_level_2']['actions']['add_item'] = [
      '#type' => 'submit',
      '#value' => t('Añadir item'),
      '#submit' => [[$this, 'addMoreLevel2']],
      '#ajax' => [
        'callback' => [$this, 'addMoreLevel2Callback'],
        'wrapper' => 'items-level-2-wrapper',
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  //LEVEL1

  public function addMoreLevel1(array &$form, FormStateInterface $form_state) {
    $num_items_level_1 = $form_state->get('num_items_level_1');
    $add_button = $num_items_level_1 + 1;
    $items_temp_level_1 = [];
    for ($i = 1; $i <= $form_state->get('num_items_level_1'); $i++){
      $items_temp_level_1['item'.$i] = [
        'content_type' => $form_state->getValue(['items_level_1','item'.$i,'content_type_'.$form_state->get('key_change')]),
        'node' => $form_state->getValue(['items_level_1','item'.$i,'autocomplete_'.$form_state->get('key_change')])
      ];
    }
    $form_state->set('items_temp_level_1', $items_temp_level_1);
    $form_state->set('num_items_level_1', $add_button);
    $form_state->setRebuild();
  }

  public function addMoreLevel1Callback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['items_level_1'];
  }

  public function removeOneLevel1(array &$form, FormStateInterface $form_state) {
    $key_delete = $form_state->getTriggeringElement()['#name'];
    $num_items_level_1 = $form_state->get('num_items_level_1');
    $items_temp_level_1 = [];
    $item = 1;
    for ($i = 1; $i <= $form_state->get('num_items_level_1'); $i++){
      if ('delete_lvl1-'.$i == $key_delete){
        $item++;
      }
      $items_temp_level_1['item'.$i] = [
        'content_type' => $form_state->getValue(['items_level_1','item'.$item,'content_type_'.$form_state->get('key_change')]),
        'node' => $form_state->getValue(['items_level_1','item'.$item,'autocomplete_'.$form_state->get('key_change')]),
      ];
      $item++;
    }
    $form_state->set('items_temp_level_1', $items_temp_level_1);
    $remove_button = $num_items_level_1 - 1;
    $form_state->set('num_items_level_1', $remove_button);
    $form_state->setRebuild();
  }

  public function removeOneLevel1Callback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['items_level_1'];
  }

  //LEVEL2

  public function addMoreLevel2(array &$form, FormStateInterface $form_state) {
    $num_items_level_2 = $form_state->get('num_items_level_2');
    $add_button = $num_items_level_2 + 1;
    $items_temp_level_2 = [];
    for ($i = 1; $i <= $form_state->get('num_items_level_2'); $i++){
      $items_temp_level_2['item'.$i] = [
        'content_type' => $form_state->getValue(['items_level_2','item'.$i,'content_type_'.$form_state->get('key_change')]),
        'node' => $form_state->getValue(['items_level_2','item'.$i,'autocomplete_'.$form_state->get('key_change')])
      ];
    }
    $form_state->set('items_temp_level_2', $items_temp_level_2);
    $form_state->set('num_items_level_2', $add_button);
    $form_state->setRebuild();
  }

  public function addMoreLevel2Callback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['items_level_2'];
  }

  public function removeOneLevel2(array &$form, FormStateInterface $form_state) {
    $key_delete = $form_state->getTriggeringElement()['#name'];
    $num_items_level_2 = $form_state->get('num_items_level_2');
    $items_temp_level_2 = [];
    $item = 1;
    for ($i = 1; $i <= $form_state->get('num_items_level_2'); $i++){
      if ('delete_lvl2-'.$i == $key_delete){
        $item++;
      }
      $items_temp_level_2['item'.$i] = [
        'content_type' => $form_state->getValue(['items_level_2','item'.$item,'content_type_'.$form_state->get('key_change')]),
        'node' => $form_state->getValue(['items_level_2','item'.$item,'autocomplete_'.$form_state->get('key_change')]),
      ];
      $item++;
    }
    $form_state->set('items_temp_level_2', $items_temp_level_2);
    $remove_button = $num_items_level_2 - 1;
    $form_state->set('num_items_level_2', $remove_button);
    $form_state->setRebuild();
  }

  public function removeOneLevel2Callback(array &$form, FormStateInterface $form_state) {
    // The form passed here is the entire form, not the subform that is
    // passed to non-AJAX callback.
    return $form['items_level_2'];
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('consum_theme_blocks.settings')->set('menusettings.items_level_1', [])->save();

    for ($i = 1; $i <= $form_state->get('num_items'); $i++){
      $this->config('consum_theme_blocks.settings')->set('menusettings.items_level_1.items.item'.$i.'.content_type',
        $form_state->getValue(['items_level_1','item'.$i.'','content_type_'.$form_state->get('key_change')]))->save();
      $this->config('consum_theme_blocks.settings')->set('menusettings.items_level_1.items.item'.$i.'.node',
        $form_state->getValue(['items_level_1','item'.$i.'','autocomplete_'.$form_state->get('key_change')]))->save();
    }

    $this->config('consum_theme_blocks.settings')
      ->set('menusettings.content_types', $form_state->getValue('content_types'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}