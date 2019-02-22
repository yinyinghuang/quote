<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Areas Controller
 *
 * @property \App\Model\Table\AreasTable $Areas
 *
 * @method \App\Model\Entity\Area[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AreasController extends AppController
{

    //列表
    public function index()
    {
        $tableParams = [
            'name'        => 'areas',
            'renderUrl'   => '/areas/api-lists',
            'deleteUrl'   => '/areas/api-delete',
            'editUrl'     => '/areas/api-save',
            'addUrl'      => '/areas/add',
            'viewUrl'     => '/areas/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'地区\'', 'minWidth' => 350, 'fixed' => '\'left\'', 'edit' => '\'text\'', 'unresize' => true],
                ['field' => '\'district_count\'', 'title' => '\'子区域\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/areas/view/\'+res.id+\'?active=districts">\'+res.district_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['areas' => $tableParams];
        $this->set(compact('tableParams'));
    }

    //浏览详情
    public function view($id = null)
    {
        $area = $this->Areas->find()->where(['Areas.id' => $id])->first();
        //子区域
        $area->districtCount                  = $this->Areas->Districts->find()->where(['area_id' => $area->id])->count();

        $districtTableParams = [
            'name'        => 'districts',
            'renderUrl'   => '/districts/api-lists?search[area_id]=' . $area->id,
            'deleteUrl'   => '/districts/api-delete',
            'editUrl'     => '/districts/api-save',
            'addUrl'      => '/districts/add?area_id=' . $area->id,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'子区域\'', 'minWidth' => 350, 'fixed' => '\'left\'', 'edit' => '\'text\'', 'unresize' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];
        $tableParams = ['districts' => $districtTableParams];
        $active      = $this->request->query('active');
        $this->set(compact('area', 'tableParams', 'active'));
    }
    //添加
    public function add()
    {
        $area = $this->Areas->newEntity();
        $this->set(compact('area'));
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

        $area = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Areas->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Areas->newEntity();
        if (!$area) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $area = $this->Areas->patchEntity($area, $params);
        
        if (!$area->pid) {
            $area->pid = $this->getPid();
        }
        $data = $this->Areas->save($area) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($area->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
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
        $data = ['code' =>3];
        $this->resApi(0, $data, $msg_arr[3]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Areas.id',
                'name'       => 'Areas.name',
                'is_visible' => 'Areas.is_visible',
                'sort'       => 'Areas.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Areas.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Areas.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Areas.is_visible'] = $params['is_visible'];
                }
            }
            $contain = ['Districts'];

            $order = ['Areas.sort' => 'desc', 'Areas.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->district_count   = $this->Areas->Districts->find()->where(['area_id' => $row->id])->count();
            return $row;
        });
    }
}
