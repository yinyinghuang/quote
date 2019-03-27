<?php

namespace Api\Controller;

use App\Controller\AppController as BaseController;
use Cake\Cache\Cache;
use Cake\Event\Event;

class AppController extends BaseController
{
    public function beforeFilter(Event $event)
    {
        $this->redis = new Cache;
        $this->userInfo = $this->getUInfo();
    }

    private function getUInfo()
    {
        $userInfo = [];
        $params = $this->request->getData();
        //在缓存中查找用户信息
        if(!empty($params['pkey'])){
            $userInfo = json_decode($this->redis->read($params['pkey']));
        }
        if(empty($userInfo)){
            //在缓存中查找openid
            if(!empty($params['pkey'])){
                $openid  = json_decode($this->redis->read('user.openid.'.$params['pkey']));
            }elseif(empty($params['pkey']) && !empty($params['code'])){
                $openid  = $this->getOpenid();
                $params['pkey'] = $this->setTokenId()['public_token_id'];
                $this->redis->write('user.openid.'.$params['pkey'],$openid );
            }
            if(!empty($openid)){
                //数据库中获取用户信息
                $userInfo = $this->getUserInfo($openid);
                $userInfo['pkey'] = $params['pkey'];
                $this->redis->write($params['pkey'],$userInfo );
            }             
        }
        $this->ret(1,$params);
        return $userInfo;
        
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
    }
    private function getUserInfo($openid)
    {
        $fan    = $this->Fans->find()->where(['openid' => $openid])->first();

        if ($fan) return $fan;
        $params = $this->request->getData();
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

    //返回结果
    protected function ret($errCode, $data, $errMsg='', $extra = [])
    {
        $this->autoRender = false;
        $res              = [
            'errCode' => $errCode,
            'data'    => $data,
            'errMsg'  => $errMsg,
        ] + $extra;
        die(json_encode($res));
    }
    //获取offset
    protected function getOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }
    //获取默认顺序
    protected function getDefaultOrder($controller)
    {
        return [$controller . '.sort desc', $controller . '.id desc'];
    }
    //获取产品封面
    protected function _getProductCover($product_id, $product_album)
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
    //获取产品图片列表
    protected function _getProductAlbumUrl($product_id, $product_album)
    {
        $albumDir = $this->_getAlbumDir($product_id);
        $albums   = [];
        if ($product_album) {
            foreach (json_decode($product_album, true) as $key => $album) {
                $albums[] = [
                    'thumb'  => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_2.' . $album[1],
                    'middle' => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_4.' . $album[1],
                    'full'   => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_0.' . $album[1],
                ];
            }
        }
        return $albums;
    }
    //获取产品图片文件夹
    protected function _getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
    //获取商户logo
    protected function _getMerchantLogoUrl($merchant)
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
    //获取商户logo文件夹
    protected function _getLogoDir($merchant_id)
    {
        return intval($merchant_id / 100) . '00' . '/';
    }
}
