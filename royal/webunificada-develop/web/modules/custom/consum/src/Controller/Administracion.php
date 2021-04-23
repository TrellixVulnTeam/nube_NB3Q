<?php
/* comentario de prueba de Patricia */
namespace Drupal\consum\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * {@inheritDoc}
 */
class Administracion extends ControllerBase {

  /**
   * {@inheritDoc}
   */
  protected $currentUser;
  /**
   * {@inheritDoc}
   */
  protected $entityManager;

  /**
   * {@inheritDoc}
   */
  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_manager) {
    $this->currentUser = $current_user;
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function description() {

    /* En vez de realizar una salida con un markup, es decir, con html,
    pasamos la salida a un template que hemos realizado en /templates
    Además, paso una variable con el nombre test_var. */
    return [
      '#theme' => 'administracion',
      '#nombre_autor' => 'Autor reescrito por el controlador',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getUsers() {

    $build = [
      '#type' => 'markup',
      '#markup' => $this->t('<p>Bienvenido, Administrador</p>'),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getUser($uid) {

    // Get account name of current user from inyection service.
    $usuario_actual = $this->currentUser->getAccountName();

    // Get account name of user object from user storage.
    $account = $this->entityManager->getStorage('user')->load($uid);

    $name = $account->getAccountName();
    $content = '<p>Bienvenido, ' . $usuario_actual . '</p><p>El usuario que quieres editar es </p><h3>' . $name . '</h3>';

    return [
      '#type' => 'markup',
      '#markup' => $content,
    ];
  }

}
