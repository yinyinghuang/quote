<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Merchants Model
 *
 * @property \App\Model\Table\MerchantLocationsTable|\Cake\ORM\Association\HasMany $MerchantLocations
 * @property \App\Model\Table\QuotesTable|\Cake\ORM\Association\HasMany $Quotes
 *
 * @method \App\Model\Entity\Merchant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Merchant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Merchant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Merchant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Merchant|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Merchant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Merchant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Merchant findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MerchantsTable extends Table
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

        $this->setTable('merchants');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('MerchantLocations', [
            'foreignKey' => 'merchant_id'
        ]);
        $this->hasMany('Quotes', [
            'foreignKey' => 'merchant_id'
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
            ->scalar('openhour')
            ->maxLength('openhour', 255)
            ->allowEmptyString('openhour');

        $validator
            ->scalar('logo')
            ->maxLength('logo', 50)
            ->allowEmptyString('logo');

        $validator
            ->scalar('logo_ext')
            ->maxLength('logo_ext', 10)
            ->allowEmptyString('logo_ext');

        $validator
            ->scalar('contact')
            ->maxLength('contact', 50)
            ->allowEmptyString('contact');

        $validator
            ->scalar('wechat')
            ->maxLength('wechat', 100)
            ->allowEmptyString('wechat');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('website')
            ->maxLength('website', 500)
            ->allowEmptyString('website');

        $validator
            ->scalar('intro')
            ->maxLength('intro', 1000)
            ->allowEmptyString('intro');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmptyString('address');

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['pid']));

        return $rules;
    }
}
