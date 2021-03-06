<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\I18n\Time;

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
        if ($areas) {
            $this->ret(0, $areas, '加载成功');
        }

        $areas = $this->loadModel('Areas')->find('all', [
            'fields'     => ['Areas.id', 'Areas.name'],
            'contain'    => ['Districts' => function ($query) {
                return $query->select(['Districts.name', 'Districts.id', 'Districts.area_id'])->where(['Districts.is_visible' => 1])->order(['Districts.sort']);
            }],
            'conditions' => ['Areas.is_visible' => 1],
            'order'      => ['Areas.sort'],
        ])
            ->toArray();
        $this->redis->write('area.list', $areas);
        $this->ret(0, $areas, '加载成功');
    }

    public function detail($id)
    {
        if (empty($id)) {
            $this->ret(1, null, '商户id缺失');
        }
        $merchant = $this->loadModel('Merchants')->find('all', [
            'conditions' => ['Merchants.id' => $id, 'Merchants.is_visible' => 1],
            'fields'     => ['id', 'name', 'logo', 'logo_ext', 'wechat', 'email', 'website', 'intro'],
        ])->first();
        if (empty($merchant)) {
            $this->ret(1, null, '商户不存在或已被删除');
        }
        // $fan             = $this->_getFanFormPkey($this->request->getData('pkey'));
        $fan     = [];
        $pkey            = $fan['pkey'];
        $fan_id          = $fan['id'];
        $merchant->liked = $this->loadModel('Likes')->find('all', [
            'conditions' => ['foreign_id' => $merchant->id, 'fan_id' => $fan_id, 'type' => 2],
        ])->count();
        $merchant->logos = $this->_getMerchantLogoUrl($merchant);
        $this->ret(0, compact('merchant', 'pkey'), '产品加载成功');
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
        $order             = ['MerchantLocations.sort desc', 'MerchantLocations.id desc'];
        $limit             = 20;
        $offset            = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $count             = $this->loadModel('MerchantLocations')->find('all', compact('contain', 'conditions'))->count();
        $merchantLocations = $this->loadModel('MerchantLocations')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->leftJoinWith('Areas')
            ->leftJoinWith('Districts')
            ->toArray();
        $this->ret(0, ['count' => $count, 'list' => $merchantLocations], '产品加载成功');
    }

    public function quoteLists($merchant_id)
    {
        if (empty($merchant_id)) {
            $this->ret(1, null, '商户id缺失');
        }

        $params = $this->request->getData();
        $fields = [
            'id'          => 'Products.id',
            'name'        => 'Products.name',
            'album'       => 'Products.album',
            'price_hong'  => 'Quotes.price_hong',
            'price_water' => 'Quotes.price_water',
            'modified'    => 'Quotes.modified',
        ];
        $conditions = ['Quotes.is_visible' => 1, 'Quotes.merchant_id' => $merchant_id];
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $conditions['Products.name like'] = '%' . $params['keyword'] . '%';
        }
        $contain = ['Products'];
        $order   = ['Quotes.sort desc', 'Products.sort desc', 'Quotes.id desc', 'Products.id desc'];
        $limit   = 20;
        $offset  = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $count   = $this->loadModel('Quotes')->find('all', compact('contain', 'conditions'))->count();
        $quotes  = $this->loadModel('Quotes')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->cover    = $this->_getProductCover($row->id, $row->album);
                $row->modified = (new Time($row->modified))->i18nFormat('yyyy-MM-dd');
                return $row;
            })
            ->toArray();
        $this->ret(0, ['count' => $count, 'list' => $quotes], '加载成功');
    }
    public function setLike($merchant_id)
    {
        if (empty($merchant_id)) {
            $this->ret(1, null, '商户id缺失');
        }
        $params     = $this->request->getData();
        // $fan        = $this->_getFanFormPkey($params['pkey']);
        $fan     = [];
        $fan_id     = $fan['id'];
        $foreign_id = $merchant_id;
        $type       = 2;
        $action     = $params['type'];
        $conditions = compact('foreign_id', 'fan_id', 'type');
        if ($action === 'dislike') {
            $this->loadModel('Likes')->deleteAll($conditions);
        } else {
            $like = $this->loadModel('Likes')->find('all')->where($conditions)->first();
            if (!$like) {
                $conditions['created'] = date('Y-m-d H:i:s');
                $this->loadModel('Likes')->query()->insert(['fan_id', 'foreign_id', 'created', 'type'])->values($conditions)->execute();
            }
        }
        $this->ret(0, ['pkey' => $fan['pkey']], '加载成功');
    }
}
