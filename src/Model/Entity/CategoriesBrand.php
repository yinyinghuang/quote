<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CategoriesBrand Entity
 *
 * @property int $category_id
 * @property string $brand
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Category $category
 */
class CategoriesBrand extends Entity
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
        'category_id' => true,
        'brand' => true,
        'created' => true,
        'category' => true
    ];
}
