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
        $areas = $this->loadModel('Areas')->find('all', [
            'fields'     => ['Areas.id', 'Areas.name'],
            'contain'    => ['Districts' => function ($query) {
                return $query->select(['Districts.name', 'Districts.id', 'Districts.area_id'])->where(['Districts.is_visible' => 1])->order(['Districts.sort']);
            }],
            'conditions' => ['Areas.is_visible' => 1],
            'order'      => ['Areas.sort'],
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
                        'Merchants.price_hong_min >='  => $price_range[0],
                        'Merchants.price_water_min >=' => $price_range[0],
                    ];
                }
                if (!empty($price_range[1])) {
                    $where['or'] = [
                        'Merchants.price_hong_max >='  => $price_range[1],
                        'Merchants.price_water_max >=' => $price_range[1],
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
    private function _getMerchantLogoUrl($merchant)
    {
        $logos = [];
        if (empty($merchant->logo) || empty($merchant->logo_ext)) {
            return $logos;
        }

        $logoDir = $this->_getLogoDir($merchant->id);
        $logos   = [
            'thumb'  => 'album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_1.' . $merchant->logo_ext,
            'middle' => 'album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_2.' . $merchant->logo_ext,
            'full'   => 'album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_0.' . $merchant->logo_ext,
        ];
        return $logos;
    }
    //获取产品图片文件夹
    private function _getLogoDir($merchant_id)
    {
        return intval($merchant_id / 100) . '00' . '/';
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
    private function _getProductCover($product_id, $product_album)
    {

        $cover = '';
        if ($product_album) {
            $albumDir = $this->_getAlbumDir($product_id);
            $albums   = json_decode($product_album, true);
            if (count($albums)) {
                $album = $albums[0];
                $cover = 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_2.' . $album[1];
            }
        }
        return $cover;

    }
    //获取产品图片文件夹
    private function _getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
}
