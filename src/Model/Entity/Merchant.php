<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Merchant Entity
 *
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string|null $openhour
 * @property string|null $logo
 * @property string|null $logo_ext
 * @property string|null $contact
 * @property string|null $wechat
 * @property string|null $email
 * @property string|null $website
 * @property string|null $intro
 * @property string|null $address
 * @property bool $is_visible
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\MerchantLocation[] $merchant_locations
 * @property \App\Model\Entity\Quote[] $quotes
 */
class Merchant extends Entity
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
        'openhour' => true,
        'logo' => true,
        'logo_ext' => true,
        'contact' => true,
        'wechat' => true,
        'email' => true,
        'website' => true,
        'intro' => true,
        'address' => true,
        'is_visible' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'merchant_locations' => true,
        'quotes' => true
    ];
}
