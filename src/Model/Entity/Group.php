<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Group Entity
 *
 * @property int $id
 * @property int $pid
 * @property int $zone_id
 * @property string $name
 * @property bool $is_visible
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Zone $zone
 * @property \App\Model\Entity\Category[] $categories
 * @property \App\Model\Entity\Product[] $products
 */
class Group extends Entity
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
        'pid' => true,
        'zone_id' => true,
        'name' => true,
        'is_visible' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'zone' => true,
        'categories' => true,
        'products' => true
    ];
}
