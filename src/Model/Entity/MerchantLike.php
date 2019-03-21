<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MerchantLike Entity
 *
 * @property int $merchant_id
 * @property int $fan_id
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \App\Model\Entity\Merchant $merchant
 * @property \App\Model\Entity\Fan $fan
 */
class MerchantLike extends Entity
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
        'merchant_id' => true,
        'fan_id' => true,
        'created' => true,
        'merchant' => true,
        'fan' => true
    ];
}
