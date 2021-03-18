<?php

namespace Drupal\noticias\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class NoticiasController extends ControllerBase {

  public function description(){

    $url = Url::fromRoute('block.admin_display');
    $block_admin_link = $this->l($this->t('Página de administración de bloques'), $url);

    $build = array(
      '#type' => 'markup',
      '#markup' => $this->t('<p>Noticias ofrece un bloque en el que se muestran noticias' . '</p><p>!block_admin_link</p>', ['!block_admin_link'=> $block_admin_link]),
    );

    return $build;
  }

  public function getNoticia($idNoticia, $vista){

    $build = array(
      '#type' => 'markup',
      '#markup' => '<p>Esta es la página que muestra noticias con la noticia id <strong>' . $idNoticia . '</strong> con la vista <strong>'. $vista . '</strong></p>',
    );

    return $build;
  }
}
