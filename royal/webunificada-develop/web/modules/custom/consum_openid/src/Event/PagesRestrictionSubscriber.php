<?php

namespace Drupal\consum_openid\Event;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class PagesRestrictionSubscriber.
 *
 * @package Drupal\consum_openid
 */
class PagesRestrictionSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents()
  {
    $events[KernelEvents::REQUEST][] = ['onRequestExceptionPages', 215];

    return $events;
  }

  /**
   * On Request Check Restricted Pages.
   */
  public function onRequestExceptionPages(GetResponseEvent $event)
  {
    $requestUri = $event->getRequest()->getRequestUri();
    if (in_array($requestUri, ['/user/password'])) {
      $response = new RedirectResponse(Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString());
      $response->send();
      $event->stopPropagation();
    }

    $query_parameters = $event->getRequest()->query->all();
    if (!empty($query_parameters)) {
      if (!empty($query_parameters["error"]) && $query_parameters["error"] == 'login_required') {
        $headers = $event->getRequest()->headers->all();
        unset($_SESSION["session_state"]);
        $callbak_url = $headers['referer'][0] ? $headers['referer'][0] : Url::fromRoute('<front>');
        user_logout();

        $response = new RedirectResponse($callbak_url);
        $response->send();
        $event->stopPropagation();
      }
      if (!empty($query_parameters["session_state"])) {
        $_SESSION["session_state"] = $query_parameters["session_state"];
      }
    }

  }
}
