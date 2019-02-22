<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Districts Controller
 *
 * @property \App\Model\Table\DistrictsTable $Districts
 *
 * @method \App\Model\Entity\District[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictsController extends AppController
{
    //添加
    public function add()
    {
        $district = $this->Districts->newEntity();

        $params  = $this->request->query();
        $area_id = null;
        if (isset($params['area_id']) && $params['area_id']) {
            $area = $this->Districts->Areas->find()->where(['id' => $params['area_id']])->first();
            if ($area) {
                $area_id = $area->id;
            }
        }
        $district->district_select = $this->getCasecadeTplParam('district_select', [
            'area'     => [
                'area_id' => $area_id,
                'disabled' => true,
            ],
            'district' => [
                'show'    => false,
                'options' => [],
            ],
        ], false);
        $this->set(compact('district'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数aid缺失', '记录不存在或已删除', '内容填写有误'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $district = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Districts->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Districts->newEntity();
        if (!$district) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $district = $this->Districts->patchEntity($district, $params);

        if (!$district->pid) {
            $district->pid = $this->getPid();
        }
        $data = $this->Districts->save($district) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($district->__debugInfo()['[errors]'] as $name => $error) {
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
        $data = ['code' => 3];
        $this->resApi(0, $data, $msg_arr[3]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Districts.id',
                'name'       => 'Districts.name',
                'is_visible' => 'Districts.is_visible',
                'sort'       => 'Districts.sort',
                'area_name'  => 'Areas.name',
                'area_id'    => 'Areas.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['area_id']) && intval($params['area_id'])) {
                    $where['Districts.area_id'] = intval($params['area_id']);
                }
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Districts.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Districts.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Districts.is_visible'] = $params['is_visible'];
                }
            }
            $contain = ['Areas'];

            $order = ['Districts.sort' => 'desc', 'Districts.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
