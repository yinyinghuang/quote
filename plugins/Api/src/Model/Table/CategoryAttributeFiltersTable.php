<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoryAttributeFilters Model
 *
 * @property \Api\Model\Table\CategoryAttributesTable|\Cake\ORM\Association\BelongsTo $CategoryAttributes
 *
 * @method \Api\Model\Entity\CategoryAttributeFilter get($primaryKey, $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\CategoryAttributeFilter findOrCreate($search, callable $callback = null, $options = [])
 */
class CategoryAttributeFiltersTable extends Table
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

        $this->setTable('category_attribute_filters');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('pid')
            ->requirePresence('pid', 'create')
            ->allowEmptyString('pid', false)
            ->add('pid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('filter')
            ->maxLength('filter', 255)
            ->requirePresence('filter', 'create')
            ->allowEmptyString('filter', false);

        $validator
            ->boolean('is_visible')
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
        $rules->add($rules->isUnique(['pid']));
        $rules->add($rules->existsIn(['category_attribute_id'], 'CategoriesAttributes'));

        return $rules;
    }
}
