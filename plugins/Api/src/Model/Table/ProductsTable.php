<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \Api\Model\Table\ZonesTable|\Cake\ORM\Association\BelongsTo $Zones
 * @property \Api\Model\Table\GroupsTable|\Cake\ORM\Association\BelongsTo $Groups
 * @property \Api\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 * @property \Api\Model\Table\CommentsTable|\Cake\ORM\Association\HasMany $Comments
 * @property \Api\Model\Table\QuotesTable|\Cake\ORM\Association\HasMany $Quotes
 * @property \Api\Model\Table\AttributesTable|\Cake\ORM\Association\BelongsToMany $Attributes
 *
 * @method \Api\Model\Entity\Product get($primaryKey, $options = [])
 * @method \Api\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Product|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsTable extends Table
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

        $this->setTable('products');
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
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
            'className' => 'Api.Categories'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'product_id',
            'className' => 'Api.Comments'
        ]);
        $this->hasMany('Quotes', [
            'foreignKey' => 'product_id',
            'className' => 'Api.Quotes'
        ]);
        $this->belongsToMany('Attributes', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'attribute_id',
            'joinTable' => 'products_attributes',
            'className' => 'Api.Attributes'
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
            ->scalar('brand')
            ->maxLength('brand', 255)
            ->requirePresence('brand', 'create')
            ->allowEmptyString('brand', false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->boolean('is_new')
            ->requirePresence('is_new', 'create')
            ->allowEmptyString('is_new', false);

        $validator
            ->boolean('is_hot')
            ->requirePresence('is_hot', 'create')
            ->allowEmptyString('is_hot', false);

        $validator
            ->decimal('price_hong_min')
            ->allowEmptyString('price_hong_min');

        $validator
            ->decimal('price_hong_max')
            ->allowEmptyString('price_hong_max');

        $validator
            ->decimal('price_water_min')
            ->allowEmptyString('price_water_min');

        $validator
            ->decimal('price_water_max')
            ->allowEmptyString('price_water_max');

        $validator
            ->scalar('caption')
            ->maxLength('caption', 255)
            ->allowEmptyString('caption');

        $validator
            ->scalar('album')
            ->maxLength('album', 255)
            ->allowEmptyString('album');

        $validator
            ->scalar('filter')
            ->allowEmptyString('filter');

        $validator
            ->decimal('rating')
            ->allowEmptyString('rating');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
