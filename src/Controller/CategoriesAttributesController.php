<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoriesAttributes Controller
 *
 * @property \App\Model\Table\CategoriesAttributesTable $CategoriesAttributes
 *
 * @method \App\Model\Entity\CategoriesAttribute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesAttributesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories', 'Attributes']
        ];
        $categoriesAttributes = $this->paginate($this->CategoriesAttributes);

        $autocompleteFields = [
            ['controller' => 'Categories', 'inputElem' => '#product_name', 'idElem' => '#product_id'],
        ];
        $this->set(compact('categoriesAttributes'));
    }

    /**
     * View method
     *
     * @param string|null $id Categories Attribute id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoriesAttribute = $this->CategoriesAttributes->get($id, [
            'contain' => ['Categories', 'Attributes']
        ]);

        $this->set('categoriesAttribute', $categoriesAttribute);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoriesAttribute = $this->CategoriesAttributes->newEntity();
        if ($this->request->is('post')) {
            $categoriesAttribute = $this->CategoriesAttributes->patchEntity($categoriesAttribute, $this->request->getData());
            if ($this->CategoriesAttributes->save($categoriesAttribute)) {
                $this->Flash->success(__('The categories attribute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories attribute could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesAttributes->Categories->find('list', ['limit' => 200]);
        $attributes = $this->CategoriesAttributes->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('categoriesAttribute', 'categories', 'attributes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Categories Attribute id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoriesAttribute = $this->CategoriesAttributes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoriesAttribute = $this->CategoriesAttributes->patchEntity($categoriesAttribute, $this->request->getData());
            if ($this->CategoriesAttributes->save($categoriesAttribute)) {
                $this->Flash->success(__('The categories attribute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories attribute could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesAttributes->Categories->find('list', ['limit' => 200]);
        $attributes = $this->CategoriesAttributes->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('categoriesAttribute', 'categories', 'attributes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Categories Attribute id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoriesAttribute = $this->CategoriesAttributes->get($id);
        if ($this->CategoriesAttributes->delete($categoriesAttribute)) {
            $this->Flash->success(__('The categories attribute has been deleted.'));
        } else {
            $this->Flash->error(__('The categories attribute could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'CategoriesAttributes.id',
                'name'       => 'Attributes.name',
                'attribute_id'       => 'Attributes.id',
                'attribute_name'       => 'Attributes.name',
                'is_visible' => 'CategoriesAttributes.is_visible',
                'is_filter' => 'CategoriesAttributes.is_filter',
                'sort'       => 'CategoriesAttributes.sort',
                'category_name' => 'Categories.name',
                'category_id' => 'Categories.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where   = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['CategoriesAttributes.id'] = intval($params['id']);
                }
                if (isset($params['attribute_name']) && trim($params['attribute_name'])) {
                    $where['Attributes.attribute_name like'] = '%' . trim($params['attribute_name']) . '%';
                }
                if (isset($params['category_id']) && intval($params['category_id'])) {
                    $where['CategoriesAttributes.category_id'] = intval($params['category_id']);
                }     
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1,0])) {
                    $where['CategoriesAttributes.is_visible'] = $params['is_visible'];
                }     
                if (isset($params['is_filter']) && in_array($params['is_filter'], [1,0])) {
                    $where['CategoriesAttributes.is_filter'] = $params['is_filter'];
                }     
                if (isset($params['fiter_type']) && in_array($params['fiter_type'], [1,2])) {
                    $where['CategoriesAttributes.fiter_type'] = $params['fiter_type'];
                }
            }
            $contain = ['Categories','Attributes'];

            $order = ['CategoriesAttributes.sort' => 'desc','CategoriesAttributes.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
