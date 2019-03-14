<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProductData Entity
 *
 * @property int $product_id
 * @property int|null $view_count
 * @property int|null $collect_count
 * @property int|null $comment_count
 * @property int $quote_count
 */
class ProductData extends Entity
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
        'view_count' => true,
        'collect_count' => true,
        'comment_count' => true,
        'quote_count' => true
    ];
}
