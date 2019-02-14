<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoriesBrands Controller
 *
 * @property \App\Model\Table\CategoriesBrandsTable $CategoriesBrands
 *
 * @method \App\Model\Entity\CategoriesBrand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesBrandsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories', 'Brands']
        ];
        $categoriesBrands = $this->paginate($this->CategoriesBrands);

        $this->set(compact('categoriesBrands'));
    }

    /**
     * View method
     *
     * @param string|null $id Categories Brand id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoriesBrand = $this->CategoriesBrands->get($id, [
            'contain' => ['Categories', 'Brands']
        ]);

        $this->set('categoriesBrand', $categoriesBrand);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoriesBrand = $this->CategoriesBrands->newEntity();
        if ($this->request->is('post')) {
            $categoriesBrand = $this->CategoriesBrands->patchEntity($categoriesBrand, $this->request->getData());
            if ($this->CategoriesBrands->save($categoriesBrand)) {
                $this->Flash->success(__('The categories brand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories brand could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesBrands->Categories->find('list', ['limit' => 200]);
        $brands = $this->CategoriesBrands->Brands->find('list', ['limit' => 200]);
        $this->set(compact('categoriesBrand', 'categories', 'brands'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Categories Brand id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoriesBrand = $this->CategoriesBrands->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoriesBrand = $this->CategoriesBrands->patchEntity($categoriesBrand, $this->request->getData());
            if ($this->CategoriesBrands->save($categoriesBrand)) {
                $this->Flash->success(__('The categories brand has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories brand could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesBrands->Categories->find('list', ['limit' => 200]);
        $brands = $this->CategoriesBrands->Brands->find('list', ['limit' => 200]);
        $this->set(compact('categoriesBrand', 'categories', 'brands'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Categories Brand id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoriesBrand = $this->CategoriesBrands->get($id);
        if ($this->CategoriesBrands->delete($categoriesBrand)) {
            $this->Flash->success(__('The categories brand has been deleted.'));
        } else {
            $this->Flash->error(__('The categories brand could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
