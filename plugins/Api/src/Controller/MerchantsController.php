<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * Merchants Controller
 *
 *
 * @method \Api\Model\Entity\Merchant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MerchantsController extends AppController
{

    public function areaLists()
    {
        $areas = $this->loadModel('Areas')->find('all',[
            'fields' => ['Areas.id','Areas.name'],
            'contain'=> ['Districts' => function($query){
                return $query->select(['Districts.name','Districts.id','Districts.area_id'])->where(['Districts.is_visible' => 1])->order(['Districts.sort']);
            }],
            'conditions' => ['Areas.is_visible' => 1],
            'order' => ['Areas.sort']
        ])
        ->toArray();
        $this->ret(0, $areas, '加载成功');
    }

    public function lists()
    {
        $params  = $this->request->getData();
        $select  = ['Merchants.id', 'Merchants.name', 'Merchants.album', 'Merchants.price_hong_min', 'Merchants.price_hong_max', 'Merchants.price_water_min', 'Merchants.price_water_max'];
        $where   = ['Merchants.is_visible' => 1, 'Categories.is_visible' => 1];
        $contain = ['Categories'];
        $order   = ['Merchants.sort desc', 'Merchants.id desc'];
        $limit   = 20;
        $offset  = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        if (isset($params['category_id']) && $params['category_id']) {
            $where['Merchants.category_id'] = $params['category_id'];
        }

        //最新更新
        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'last':
                    $order = ['Merchants.modified desc'] + $order;
                    break;
            }
        }
        //获取品牌
        if (isset($params['brand']) && !empty($params['brand'])) {
            $where['Merchants.brand'] = $params['brand'];
        }
        //获取价格
        if (isset($params['price']) && !empty($params['price'])) {
            $price_range = explode('-', $params['price']);
            if (count($price_range) === 2) {
                if (!empty($price_range[0])) {
                    $where['or'] = [
                        'Merchants.price_hong_min >=' => $price_range[0],
                        'Merchants.price_water_min >=' => $price_range[0]
                    ];
                }
                if (!empty($price_range[1])) {
                    $where['or'] = [
                        'Merchants.price_hong_max >=' => $price_range[1],
                        'Merchants.price_water_max >=' => $price_range[1]
                    ];
                }

            }

        }
        //获取筛选条件
        if (isset($params['filter']) && !empty($params['filter'])) {
            foreach ($params['filter'] as $filter) {
                $where[] = 'Merchants.filter LIKE "%' . $filter . ',%"';
            }
        }
        //获取排序
        if (isset($params['sort'])) {
            switch ($params['sort']) {
                case 'hotest':

                    break;

                default:
                    # code...
                    break;
            }
        }
        $merchants = $this->Merchants
            ->find()
            ->select($select)
            ->where($where)
            ->contain($contain)
            ->order($order)
            ->offset($offset)
            ->limit($limit)
            ->map(function ($row) {
                $row->cover = $this->_getProductCover($row->id, $row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $merchants, '加载成功');
    }
}
