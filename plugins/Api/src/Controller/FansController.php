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
        $params = $this->request->getData();
        //在缓存中查找用户信息
        if(!empty($params['pkey'])){
            $userInfo = json_decode($this->redis->read($params['pkey']));
        }
        if(empty($userInfo)){
            //在缓存中查找openid
            if(!empty($params['pkey'])){
                $openid  = json_decode($this->redis->read('user.openid.'.$params['pkey']));
            }else{
                $openid  = $this->getOpenid();
                $params['pkey'] = $this->setTokenId()['public_token_id'];
                $this->redis->write('user.openid.'.$params['pkey'],$openid );
            }
            //数据库中获取用户信息
            $userInfo = $this->getUserInfo($openid);
            $userInfo['pkey'] = $params['pkey'];
            $this->redis->write($params['pkey'],$userInfo ); 
        }
        $this->ret(0,['pkey' => $params['pkey']]);
        
    }
    //获取openid
    private function getOpenid()
    {
        $code = $this->request->getData('code');
        empty($code) && $this->ret(1, '', '缺少code');
        $this->sessionKey = $this->getSessionKey($code);
        if (array_key_exists('errcode', $this->sessionKey->json)) {
            $this->ret(2, '', $this->sessionKey->json['errmsg']);
        } else {
            $openid = $this->sessionKey->json['openid'];      
            return $openid;      
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
    private function getUserInfo($openid)
    {
        $fan    = $this->Fans->find()->where(['openid' => $openid])->first();

        if ($fan) return $fan;
        $fan         = $this->Fans->newEntity();
        $fan->openid = $openid;
        $fan->sign_up =(new Time($row->created))->i18nFormat('yyyy-MM-dd H:i:s');
        $params      = json_decode($this->request->getData('user_msg_str'), true);
        $fan         = $this->Fans->patchEntity($fan, $params);
        $schema      = $this->Fans->getSchema();
        $data        = $fan->extract($this->Fans->getSchema()->columns(), true);
        $fan         = $this->Fans->_insert($fan, $data);
        
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
        return $fan;
    }
    private function setTokenId()
    {
        $token_salt = Configure::consume('Security.salt');
        $timestamp = time();//时间戳
        $nostr = self::setNostr();
        $arr = ["{$timestamp}", $nostr, $token_salt];
        sort($arr);

        $public_token_id = sha1(implode($arr));
        $real_token_id = md5($public_token_id . '@123' . $token_salt);

        $ret['public_token_id'] = $public_token_id;
        $ret['real_token_id'] = $real_token_id;

        return $ret;
    }    
    //生成随机字符串
    public static function setNostr($len = 12)
    {
        $nostr = mb_substr(md5(mb_substr(str_shuffle('0987YaTzxc23CvBVNbM456nSmkEjhA678guXoZqwpQrhRq24werWtyu2547iopsIdfgU76hPj'), 0, $len)), 0, $len); //随机字符串
        return $nostr;
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
