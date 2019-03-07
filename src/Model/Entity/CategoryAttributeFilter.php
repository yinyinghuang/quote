<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CategoryAttributeFilter Entity
 *
 * @property int $id
 * @property int $pid
 * @property int $category_attribute_id
 * @property string $filter
 * @property bool $is_visible
 * @property int|null $sort
 *
 * @property \App\Model\Entity\CategoryAttribute $category_attribute
 */
class CategoryAttributeFilter extends Entity
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
        'category_attribute_id' => true,
        'filter' => true,
        'is_visible' => true,
        'sort' => true,
        'category_attribute' => true
    ];
}
