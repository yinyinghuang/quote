<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Quotes Controller
 *
 * @property \App\Model\Table\QuotesTable $Quotes
 *
 * @method \App\Model\Entity\Quote[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuotesController extends AppController
{

    //首页列表
    public function index()
    {
        $this->paginate = [
            'contain' => ['Merchants', 'Products'],
        ];
        $quotes = $this->paginate($this->Quotes);

        $this->set(compact('quotes'));
    }

    //浏览详情
    public function view($id = null)
    {
        $quote = $this->Quotes->get($id, [
            'contain' => ['Merchants', 'Products'],
        ]);
        $quote->product_name  = $quote->product->name;
        $quote->merchant_name = $quote->merchant->name;
        $this->set('quote', $quote);
    }

    //新增报价
    public function add()
    {
        $quote  = $this->Quotes->newEntity();
        $params = $this->request->query();

        //存在product_id
        if (isset($params['product_id'])) {
            $product = $this->Quotes->Products
                ->find()
                ->select(['id', 'name'])
                ->where(['id' => $params['product_id']])
                ->first();
            if ($product) {
                $quote->product_id   = $params['product_id'];
                $quote->product_name = $product->name;
            }
        }
        //存在merchant_id
        if (isset($params['merchant_id'])) {

            $merchant = $this->Quotes->Merchants
                ->find()
                ->select(['id', 'name'])
                ->where(['id' => $params['merchant_id']])
                ->first();
            if ($product) {
                $quote->merchant_id   = $params['merchant_id'];
                $quote->merchant_name = $merchant->name;
            }
        }
        $autocompleteFields = [
            ['controller' => 'Products', 'inputElem' => '#product_name', 'idElem' => '#product_id'],
            ['controller' => 'Merchants', 'inputElem' => '#merchant_name', 'idElem' => '#merchant_id'],
        ];
        $this->set(compact('quote', 'autocompleteFields'));
        $this->render('view');
    }

    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code           = 0;
        $msg_arr        = ['保存成功', '记录不存在或已删除', '内容填写有误'];
        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $quote = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Quotes->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Quotes->newEntity();

        if (!$quote) {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        $params = $this->request->getData();

        //详情编辑页面提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $quote = $this->Quotes->patchEntity($quote, $params);
        $isNew = $quote->isNew();
        $data  = $this->Quotes->save($quote) ? 0 : 2;
        //如报价为新增且保存成功，更新产品数据统计
        if($isNew && $data==0) $this->setProductMetaData($quote->product_id, ['quote_count' => 1]);
        $this->resApi($code, $data, $msg_arr[$data]);

    }

    //前端删除
    public function apiDelete()
    {
        $this->allowMethod(['POST']);
        $ids  = $this->request->getData('ids');
        if (count($ids)) {
            $code=0;
            //更新产品数据
            $quotes = $this->Quotes->find('all', [
                'conditions' => ['id in ' => $ids],
                'fields'     => ['id', 'product_id'],
            ])
                ->groupBy('product_id');
            foreach ($quotes as $product_id => $value) {
                $count = count($value);
                if ($count) {
                    $this->setProductMetaData($product_id, ['quote_count' => -($count)]);
                }
            }
            $this->Quotes->deleteAll(['id in' => $ids]);
        }else{
            $code=2;
        }

        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->resApi(0, compact('code', 'ids'), $msg_arr[$code]);
    }

    //ajax获取产品list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'            => 'Quotes.id',
                'price_hong'    => 'Quotes.price_hong',
                'price_water'   => 'Quotes.price_water',
                'merchant_name' => 'Merchants.name',
                'merchant_id'   => 'Merchants.id',
                'product_name'  => 'Products.name',
                'product_id'    => 'Products.id',
                'modified'      => 'Quotes.modified',
                'sort'          => 'Quotes.sort',
                'is_visible'    => 'Quotes.is_visible',
            ];

            $contain                                                                = ['Merchants','Products'];
            $this->request->getQuery('product_id') && $where['Quotes.product_id']   = $this->request->getQuery('product_id');
            $this->request->getQuery('merchant_id') && $where['Quotes.merchant_id'] = $this->request->getQuery('merchant_id');
            $paramFn                                                                = $this->request->is('get') ? 'getQuery' : 'getData';
            $params                                                                 = $this->request->$paramFn();
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
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Quotes.is_visible'] = $params['is_visible'];
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
            $msg_arr = ['加载完成', '访问参数无pid或mid'];
            if (!($this->request->getQuery('product_id') || $this->request->getQuery('merchant_id'))) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        }, function ($row) {
            $row->modified = (new Time($row->modified))->i18nFormat('yyyy-MM-dd HH:mm:ss');
            return $row;
        });
    }
}
