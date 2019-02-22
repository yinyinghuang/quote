<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Fans', 'Products']
        ];
        $comments = $this->paginate($this->Comments);

        $this->set(compact('comments'));
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['Fans', 'Products']
        ]);

        $this->set('comment', $comment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $comment = $this->Comments->newEntity();
        if ($this->request->is('post')) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comment could not be saved. Please, try again.'));
        }
        $fans = $this->Comments->Fans->find('list', ['limit' => 200]);
        $products = $this->Comments->Products->find('list', ['limit' => 200]);
        $this->set(compact('comment', 'fans', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comment could not be saved. Please, try again.'));
        }
        $fans = $this->Comments->Fans->find('list', ['limit' => 200]);
        $products = $this->Comments->Products->find('list', ['limit' => 200]);
        $this->set(compact('comment', 'fans', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'            => 'Quotes.id',
                'price_hong'    => 'Quotes.price_hong',
                'price_water'   => 'Quotes.price_water',
                'merchant_name' => 'Merchants.name',
                'merchant_id'   => 'Merchants.id',
                'modified'      => 'Quotes.modified',
                'sort'          => 'Quotes.sort',
            ];

            $contain = ['Merchants'];
            $where   = ['Quotes.product_id' => $this->request->getQuery('product_id')];
            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Merchants.id'] = intval($params['id']);
                }
                if (isset($params['merchant_name']) && trim($params['merchant_name'])) {
                    $where['Merchants.name like'] = '%' . trim($params['merchant_name']) . '%';
                }
                if (isset($params['price_hong_min']) && floatval($params['price_hong_min'])) {
                    $where['Quotes.price_hong >='] = floatval($params['price_hong_min']);
                }
                if (isset($params['price_hong_max']) && floatval($params['price_hong_max'])) {
                    $where['Quotes.price_hong <'] = floatval($params['price_hong_max']);
                }
                if (isset($params['price_water_min']) && floatval($params['price_water_min'])) {
                    $where['Quotes.price_water >='] = floatval($params['price_water_min']);
                }
                if (isset($params['price_water_max']) && floatval($params['price_water_max'])) {
                    $where['Quotes.price_water <'] = floatval($params['price_water_max']);
                }
                if (isset($params['district_id']) && intval($params['district_id'])) {
                    $merchants_id = $this->Quotes->Merchants->MerchantLocations->find('all', [
                        'conditions' => ['MerchantLocations.district_id' => $params['district_id']],
                        'fields'     => ['merchant_id'],
                        'group'      => ['merchant_id'],
                    ])
                        ->extract('merchant_id')
                        ->toArray();

                    empty($merchants_id) ? $where['1 !='] = 1 : $where['merchant_id in'] = $merchants_id;
                } elseif (isset($params['area_id']) && intval($params['area_id'])) {
                    $merchants_id = $this->Quotes->Merchants->MerchantLocations->find('all', [
                        'conditions' => ['MerchantLocations.area_id' => $params['area_id']],
                        'fields'     => ['merchant_id'],
                        'group'      => ['merchant_id'],
                    ])
                        ->extract('merchant_id')
                        ->toArray();

                    empty($merchants_id) ? $where['1 !='] = 1 : $where['merchant_id in'] = $merchants_id;
                }

            }
            $order = ['Quotes.sort' => 'desc', 'Quotes.modified' => 'desc', 'Quotes.id' => 'desc'];

            return [$fields, $where, $contain, $order];
        }, function () {
            $msg_arr = ['加载完成', '访问参数无pdt_id'];
            if (!$this->request->getQuery('product_id')) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        }, function ($row) {
            $row->modified = (new Time($row->modified))->i18nFormat('yyyy-MM-dd HH:mm:ss');
            return $row;
        });
    }
}
