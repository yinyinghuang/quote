<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Quotes Model
 *
 * @property \App\Model\Table\MerchantsTable|\Cake\ORM\Association\BelongsTo $Merchants
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 *
 * @method \App\Model\Entity\Quote get($primaryKey, $options = [])
 * @method \App\Model\Entity\Quote newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Quote[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Quote|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Quote|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Quote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Quote[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Quote findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuotesTable extends Table
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

        $this->setTable('quotes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Merchants', [
            'foreignKey' => 'merchant_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->decimal('price_hong')
            ->requirePresence('price_hong', 'create')
            ->allowEmptyString('price_hong');

        $validator
            ->decimal('price_water')
            ->requirePresence('price_water', 'create')
            ->allowEmptyString('price_water');

        $validator
            ->scalar('remark')
            ->allowEmptyString('remark');

        $validator
            ->boolean('is_visible')
            ->requirePresence('is_visible', 'create')
            ->allowEmptyString('is_visible', false);

        $validator
            ->allowEmptyString('sort');

        $validator
            ->integer('record')
            ->allowEmptyString('record');

        return $validator;
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
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
