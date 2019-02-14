<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MerchantLocation Entity
 *
 * @property int $id
 * @property int $pid
 * @property int $merchant_id
 * @property int $district_id
 * @property string|null $openhour
 * @property string|null $contact
 * @property string|null $address
 * @property string|null $latitude
 * @property string|null $longtitude
 * @property int|null $sort
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Merchant $merchant
 * @property \App\Model\Entity\District $district
 */
class MerchantLocation extends Entity
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
        'district_id' => true,
        'openhour' => true,
        'contact' => true,
        'address' => true,
        'latitude' => true,
        'longtitude' => true,
        'sort' => true,
        'created' => true,
        'modified' => true,
        'merchant' => true,
        'district' => true
    ];
}
