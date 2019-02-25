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
    protected $appid = 'wx594d39c9d198444b';
    protected $secret = '3896265393bd937b00683664282f01f8';

    public function login()
    {
        $code = $this->request->getData('code');

        $this->sessionKey = $this->getSessionKey($code);
        
        if(array_key_exists('errcode',$this->sessionKey->json)){
            $this->ret(1,'',$this->sessionKey->json['errmsg']);                  
            
        }else{
            debug($this->sessionKey->json['errmsg']);
            die();
            $this->sessionKey = $this->sessionKey->json['session_key'];

            require_once(ROOT . DS  .'vendor' . DS . 'wxAes' . DS . 'wxBizDataCrypt.php');
            $bizDataCrypt = new \wxBizDataCrypt($this->appid, $this->sessionKey);
            $errCode = $bizDataCrypt->decryptData( $encryptedData, $iv, $data );

            if ($errCode) {
                $this->wxThrowError('decryptData',$errCode);
            }else{
                $data = json_decode($data,true);
                
                $fanTable = $this->loadModel('Fans');
                $fan = $fanTable->find()->where(['openId' => $data['openId']])->first() 
                    ? : $fanTable->newEntity();
                $fan = $fanTable->patchEntity($fan,$data);
                if($fanTable->save($fan)){
                    $response = [
                        'userInfo' => [
                            'nickName' => $data['nickName'],
                            'avatarUrl' => $data['avatarUrl'],
                            'userId' => $fan->id
                        ]
                    ];
                }else{
                    $this->wxThrowError('saveUser','failed');
                }
            }

        }
        $this->response->body(json_encode($response));
        
        return $this->response;
        die;
    }


    private function getSessionKey($jscode){
        $http = new Client();
        $jsonPayload = [
            'appid' => $this->appid,
            'secret' => $this->secret,
        ];
        $url = 'https://sz.api.weixin.qq.com/sns/jscode2session?js_code='.$jscode.'&grant_type=authorization_code';
        $response = $http->get($url,$jsonPayload,['type' => 'json']);
        return $response;
        die;                
    }
}
