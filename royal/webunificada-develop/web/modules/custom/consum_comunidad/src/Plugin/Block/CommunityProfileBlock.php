<?php

namespace Drupal\consum_comunidad\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use Drupal\taxonomy\Entity\Term;

/**
 * Defines Community Profile block.
 *
 * @Block(
 *   id = "community_profile_block",
 *   admin_label = @Translation("Community Profile")
 * )
 */
class CommunityProfileBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function defaultConfiguration()
    {
        return parent::defaultConfiguration() + [
            'user' => [],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $account = User::load(\Drupal::currentUser()->id());

        for ($i = 0; $i < count($account->get("field_intereses")->referencedEntities()); $i++){
            $term = Term::load($account->get("field_intereses")[$i]->target_id);
            $name = $term->getName();
            $interests[$account->get("field_intereses")[$i]->target_id] = $name;
        }

        $points = $account->get("field_puntos")[count($account->get("field_puntos"))-1]->value;

        if (NULL != $account->user_picture->entity) {
            $file_uri = $account->user_picture->entity->getFileUri();
        }
        else {
            $default_user = User::load('1');
            $file_uri = $default_user->user_picture->entity->getFileUri();
        }

        $user = [
            "user_picture" => file_create_url($file_uri),
            "name" => $account->get("field_nombre")->value,
            "apellido1" => $account->get("field_apellido1")->value,
            "apellido2" => $account->get("field_apellido2")->value,
            "points" => $points,
            "view_facebook" => $account->get("field_ver_perfil_de_facebook")->value,
            "view_twitter" => $account->get("field_ver_perfil_de_twitter")->value,
            "view_instagram" => $account->get("field_ver_perfil_de_instagram")->value,
            "url_facebook" => $account->get("field_perfil_de_facebook")->value,
            "url_twitter" => $account->get("field_perfil_de_twitter")->value,
            "url_instagram" => $account->get("field_perfil_de_instagram")->value,
            "about_me" => $account->get("field_sobre_mi")->value,
            "interests" => $interests,
            "following" => count($account->get("field_siguiendo")->referencedEntities()),
            "medalla_hogar" => $account->get("field_medalla_hogar")->value,
            "medalla_participacion" => $account->get("field_medalla_participacion")->value,
            "medalla_recetas" => $account->get("field_medalla_recetas")->value,
            "medalla_nutricion" => $account->get("field_medalla_nutricion")->value,
            "medalla_vivir_viajar" => $account->get("field_medalla_vivir_viajar")->value,
            "medalla_bodega" => $account->get("field_medalla_bodega")->value,
            "medalla_cuidarnos" => $account->get("field_medalla_cuidarnos")->value,
        ];

        return [
            '#theme' => 'content_community_profile',
            '#user' => $user,
        ];
    }
}
