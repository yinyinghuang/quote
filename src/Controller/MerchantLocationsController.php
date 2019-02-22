<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * MerchantLocations Controller
 *
 * @property \App\Model\Table\MerchantLocationsTable $MerchantLocations
 *
 * @method \App\Model\Entity\MerchantLocation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MerchantLocationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Merchants', 'Districts'],
        ];
        $merchantLocations = $this->paginate($this->MerchantLocations);

        $this->set(compact('merchantLocations'));
    }

    /**
     * View method
     *
     * @param string|null $id Merchant Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $merchantLocation = $this->MerchantLocations->get($id, [
            'contain' => ['Merchants', 'Districts'],
        ]);

        $this->set('merchantLocation', $merchantLocation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $merchantLocation = $this->MerchantLocations->newEntity();
        if ($this->request->is('post')) {
            $merchantLocation = $this->MerchantLocations->patchEntity($merchantLocation, $this->request->getData());
            if ($this->MerchantLocations->save($merchantLocation)) {
                $this->Flash->success(__('The merchant location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The merchant location could not be saved. Please, try again.'));
        }
        $merchants = $this->MerchantLocations->Merchants->find('list', ['limit' => 200]);
        $districts = $this->MerchantLocations->Districts->find('list', ['limit' => 200]);
        $this->set(compact('merchantLocation', 'merchants', 'districts'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Merchant Location id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $merchantLocation = $this->MerchantLocations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $merchantLocation = $this->MerchantLocations->patchEntity($merchantLocation, $this->request->getData());
            if ($this->MerchantLocations->save($merchantLocation)) {
                $this->Flash->success(__('The merchant location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The merchant location could not be saved. Please, try again.'));
        }
        $merchants = $this->MerchantLocations->Merchants->find('list', ['limit' => 200]);
        $districts = $this->MerchantLocations->Districts->find('list', ['limit' => 200]);
        $this->set(compact('merchantLocation', 'merchants', 'districts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Merchant Location id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $merchantLocation = $this->MerchantLocations->get($id);
        if ($this->MerchantLocations->delete($merchantLocation)) {
            $this->Flash->success(__('The merchant location has been deleted.'));
        } else {
            $this->Flash->error(__('The merchant location could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'            => 'MerchantLocations.id',
                'merchant_name' => 'Merchants.name',
                'merchant_id'   => 'Merchants.id',
                'address'       => 'MerchantLocations.address',
                'location'      => 'CONCAT(MerchantLocations.latitude,",",MerchantLocations.longtitude)',
                'openhour'      => 'MerchantLocations.openhour',
                'contact'       => 'MerchantLocations.contact',
                'is_visible'    => 'MerchantLocations.is_visible',
                'sort'          => 'MerchantLocations.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();
            
            $where['MerchantLocations.merchant_id'] = intval($params['merchant_id']);
            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['MerchantLocations.id'] = intval($params['id']);
                }
                if (isset($params['merchant_id']) && intval($params['merchant_id'])) {
                    $where['MerchantLocations.merchant_id'] = intval($params['merchant_id']);
                }

                if (isset($params['district_id']) && intval($params['district_id'])) {
                    $where['MerchantLocations.district_id'] = intval($params['district_id']);
                } elseif (isset($params['area_id']) && intval($params['area_id'])) {
                    $where['MerchantLocations.area_id'] = intval($params['area_id']);
                }

                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['MerchantLocations.is_visible'] = $params['is_visible'];
                }
            }

            $contain = ['Merchants'];
            $order   = ['MerchantLocations.sort' => 'desc', 'MerchantLocations.modified' => 'desc', 'MerchantLocations.created' => 'desc', 'MerchantLocations.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, function () {
            $msg_arr = ['加载完成', '访问参数无m_id'];
            if (!$this->request->getQuery('merchant_id')) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        });
    }
}
