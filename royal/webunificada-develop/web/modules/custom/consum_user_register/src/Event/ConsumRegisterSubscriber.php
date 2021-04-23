<?php

namespace Drupal\consum_user_register\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ConsumRegisterSubscriber.
 *
 * @package Drupal\consum_user_register
 */
class ConsumRegisterSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents()
  {
    $events[KernelEvents::REQUEST][] = ['onRequestRegisterRedirectPages'];

    return $events;
  }

  /**
   * On Request Check Restricted Pages.
   */
  public function onRequestRegisterRedirectPages(GetResponseEvent $event)
  {
    $requestUri = $event->getRequest()->getRequestUri();
    if (in_array($requestUri, ['/user/register'])) {
      $response = new RedirectResponse('/user/consum-register');
      $response->send();
      $event->stopPropagation();
    }
  }
}
