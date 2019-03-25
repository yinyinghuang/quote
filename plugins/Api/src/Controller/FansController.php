<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Http\Client;
use Cake\I18n\Time;

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
        $code = $this->request->getData('code');
        empty($code) && $this->ret(1, '', '缺少code');

        $this->sessionKey = $this->getSessionKey($code);
        if (array_key_exists('errcode', $this->sessionKey->json)) {
            $this->ret(2, '', $this->sessionKey->json['errmsg']);
        } else {
            $openid = $this->sessionKey->json['openid'];
            $fan    = $this->Fans->find()->where(['openid' => $openid])->first();

            if ($fan) {
                $this->ret(0, $fan->id, '登陆成功');
            } else {
                $fan         = $this->Fans->newEntity();
                $fan->openid = $openid;
                $fan->sign_up =(new Time($row->created))->i18nFormat('yyyy-MM-dd H:i:s');
                $params      = json_decode($this->request->getData('user_msg_str'), true);
                $fan         = $this->Fans->patchEntity($fan, $params);
                $schema      = $this->Fans->getSchema();
                $data        = $fan->extract($this->Fans->getSchema()->columns(), true);
                $fan         = $this->Fans->_insert($fan, $data);
                $this->ret(0, $fan->id, '注册成功');
                //以下方式保存数据，openid保存失败，原因未知
                // if ($this->Fans->save($fan)) {
                //     $this->ret(0, $fan->id, '注册成功');
                // } else {
                //     $msgs = [];
                //     foreach ($fan->__debugInfo()['[errors]'] as $name => $error) {
                //         $msgs[] = $name . ':' . implode(',', array_values($error));
                //     }
                //     $this->ret(3, $fan, implode(';', $msgs));
                // }
            }
        }
    }

    private function getSessionKey($jscode)
    {
        $http        = new Client();
        $jsonPayload = [
            'appid'  => $this->appid,
            'secret' => $this->secret,
        ];
        $url      = 'https://sz.api.weixin.qq.com/sns/jscode2session?js_code=' . $jscode . '&grant_type=authorization_code';
        $response = $http->get($url, $jsonPayload, ['type' => 'json']);
        return $response;
        die;
    }

    public function merchantLists($fan_id)
    {
        if (empty($fan_id)) {
            $this->ret(1, null, 'fan_id缺失');
        }
        $params     = $this->request->getData();
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
    public function productLists($fan_id)
    {
        if (empty($fan_id)) {
            $this->ret(1, null, 'fan_id缺失');
        }
        $params = $this->request->getData();
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

    public function commentLists($fan_id)
    {
        if (empty($fan_id)) {
            $this->ret(1, null, 'fan_id缺失');
        }
        $params = $this->request->getData();
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
