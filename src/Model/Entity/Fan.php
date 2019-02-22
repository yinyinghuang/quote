<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Fan Entity
 *
 * @property int $id
 * @property string $openId
 * @property string $nickName
 * @property string $avatarUrl
 * @property int $gender
 * @property string|null $city
 * @property string|null $province
 * @property string|null $country
 * @property string|null $language
 * @property \Cake\I18n\FrozenTime|null $sign_up
 * @property \Cake\I18n\FrozenTime|null $last_access
 *
 * @property \App\Model\Entity\Comment[] $comments
 */
class Fan extends Entity
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
        'openId' => true,
        'nickName' => true,
        'avatarUrl' => true,
        'gender' => true,
        'city' => true,
        'province' => true,
        'country' => true,
        'language' => true,
        'sign_up' => true,
        'last_access' => true,
        'comments' => true
    ];
}
