<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Fans Model
 *
 * @property \Api\Model\Table\CommentsTable|\Cake\ORM\Association\HasMany $Comments
 *
 * @method \Api\Model\Entity\Fan get($primaryKey, $options = [])
 * @method \Api\Model\Entity\Fan newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\Fan[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\Fan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Fan|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Fan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\Fan[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\Fan findOrCreate($search, callable $callback = null, $options = [])
 */
class FansTable extends Table
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

        $this->setTable('fans');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Comments', [
            'foreignKey' => 'fan_id',
            'className' => 'Api.Comments'
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
            ->scalar('openid')
            ->maxLength('openid', 50)
            ->requirePresence('openid', 'create')
            ->allowEmptyString('openid', false)
            ->add('openid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('nickName')
            ->maxLength('nickName', 255)
            ->requirePresence('nickName', 'create')
            ->allowEmptyString('nickName', false);

        $validator
            ->scalar('avatarUrl')
            ->maxLength('avatarUrl', 255)
            ->requirePresence('avatarUrl', 'create')
            ->allowEmptyString('avatarUrl', false);

        $validator
            ->requirePresence('gender', 'create')
            ->allowEmptyString('gender', false);

        $validator
            ->scalar('city')
            ->maxLength('city', 50)
            ->allowEmptyString('city');

        $validator
            ->scalar('province')
            ->maxLength('province', 100)
            ->allowEmptyString('province');

        $validator
            ->scalar('country')
            ->maxLength('country', 255)
            ->allowEmptyString('country');

        $validator
            ->scalar('language')
            ->maxLength('language', 255)
            ->allowEmptyString('language');

        $validator
            ->dateTime('sign_up')
            ->allowEmptyDateTime('sign_up');

        $validator
            ->dateTime('last_access')
            ->allowEmptyDateTime('last_access');

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
        $rules->add($rules->isUnique(['openid']));

        return $rules;
    }
}
