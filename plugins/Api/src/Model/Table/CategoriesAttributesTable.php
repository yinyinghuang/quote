<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoriesAttributes Model
 *
 * @property \Api\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 * @property \Api\Model\Table\AttributesTable|\Cake\ORM\Association\BelongsTo $Attributes
 *
 * @method \Api\Model\Entity\CategoriesAttribute get($primaryKey, $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\CategoriesAttribute findOrCreate($search, callable $callback = null, $options = [])
 */
class CategoriesAttributesTable extends Table
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

        $this->setTable('categories_attributes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
            'className' => 'Api.Categories'
        ]);
        $this->belongsTo('Attributes', [
            'foreignKey' => 'attribute_id',
            'joinType' => 'INNER',
            'className' => 'Api.Attributes'
        ]);
        $this->hasMany('ProductsAttributes', [
            'foreignKey' => 'category_attribute_id',
            'className' => 'Api.ProductsAttributes'
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
            ->requirePresence('level', 'create')
            ->allowEmptyString('level', false);

        $validator
            ->scalar('unit')
            ->maxLength('unit', 10)
            ->allowEmptyString('unit');

        $validator
            ->boolean('is_filter')
            ->requirePresence('is_filter', 'create')
            ->allowEmptyString('is_filter', false);

        $validator
            ->requirePresence('filter_type', 'create')
            ->allowEmptyString('filter_type', false);

        $validator
            ->requirePresence('is_visible', 'create')
            ->allowEmptyString('is_visible', false);

        $validator
            ->allowEmptyString('sort');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));
        $rules->add($rules->existsIn(['attribute_id'], 'Attributes'));

        return $rules;
    }
}
