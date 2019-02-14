<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductsAttributes Controller
 *
 * @property \App\Model\Table\ProductsAttributesTable $ProductsAttributes
 *
 * @method \App\Model\Entity\ProductsAttribute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsAttributesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'CategoryAttributes', 'Attributes']
        ];
        $productsAttributes = $this->paginate($this->ProductsAttributes);

        $this->set(compact('productsAttributes'));
    }

    /**
     * View method
     *
     * @param string|null $id Products Attribute id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productsAttribute = $this->ProductsAttributes->get($id, [
            'contain' => ['Products', 'CategoryAttributes', 'Attributes']
        ]);

        $this->set('productsAttribute', $productsAttribute);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productsAttribute = $this->ProductsAttributes->newEntity();
        if ($this->request->is('post')) {
            $productsAttribute = $this->ProductsAttributes->patchEntity($productsAttribute, $this->request->getData());
            if ($this->ProductsAttributes->save($productsAttribute)) {
                $this->Flash->success(__('The products attribute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The products attribute could not be saved. Please, try again.'));
        }
        $products = $this->ProductsAttributes->Products->find('list', ['limit' => 200]);
        $categoryAttributes = $this->ProductsAttributes->CategoryAttributes->find('list', ['limit' => 200]);
        $attributes = $this->ProductsAttributes->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('productsAttribute', 'products', 'categoryAttributes', 'attributes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Products Attribute id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productsAttribute = $this->ProductsAttributes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productsAttribute = $this->ProductsAttributes->patchEntity($productsAttribute, $this->request->getData());
            if ($this->ProductsAttributes->save($productsAttribute)) {
                $this->Flash->success(__('The products attribute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The products attribute could not be saved. Please, try again.'));
        }
        $products = $this->ProductsAttributes->Products->find('list', ['limit' => 200]);
        $categoryAttributes = $this->ProductsAttributes->CategoryAttributes->find('list', ['limit' => 200]);
        $attributes = $this->ProductsAttributes->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('productsAttribute', 'products', 'categoryAttributes', 'attributes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Products Attribute id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productsAttribute = $this->ProductsAttributes->get($id);
        if ($this->ProductsAttributes->delete($productsAttribute)) {
            $this->Flash->success(__('The products attribute has been deleted.'));
        } else {
            $this->Flash->error(__('The products attribute could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
