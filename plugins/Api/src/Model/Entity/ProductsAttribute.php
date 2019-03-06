<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductsAttribute Entity
 *
 * @property int $product_id
 * @property int $category_attribute_id
 * @property string $value
 *
 * @property \Api\Model\Entity\Product $product
 * @property \Api\Model\Entity\CategoryAttribute $category_attribute
 */
class ProductsAttribute extends Entity
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
        'product_id' => true,
        'category_attribute_id' => true,
        'value' => true,
        'product' => true,
        'category_attribute' => true
    ];
}
