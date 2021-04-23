<?php

namespace Drupal\consum_iam_mdm\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for api_call entity.
 *
 * @ingroup consum_iam_mdm
 */
class ApiCallListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build= [];

    $build += parent::render();
    return $build;
  }

  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * @return array
   *   An array of entity IDs.
   */
  protected function getEntityIds() {
    $this->limit = 50;
    $query = $this->getStorage()->getQuery()
      ->sort($this->entityType->getKey('id'), 'DESC');

    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }

  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the contact list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('Api call ID');
    $header['name'] = $this->t('Name');
    $header['field_success'] = $this->t('Success');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\consum_iam_mdm\Entity\ApiCallEntity */
    $row['id'] = $entity->id();
    $row['name'] = $entity->link();
    $row['field_success'] = $entity->field_success->value;
    return $row + parent::buildRow($entity);
  }

}
