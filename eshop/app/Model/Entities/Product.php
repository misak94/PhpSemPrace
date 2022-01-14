<?php

namespace App\Model\Entities;

use LeanMapper\Entity;

/**
 * Class Product
 * @package App\Model\Entities
 * @property int $productId
 * @property string $title
 * @property string $url
 * @property string $description
 * @property float $price
 * @property string $photoExtension = ''
 * @property int $available
 * @property Category|null $category m:hasOne
 * @property Comments[] $comments m:belongsToMany
 */
class Product extends Entity implements \Nette\Security\Resource{

  /**
   * @inheritDoc
   */
  function getResourceId():string{
    return 'Product';
  }
}