<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attributes Model
 *
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsToMany $Products
 *
 * @method \App\Model\Entity\Attribute get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attribute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Attribute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attribute|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attribute findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttributesTable extends Table
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

        $this->setTable('attributes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Categories', [
            'foreignKey' => 'attribute_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'categories_attributes'
        ]);
        $this->belongsToMany('Products', [
            'foreignKey' => 'attribute_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'products_attributes'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false)
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['name']));

        return $rules;
    }
}
