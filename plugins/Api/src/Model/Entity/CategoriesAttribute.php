<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * CategoriesAttribute Entity
 *
 * @property int $id
 * @property int $category_id
 * @property int $attribute_id
 * @property int $level
 * @property string|null $unit
 * @property bool $is_filter
 * @property int $filter_type
 * @property int $is_visible
 * @property int|null $sort
 *
 * @property \Api\Model\Entity\Category $category
 * @property \Api\Model\Entity\Attribute $attribute
 */
class CategoriesAttribute extends Entity
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
        'attribute_id' => true,
        'level' => true,
        'unit' => true,
        'is_filter' => true,
        'filter_type' => true,
        'is_visible' => true,
        'sort' => true,
        'category' => true,
        'attribute' => true
    ];
}
