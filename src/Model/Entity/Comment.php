<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $product_id
 * @property int $fan_id
 * @property int $rating
 * @property string $content
 * @property int $is_checked
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Fan $fan
 * @property \App\Model\Entity\Product $product
 */
class Comment extends Entity
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
        'fan_id' => true,
        'rating' => true,
        'content' => true,
        'is_checked' => true,
        'sort' => true,
        'created' => true,
        'fan' => true,
        'product' => true
    ];
}
