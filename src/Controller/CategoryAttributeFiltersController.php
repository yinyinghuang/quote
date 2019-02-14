<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoryAttributeFilters Controller
 *
 * @property \App\Model\Table\CategoryAttributeFiltersTable $CategoryAttributeFilters
 *
 * @method \App\Model\Entity\CategoryAttributeFilter[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoryAttributeFiltersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['CategoryAttributes']
        ];
        $categoryAttributeFilters = $this->paginate($this->CategoryAttributeFilters);

        $this->set(compact('categoryAttributeFilters'));
    }

    /**
     * View method
     *
     * @param string|null $id Category Attribute Filter id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoryAttributeFilter = $this->CategoryAttributeFilters->get($id, [
            'contain' => ['CategoryAttributes']
        ]);

        $this->set('categoryAttributeFilter', $categoryAttributeFilter);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoryAttributeFilter = $this->CategoryAttributeFilters->newEntity();
        if ($this->request->is('post')) {
            $categoryAttributeFilter = $this->CategoryAttributeFilters->patchEntity($categoryAttributeFilter, $this->request->getData());
            if ($this->CategoryAttributeFilters->save($categoryAttributeFilter)) {
                $this->Flash->success(__('The category attribute filter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category attribute filter could not be saved. Please, try again.'));
        }
        $categoryAttributes = $this->CategoryAttributeFilters->CategoryAttributes->find('list', ['limit' => 200]);
        $this->set(compact('categoryAttributeFilter', 'categoryAttributes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category Attribute Filter id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoryAttributeFilter = $this->CategoryAttributeFilters->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoryAttributeFilter = $this->CategoryAttributeFilters->patchEntity($categoryAttributeFilter, $this->request->getData());
            if ($this->CategoryAttributeFilters->save($categoryAttributeFilter)) {
                $this->Flash->success(__('The category attribute filter has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category attribute filter could not be saved. Please, try again.'));
        }
        $categoryAttributes = $this->CategoryAttributeFilters->CategoryAttributes->find('list', ['limit' => 200]);
        $this->set(compact('categoryAttributeFilter', 'categoryAttributes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category Attribute Filter id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoryAttributeFilter = $this->CategoryAttributeFilters->get($id);
        if ($this->CategoryAttributeFilters->delete($categoryAttributeFilter)) {
            $this->Flash->success(__('The category attribute filter has been deleted.'));
        } else {
            $this->Flash->error(__('The category attribute filter could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
