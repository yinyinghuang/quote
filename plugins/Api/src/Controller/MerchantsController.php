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
        $areas = $this->redis->read('area.list');
        if($areas) $this->ret(0, $areas, '加载成功');
        $areas = $this->loadModel('Areas')->find('all', [
            'fields'     => ['Areas.id', 'Areas.name'],
            'contain'    => ['Districts' => function ($query) {
                return $query->select(['Districts.name', 'Districts.id', 'Districts.area_id'])->where(['Districts.is_visible' => 1])->order(['Districts.sort']);
            }],
            'conditions' => ['Areas.is_visible' => 1],
            'order'      => ['Areas.sort'],
        ])
            ->toArray();
        $this->redis->write('area.list',$areas);
        $this->ret(0, $areas, '加载成功');
    }

    public function detail($id)
    {
        if (empty($id)) {
            $this->ret(1, null, '商户id缺失');
        }
        $merchant = $this->loadModel('Merchants')->find('all', [
            'conditions' => ['Merchants.id' => $id, 'Merchants.is_visible' => 1],
            'fields' => ['id','name','logo','logo_ext','wechat','email','website','intro'],
        ])->first();
        if (empty($merchant)) {
            $this->ret(1, null, '商户不存在或已被删除');
        }
        $fan_id  = $this->request->getData('pkey');
        $merchant->liked = $this->loadModel('MerchantLikes')->find('all', [
            'conditions' => ['merchant_id' => $merchant->id,'fan_id' => $fan_id],
        ])->count();
        $merchant->logos = $this->_getMerchantLogoUrl($merchant);
        $this->ret(0, $merchant, '产品加载成功');
    }
    public function locationLists($merchant_id)
    {
        if (empty($merchant_id)) {
            $this->ret(1, null, '商户id缺失');
        }
        $params = $this->request->getData();
        $fields = [
            'area_id'       => 'Areas.id',
            'area_name'     => 'Areas.name',
            'district_id'   => 'Districts.id',
            'district_name' => 'Districts.name',
            'openhour'      => 'MerchantLocations.openhour',
            'contact'       => 'MerchantLocations.contact',
            'address'       => 'MerchantLocations.address',
            'latitude'      => 'MerchantLocations.latitude',
            'longitude'     => 'MerchantLocations.longtitude',
        ];
        $conditions        = ['MerchantLocations.is_visible' => 1, 'MerchantLocations.merchant_id' => $merchant_id];
        $order             = ['MerchantLocations.sort desc','MerchantLocations.id desc',];
        $limit             = 20;
        $offset            = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $merchantLocations = $this->loadModel('MerchantLocations')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->leftJoinWith('Areas')
            ->leftJoinWith('Districts')
            ->toArray();
        $this->ret(0, $merchantLocations, '产品加载成功');
    }

    public function quoteLists($merchant_id)
    {
        if (empty($merchant_id)) {
            $this->ret(1, null, '商户id缺失');
        }

        $params = $this->request->getData();
        $fields = [
            'id'   => 'Products.id',
            'name' => 'Products.name',
            'album' => 'Products.album',
            'price_hong'   => 'Quotes.price_hong',
            'price_water'  => 'Quotes.price_water',
        ];
        $conditions = ['Quotes.is_visible' => 1, 'Quotes.merchant_id' => $merchant_id];
        $contain    = ['Products'];
        $order      = ['Quotes.sort desc', 'Products.sort desc', 'Quotes.id desc', 'Products.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        
        $quotes = $this->loadModel('Quotes')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->cover = $this->_getProductCover($row->id, $row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $quotes, '加载成功');
    }
    public function setLike($merchant_id)
    {
        if (empty($merchant_id)) {
            $this->ret(1, null, '商户id缺失');
        }
        $params     = $this->request->getData();
        $fan_id = $this->_getFanIdFormPkey($params['pkey']);
        $type       = $params['type'];
        $conditions = compact('merchant_id', 'fan_id');
        if ($type === 'dislike') {
            $this->loadModel('MerchantLikes')->deleteAll($conditions);
        } else {
            $like = $this->loadModel('MerchantLikes')->find('all')->where($conditions)->first();
            if (!$like) {
                $conditions['created'] = date('Y-m-d H:i:s');
                $this->loadModel('MerchantLikes')->query()->insert(['fan_id', 'merchant_id', 'created'])->values($conditions)->execute();
            }
        }
        $this->ret(0, 1, '加载成功');
    }
}
