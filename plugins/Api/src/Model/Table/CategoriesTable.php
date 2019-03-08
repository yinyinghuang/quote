<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @property \Api\Model\Table\ZonesTable|\Cake\ORM\Association\BelongsTo $Zones
 * @property \Api\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \Api\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 * @property \Api\Model\Table\AttributesTable|\Cake\ORM\Association\BelongsToMany $Attributes
 * @property \Api\Model\Table\BrandsTable|\Cake\ORM\Association\BelongsToMany $Brands
 *
 * @method \Api\Model\Entity\Category get($primaryKey, $options = [])
 * @method \Api\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Category|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\Category findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Zones', [
            'foreignKey' => 'zone_id',
            'joinType' => 'INNER',
            'className' => 'Api.Zones'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER',
            'className' => 'Api.Groups'
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'category_id',
            'className' => 'Api.Products'
        ]);
        $this->hasMany('CategoriesAttributes', [
            'foreignKey' => 'category_id',
            'className' => 'Api.CategoriesAttributes'
        ]);
        $this->belongsToMany('Brands', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'brand_id',
            'joinTable' => 'categories_brands',
            'className' => 'Api.Brands'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

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
        $rules->add($rules->existsIn(['zone_id'], 'Zones'));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));

        return $rules;
    }
}
