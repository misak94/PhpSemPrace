<?php

namespace App\Model\Entities;


use Dibi\DateTime;
use LeanMapper\Entity;

/**
 * Class Category
 * @package App\Model\Entities
 * @property int $commentId
 * @property int $postId
 * @property string $name
 * @property string $email
 * @property string $content
 * @property-read DateTime $created
 */
class Comments extends Entity
{

}