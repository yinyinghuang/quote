<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MerchantLikes Model
 *
 * @property \App\Model\Table\MerchantsTable|\Cake\ORM\Association\BelongsTo $Merchants
 * @property \App\Model\Table\FansTable|\Cake\ORM\Association\BelongsTo $Fans
 *
 * @method \App\Model\Entity\MerchantLike get($primaryKey, $options = [])
 * @method \App\Model\Entity\MerchantLike newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MerchantLike[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MerchantLike|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MerchantLike|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MerchantLike patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MerchantLike[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MerchantLike findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MerchantLikesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('merchant_likes');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Merchants', [
            'foreignKey' => 'merchant_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Fans', [
            'foreignKey' => 'fan_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['merchant_id'], 'Merchants'));
        $rules->add($rules->existsIn(['fan_id'], 'Fans'));

        return $rules;
    }
}
