<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Zone Entity
 *
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property bool $is_visible
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Group[] $groups
 * @property \App\Model\Entity\Product[] $products
 */
class Zone extends Entity
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
        'name' => true,
        'is_visible' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'groups' => true,
        'products' => true
    ];
}
