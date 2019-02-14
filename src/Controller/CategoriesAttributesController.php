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
}
