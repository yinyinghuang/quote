<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductData Model
 *
 * @method \App\Model\Entity\ProductData get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductData newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductData[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductData|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductData|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductData patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductData[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductData findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductDataTable extends Table
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

        $this->setTable('product_data');
        $this->setDisplayField('product_id');
        $this->setPrimaryKey('product_id');
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
            ->integer('product_id')
            ->allowEmptyString('product_id', 'create')
            ->add('product_id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('view_count')
            ->allowEmptyString('view_count');

        $validator
            ->integer('collect_count')
            ->allowEmptyString('collect_count');

        $validator
            ->integer('comment_count')
            ->allowEmptyString('comment_count');

        $validator
            ->integer('quote_count')
            ->requirePresence('quote_count', 'create')
            ->allowEmptyString('quote_count', false);

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
        $rules->add($rules->isUnique(['product_id']));

        return $rules;
    }
}
