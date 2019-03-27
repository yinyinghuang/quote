<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Http\Client;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * Fans Controller
 *
 *
 * @method \Api\Model\Entity\Fan[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FansController extends AppController
{
    protected $appid  = 'wx594d39c9d198444b';
    protected $secret = '3896265393bd937b00683664282f01f8';

    public function login()
    {
        $userInfo =$this->userInfo;
        $this->ret(1,$this->request->getData());
        $this->ret(0,['pkey' => $userInfo['pkey']]);
        
    }
    public function merchantLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params = $this->request->getData();
        $fan_id = $this->redis->read($pkey)['id'];
        $fields     = ['Merchants.id', 'Merchants.name', 'Merchants.logo', 'Merchants.logo_ext'];
        $conditions = ['Merchants.is_visible' => 1];

        $order        = ['Merchants.sort desc', 'Merchants.id desc'];
        $limit        = 20;
        $offset       = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $merchant_ids = $this->loadModel('MerchantLikes')->find('all', [
            'conditions' => ['fan_id' => $fan_id],
        ])->extract('merchant_id')->toArray();
        if (empty($merchant_ids)) {
            $conditions = ['1!=1'];
        } else {
            $conditions['Merchants.id in'] = $merchant_ids;
        }
        $merchants = $this->loadModel('Merchants')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->logos = $this->_getMerchantLogoUrl($row);
                $conditions = ['merchant_id' => $row->id, 'address is not null'];
                $location   = $this->loadModel('MerchantLocations')->find('all', [
                    'conditions' => $conditions,
                ])->first();
                if ($location) {
                    $row->address                            = $location->address;
                    $location->latitude && $row->latitude    = $location->latitude;
                    $location->longtitude && $row->longitude = $location->longtitude;
                }
                return $row;
            })
            ->toArray();
        $this->ret(0, $merchants, '加载成功');
    }
    public function productLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params = $this->request->getData();
        $fan_id = $this->redis->read($pkey)['id'];
        $fields = [
            'Products.id',
            'Products.name',
            'Products.album',
            'Products.price_hong_min',
            'Products.price_hong_max',
            'Products.price_water_min',
            'Products.price_water_max',
        ];
        $conditions = ['Products.is_visible' => 1, 'Categories.is_visible' => 1];
        $contain    = ['Categories'];
        $order      = ['Products.sort desc', 'Products.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        //获取粉丝收藏的产品列表
        $product_ids = $this->loadModel('Likes')->find('all', [
            'conditions' => ['fan_id' => $fan_id],
        ])->extract('product_id')->toArray();
        if (empty($product_ids)) {
            $conditions = ['1!=1'];
        } else {
            $conditions['Products.id in'] = $product_ids;
        }
        $products = $this->loadModel('Products')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->cover = $this->_getProductCover($row->id, $row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $products, '加载成功');
    }

    public function commentLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params = $this->request->getData();
        $fan_id = $this->redis->read($pkey)['id'];
        $fields = [
            'id'              => 'Products.id',
            'name'            => 'Products.name',
            'album'           => 'Products.album',
            'price_hong_min'  => 'Products.price_hong_min',
            'price_hong_max'  => 'Products.price_hong_max',
            'price_water_min' => 'Products.price_water_min',
            'price_water_max' => 'Products.price_water_max',
            'created'         => 'Comments.created',
            'rating'          => 'Comments.rating',
            'content'         => 'Comments.content',
            'is_checked'      => 'Comments.is_checked',
        ];
        $conditions = ['fan_id' => $fan_id];
        $contain    = ['Products'];
        $order      = ['Comments.created desc', 'Comments.sort desc', 'Comments.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        $comments = $this->loadModel('Comments')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->cover   = $this->_getProductCover($row->id, $row->album);
                $row->created = (new Time($row->created))->i18nFormat('yyyy-MM-dd');
                return $row;
            })
            ->toArray();
        $this->ret(0, $comments, '加载成功');
    }
}
