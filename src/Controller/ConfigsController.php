<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Configs Controller
 *
 * @property \App\Model\Table\ConfigsTable $Configs
 *
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigsController extends AppController
{

    //
    public function index()
    {
        $comment_need_check = $this->Configs->findByName('comment_need_check')->first()->value;
        $this->set(compact('comment_need_check'));
    }
    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数pid缺失', '记录不存在或已删除', '内容填写有误', '图片保存失败'];

        $params         = $this->request->getData();
        $params['comment_need_check']                       = isset($params['comment_need_check']) ? $params['comment_need_check'] : 0;
        $this->Configs->query()->update()->set(['value' => $params['comment_need_check']])->where(['name' => 'comment_need_check'])->execute();
        $data = 0;
        $this->resApi($code, $data, $msg_arr[$data]);

    }

}
