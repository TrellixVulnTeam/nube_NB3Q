<?php

namespace Drupal\royal_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;


/**
 * Perform image saving from the wordpress site into the drupal filesystem and database.
 * This plugin is a standalone process triggered by a migration but it will not return any value.
 *
 * @MigrateProcessPlugin(
 *   id = "cuidarnos_manage_image"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: cuidarnos_manage_image
 *   source: not needed*
 * @endcode
 *
 */
class CuidarnosManageImage extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */


  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // We want to get several fields from the migration so we retrieve all.
    $row_data = $row->getSource();

    // Only importing attachments
    if ($row_data['post_type'] != 'attachment' || $row_data['post_parent'] == 0) {
      echo $row_data['field_wp_post_id'] . ' - This item is not an attachment.' . "\n";
      throw new MigrateSkipRowException('This item is not an attachment');
      return;
    }

    // Searching for the related node (post).
    $nodes = \Drupal::entityManager()
    ->getStorage('node')
    ->loadByProperties(['field_wp_post_id' => $row_data['post_parent']]);

    if (empty($nodes)) {
      echo $row_data['field_wp_post_id'] . ' - The parent of this item is not a post.' . "\n";
      throw new MigrateSkipRowException('The parent of this item is not a post');
      return;
    }

    // Creating the file with the same filename.
    $link_array = explode('/',$row_data['file']);
    $filename = end($link_array);
    $new_file = system_retrieve_file($row_data['file'],  "public://images/" . $filename , true);

    if(!$new_file) {
      echo $row_data['field_wp_post_id'] . ' - Error retrieving the file: ' . $row_data['file'] . "\n";
      throw new MigrateSkipRowException('Error retrieving the file');
      return;
    }

    // Relating the new created file to his corresponding node.
    $node = reset($nodes);

    $title = (!empty($row_data['title'])) ? $row_data['title']  : '';

    if ($row_data['field_wp_post_id'] == $node->field_wp_thumbnail_id) {
      $node->field_image[] = [
        'target_id' => $new_file->id(),
        'alt' => $title,
        'title' => $title,
      ];

      $node->save();

    } else {
      // $node->field_imagenes_interiores[] = [
      //   'target_id' => $new_file->id(),
      //   'alt' => $title,
      //   'title' => $title,
      // ];
    }



    return null ;
  }
}



