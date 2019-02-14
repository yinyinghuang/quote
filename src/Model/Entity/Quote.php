<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Quote Entity
 *
 * @property int $id
 * @property int $pid
 * @property int $merchant_id
 * @property int $product_id
 * @property float $price_hong
 * @property float $price_water
 * @property string|null $remark
 * @property int|null $sort
 * @property \Cake\I18n\FrozenDate|null $modified
 *
 * @property \App\Model\Entity\Merchant $merchant
 * @property \App\Model\Entity\Product $product
 */
class Quote extends Entity
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
        'merchant_id' => true,
        'product_id' => true,
        'price_hong' => true,
        'price_water' => true,
        'remark' => true,
        'sort' => true,
        'modified' => true,
        'merchant' => true,
        'product' => true
    ];
}
