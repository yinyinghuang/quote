<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\I18n\Time;

/**
 * Fans Controller
 *
 *
 * @method \Api\Model\Entity\Fan[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FansController extends AppController
{

    public function login()
    {
        $fan = $this->getUserInfo($this->request->getData());
        $this->ret(0, ['pkey' => $fan['pkey']], '登陆成功');

    }
    public function merchantLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params     = $this->request->getData();
        $fan        = $this->_getFanFormPkey($pkey);
        $pkey       = $fan['pkey'];
        $fields     = ['Merchants.id', 'Merchants.name', 'Merchants.logo', 'Merchants.logo_ext'];
        $conditions = ['Merchants.is_visible' => 1];

        $order        = ['Merchants.sort desc', 'Merchants.id desc'];
        $limit        = 20;
        $offset       = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $merchant_ids = $this->loadModel('MerchantLikes')->find('all', [
            'conditions' => ['fan_id' => $fan['id']],
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
        $this->ret(0, compact('merchants', 'pkey'), '加载成功');
    }
    //获取粉丝收藏的产品列表
    public function productLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params = $this->request->getData();
        $fan    = $this->_getFanFormPkey($pkey);
        $pkey   = $fan['pkey'];
        $fields = [
            'Products.id',
            'Products.name',
            'Products.album',
            'Products.price_hong_min',
            'Products.price_hong_max',
            'Products.price_water_min',
            'Products.price_water_max'];
        $conditions = [
            'Likes.fan_id'           => $fan['id'],
            'Likes.type'             => 1,
            'Products.is_visible'   => 1,
            'Categories.is_visible' => 1];
        $contain = ['Products' => 'Categories'];
        $order    = ['Likes.created desc', 'Products.sort desc', 'Products.id desc'];
        $limit    = 20;
        $offset   = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);
        $products = $this->loadModel('Likes')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->product->cover = $this->_getProductCover($row->product->id, $row->product->album);
                return $row;
            })
            ->extract('product')
            ->toArray();
        $this->ret(0, compact('products', 'pkey'), '加载成功');
    }

    public function commentLists($pkey)
    {
        if (empty($pkey)) {
            $this->ret(1, null, 'pkey缺失');
        }
        $params = $this->request->getData();
        $fan    = $this->_getFanFormPkey($pkey);
        $pkey   = $fan['pkey'];
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
        $conditions = ['fan_id' => $fan['id']];
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
        $this->ret(0, compact('comments', 'pkey'), '加载成功');
    }
}
