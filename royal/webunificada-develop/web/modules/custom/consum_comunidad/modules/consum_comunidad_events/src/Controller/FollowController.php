<?php

namespace Drupal\consum_comunidad_events\Controller;

/**
* @file
* Contains \Drupal\consum_comunidad_events\Controller\FollowControler.
*/

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;

/**
 * Defines FollowController class.
 */
class FollowController extends ControllerBase {

  /**
   * Follow function
   */
  public function follow($uid) {

    $current_user = User::load(\Drupal::currentUser()->id());
    $values = $current_user->get('field_siguiendo')->getValue();
    $values[count($values)]['target_id'] = $uid;
    $current_user->set('field_siguiendo', $values);
    $current_user->save();

    // Set color class icon ajax response
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#follow-' . $uid,'<a id="follow-'. $uid . '" class="boton2 btn-unfollow-user use-ajax" href="/consum-events/unfollow/' . $uid . '">Unfollow</a>'));
    $response->getContent();
    return $response;
  }

    /**
   * Follow function
   */
  public function unfollow($uid) {

    $current_user = User::load(\Drupal::currentUser()->id());
    $values = $current_user->get('field_siguiendo')->getValue();
    $new_values = [];

    foreach ($values as $delta => $value) {
      if ($value['target_id'] != $uid) {
        $new_values[$delta] = $value;
      }
    }

    $current_user->set('field_siguiendo', $new_values);
    $current_user->save();

    // Set color class icon ajax response
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('#follow-' . $uid,'<a id="follow-'. $uid . '" class="boton1 use-ajax" href="/consum-events/follow/' . $uid . '">follow</a>'));
    $response->getContent();
    return $response;
  }

}

