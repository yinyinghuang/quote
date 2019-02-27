<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Http\Client;

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
                $params      = json_decode($this->request->getData('user_msg_str'), true);
                $fan         = $this->Fans->patchEntity($fan, $params);
                $schema = $this->Fans->getSchema();               
                $data    = $fan->extract($this->Fans->getSchema()->columns(), true);
                $fan = $this->Fans->_insert($fan, $data);
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
}
