<?php

namespace Drupal\royal_migrate\Plugin\migrate\process;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Perform content transformation from the wordpress source.
 *
 * @MigrateProcessPlugin(
 *   id = "cuidarnos_manage_content"
 * )
 *
 * Usage:
 *
 * @code
 * field:
 *   plugin: cuidarnos_manage_content
 *   source: field
 * @endcode
 *
 */
class CuidarnosManageContent extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $string = $value;

    // This regular exp. will get every image link in the content
    preg_match_all('#https?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/)\.(jpg|jpeg|png))#', $string, $match);

    // The 0 index is an array with the strings matching the regex.
    if (!empty($match[0])) {
      foreach ($match[0] as $img) {
        // Using a custom function to transform every wordpress url to the new drupal realtive url.
        $value = str_replace($img, $this->replaceImageUrl($img), $value);
      }
    }

    return $value ;
  }

  // Replaces the input url with a new drupal one, keeping the filename
  protected function replaceImageUrl($url) {
    if (!empty($url)) {
      $link_array = explode('/',$url);
      $filename = end($link_array);
      $new_url = "/sites/default/files/images/" . $filename;
      return $new_url;
    } else {
      return '';
    }

  }
}
