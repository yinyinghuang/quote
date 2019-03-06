<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductsAttributes Model
 *
 * @property \Api\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \Api\Model\Table\CategoryAttributesTable|\Cake\ORM\Association\BelongsTo $CategoryAttributes
 *
 * @method \Api\Model\Entity\ProductsAttribute get($primaryKey, $options = [])
 * @method \Api\Model\Entity\ProductsAttribute newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\ProductsAttribute[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\ProductsAttribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\ProductsAttribute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\ProductsAttribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\ProductsAttribute[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\ProductsAttribute findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductsAttributesTable extends Table
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

        $this->setTable('products_attributes');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
            'className' => 'Api.Products'
        ]);
        $this->belongsTo('CategoriesAttributes', [
            'foreignKey' => 'category_attribute_id',
            'joinType' => 'INNER',
            'className' => 'Api.CategoriesAttributes'
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
            ->scalar('value')
            ->maxLength('value', 255)
            ->requirePresence('value', 'create')
            ->allowEmptyString('value', false);

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['category_attribute_id'], 'CategoriesAttributes'));

        return $rules;
    }
}
