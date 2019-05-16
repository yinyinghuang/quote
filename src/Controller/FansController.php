<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Fans Controller
 *
 * @property \App\Model\Table\FansTable $Fans
 *
 * @method \App\Model\Entity\Fan[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FansController extends AppController
{

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'fans',
            'renderUrl'   => '/fans/api-lists',
            'deleteUrl'   => '/fans/api-delete',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'avatarUrl\'', 'title' => '\'头像\'', 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'nickName\'', 'title' => '\'粉丝昵称\'', 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'city\'', 'title' => '\'城市\'', 'unresize' => true],
                ['field' => '\'sign_up\'', 'title' => '\'注册时间\'', 'unresize' => true],
                ['field' => '\'  last_access\'', 'title' => '\'上次访问\'', 'unresize' => true],
            ],
            'switchTpls'  => [
            ],
        ];

        $tableParams = ['fans' => $tableParams];
        $this->set(compact('table_fields', 'tableParams'));
    }

    //浏览
    public function view($id = null)
    {
        $fan = $this->Fans->get($id);

        $this->set('fan', $fan);
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数aid缺失', '记录不存在或已删除', '粉丝昵称已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $fan = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Fans->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Fans->newEntity();
        if (!$fan) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $fan = $this->Fans->patchEntity($fan, $params);

        $data = $this->Fans->save($fan) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($fan->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] = $name . ':' . implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }
    //ajax删除
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中', '暂不支持删除'];
        $this->allowMethod(['POST']);

        $ids = $this->request->getData('ids');
        if (count($ids) == 0) {
            $data = 2;
            $this->resApi(0, $data, $msg_arr[$res]);
        }
        //更新fans表
        $this->Fans->deleteAll(['id in' => $ids]);
        $data = ['code' => 3];
        $this->resApi(0, $data, $msg_arr[3]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'       => 'Fans.id',
                'nickName' => 'Fans.nickName',
                'city' => 'Fans.city',
                'sign_up' => 'Fans.sign_up',
                'last_access' => 'Fans.last_access',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Fans.id'] = intval($params['id']);
                }
                if (isset($params['nickName']) && trim($params['nickName'])) {
                    $where['Fans.nickName like'] = '%' . trim($params['nickName']) . '%';
                }
            }
            $contain = [];

            $order = ['Fans.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        },null,function($row){
            $row->last_access = (new Time($row->last_access))->i18nFormat('yyyy-MM-dd H:i:s');
            $row->sign_up = (new Time($row->sign_up))->i18nFormat('yyyy-MM-dd H:i:s');
        });
    }
}
