<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Keyword Entity
 *
 * @property int $id
 * @property string $name
 * @property int $count
 * @property bool $is_visible
 * @property int|null $sort
 */
class Keyword extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'count' => true,
        'is_visible' => true,
        'sort' => true
    ];
}
