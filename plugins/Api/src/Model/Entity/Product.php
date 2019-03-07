<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $pid
 * @property int $zone_id
 * @property int $group_id
 * @property int $category_id
 * @property string $brand
 * @property string $name
 * @property bool $is_new
 * @property bool $is_hot
 * @property float|null $price_hong_min
 * @property float|null $price_hong_max
 * @property float|null $price_water_min
 * @property float|null $price_water_max
 * @property string|null $caption
 * @property string|null $album
 * @property string|null $filter
 * @property float|null $rating
 * @property bool $is_visible
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Api\Model\Entity\Zone $zone
 * @property \Api\Model\Entity\Group $group
 * @property \Api\Model\Entity\Category $category
 * @property \Api\Model\Entity\Comment[] $comments
 * @property \Api\Model\Entity\Quote[] $quotes
 * @property \Api\Model\Entity\Attribute[] $attributes
 */
class Product extends Entity
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
        'group_id' => true,
        'category_id' => true,
        'brand' => true,
        'name' => true,
        'is_new' => true,
        'is_hot' => true,
        'price_hong_min' => true,
        'price_hong_max' => true,
        'price_water_min' => true,
        'price_water_max' => true,
        'caption' => true,
        'album' => true,
        'filter' => true,
        'rating' => true,
        'is_visible' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'zone' => true,
        'group' => true,
        'category' => true,
        'comments' => true,
        'quotes' => true,
        'attributes' => true
    ];
}
