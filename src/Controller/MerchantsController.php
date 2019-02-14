<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Merchants Controller
 *
 * @property \App\Model\Table\MerchantsTable $Merchants
 *
 * @method \App\Model\Entity\Merchant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MerchantsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $merchants = $this->paginate($this->Merchants);

        $this->set(compact('merchants'));
    }

    /**
     * View method
     *
     * @param string|null $id Merchant id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $merchant = $this->Merchants->get($id, [
            'contain' => ['MerchantLocations', 'Quotes'],
        ]);

        $this->set('merchant', $merchant);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $merchant = $this->Merchants->newEntity();
        if ($this->request->is('post')) {
            $merchant = $this->Merchants->patchEntity($merchant, $this->request->getData());
            if ($this->Merchants->save($merchant)) {
                $this->Flash->success(__('The merchant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The merchant could not be saved. Please, try again.'));
        }
        $this->set(compact('merchant'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Merchant id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $merchant = $this->Merchants->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $merchant = $this->Merchants->patchEntity($merchant, $this->request->getData());
            if ($this->Merchants->save($merchant)) {
                $this->Flash->success(__('The merchant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The merchant could not be saved. Please, try again.'));
        }
        $this->set(compact('merchant'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Merchant id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $merchant = $this->Merchants->get($id);
        if ($this->Merchants->delete($merchant)) {
            $this->Flash->success(__('The merchant has been deleted.'));
        } else {
            $this->Flash->error(__('The merchant could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //返回联动多级分类
    //[
    //  selected_zone_id/selected_group_id/selected_category_id当前值
    //  zones/groups/categorys:select选项
    //]
    public function apiGetDistrictSelect()
    {
        $msg_arr = ['加载完成', '未选中', '记录不存在或已删除', 'type错误'];

        $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
        $params  = $this->request->$paramFn();

        $data = [
            'area'     => [
                'selected' => 0,
                'list'     => [],
            ],

            'district' => [
                'selected' => 0,
                'list'     => [],
            ],
        ];
        $this->loadModel('Districts');
        $this->loadModel('Areas');
        $data[$params['type']]['selected'] = $params['pid'];
        $data['area']['list']              = $this->loadModel('Areas')->find('list');
        $where_d                           = [];
        

        switch ($params['type']) {
            case 'area':
                if ($data['area']['selected']) {
                    $area = $this->Areas->find()->where(['id' => $data['area']['selected']])->first();
                    if (!$area) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $where_d = ['area_id' => $data['area']['selected']];
                }
                break;
            case 'district':
                if ($data['district']['selected']) {
                    $district = $this->Districts->find()->where(['id' => $data['district']['selected']])->first();
                    if (!$district) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $data['area']['selected'] = $district->area_id;

                } else {
                    $data['area']['selected'] = $params['origin']['area'];
                }
                if ($data['area']['selected']) {
                    $where_d = ['area_id' => $data['area']['selected']];
                }

                break;
            default:
                $this->resApi(0, 3, $msg_arr[3]);
        }

        $data['district']['list'] = $this->Districts->find('list')->where($where_d);
        $this->resApi(0, $data, $msg_arr[0]);
    }
}
