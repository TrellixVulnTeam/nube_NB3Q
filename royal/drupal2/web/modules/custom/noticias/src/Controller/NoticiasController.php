<?php

namespace Drupal\noticias\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class NoticiasController extends ControllerBase {

  public function description(){
    $url = Url::fromRoute('block.admin_display');
    $block_admin_links = this->l(this->'Página de administración de bloques', $url);

    $build = array(
      '#type' => 'markup',
      '#markup' => '<p>Noticias ofrece un bloque en el que se muestran noticias</p><p></p>',
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
