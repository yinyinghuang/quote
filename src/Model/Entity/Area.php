<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Area Entity
 *
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property int|null $sort
 *
 * @property \App\Model\Entity\District[] $districts
 */
class Area extends Entity
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
        'pid'        => true,
        'name'       => true,
        'is_visible' => true,
        'sort'       => true,
        'districts'  => true,
    ];
}
