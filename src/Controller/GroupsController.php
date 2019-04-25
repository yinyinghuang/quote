<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 *
 * @method \App\Model\Entity\Group[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsController extends AppController
{

    //首页列表
    public function index()
    {
        $tableParams = [
            'name'        => 'groups',
            'renderUrl'   => '/groups/api-lists',
            'deleteUrl'   => '/groups/api-delete',
            'editUrl'     => '/groups/api-save',
            'addUrl'      => '/groups/add',
            'viewUrl'     => '/groups/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分组\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'zone_name\'', 'title' => '\'空间\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.zone_id+\'">\'+res.zone_name+\'</a>\')'],
                ['field' => '\'category_count\'', 'title' => '\'分类数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.id+\'?active=categories">\'+res.category_count+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['groups' => $tableParams];
        $category_select = $this->getCasecadeTplParam('category_select', [
            'group'    => [
                'disabled' => true,
                'options'  => [],
            ],
            'category' => [
                'disabled' => true,
                'options'  => [],
            ],
        ], true);
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams', 'category_select'));
    }

    //浏览详情
    public function view($id = null)
    {
        $group           = $this->Groups->find()->where(['Groups.id' => $id])->contain(['Zones'])->first();
        $zones           = $this->Groups->Zones->find('list')->where(['id' => $group->zone_id]);
        $groups          = $this->Groups->find('list')->where(['id' => $group->id]);
        $categories      = $this->Groups->Categories->find('list')->where(['group_id' => $group->id]);
        $group->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $group->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'show' => false,
            ],
            'category' => [
                'show' => false,
            ],
        ], false);
        //分类
        $group->categoryCount                     = $this->Groups->Categories->find()->where(['group_id' => $group->id])->count();
        $searchTpl['category']['category_select'] = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $group->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $group->id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'disabled' => true,
                'options'  => [],
            ],
        ], true);
        $categoryTableParams = [
            'name'        => 'categories',
            'renderUrl'   => '/categories/api-lists?search[group_id]=' . $group->id,
            'deleteUrl'   => '/categories/api-delete',
            'editUrl'     => '/categories/api-save',
            'addUrl'      => '/categories/add?group_id=' . $group->id,
            'viewUrl'     => '/categories/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分类\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'product_count\'', 'title' => '\'产品数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        //产品
        $group->productCount                     = $this->Groups->Products->find()->where(['group_id' => $group->id])->count();
        $searchTpl['product']['category_select'] = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $group->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $group->id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'options' => $categories,
            ],
        ], true);
        $productTableParams = [
            'name'        => 'products',
            'renderUrl'   => '/products/api-lists?search[group_id]=' . $group->id,
            'deleteUrl'   => '/products/api-delete',
            'editUrl'     => '/products/api-save',
            'addUrl'      => '/products/add?group_id=' . $group->id,
            'viewUrl'     => '/products/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'产品\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
                ['field' => '\'category_name\'', 'title' => '\'分类\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.category_id+\'">\'+res.category_name+\'</a>\')'],
                ['field' => '\'is_new\'', 'title' => '\'新品\'', 'unresize' => true, 'templet' => '\'#switchTpl_1\''],
                ['field' => '\'is_hot\'', 'title' => '\'热门\'', 'unresize' => true, 'templet' => '\'#switchTpl_2\''],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_1', 'name' => 'is_new', 'text' => '是|否'],
                ['id' => 'switchTpl_2', 'name' => 'is_hot', 'text' => '是|否'],
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];
        $categories  = $this->Groups->Categories->find('list')->where(['group_id' => $group->id]);
        $tableParams = ['categories' => $categoryTableParams, 'products' => $productTableParams];
        $active      = $this->request->query('active');
        $this->set(compact('group', 'tableParams', 'active', 'searchTpl'));
    }

    //添加
    public function add()
    {
        $group  = $this->Groups->newEntity();
        $params = $this->request->query();
        if (isset($params['zone_id']) && $params['zone_id']) {
            $zone                 = $this->Groups->Zones->find()->where(['id' => $params['zone_id']])->first();
            $zone && $group->zone = $zone;
        }
        $group->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $group->zone_id,
            ],
            'group'    => [
                'show' => false,
            ],
            'category' => [
                'show' => false,
            ],
        ], false);

        $this->set(compact('group'));
        $this->render('view');
    }

    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数gid缺失', '记录不存在或已删除', '内容填写有误', '参数zid缺失'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $group = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Groups->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Groups->newEntity();
        if (!$group) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $group = $this->Groups->patchEntity($group, $params);
        if (!$group->zone_id) {
            $data = 4;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        if (!$group->pid) {
            $group->pid = $this->getPid();
        }
        $visible_dirty = $group->isDirty('is_visible');
        $data = $this->Groups->save($group) ? 0 : 3;
        
        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($group->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }
        if($visible_dirty){
            $this->Groups->Categories
                ->query()
                ->update()
                ->set(['is_visible' => $group->is_visible])
                ->where(['group_id' => $group->id])
                ->execute();
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }

    //ajax删除空间
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中', '暂不支持删除'];
        $this->allowMethod(['POST']);
        
        $code=3;
        $this->resApi(0, compact('code'), $msg_arr[$code]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Groups.id',
                'name'       => 'Groups.name',
                'is_visible' => 'Groups.is_visible',
                'sort'       => 'Groups.sort',
                'zone_name'  => 'Zones.name',
                'zone_id'    => 'Zones.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Groups.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Groups.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['zone_id']) && intval($params['zone_id'])) {
                    $where['Groups.zone_id'] = intval($params['zone_id']);
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Groups.is_visible'] = $params['is_visible'];
                }
            }

            $contain = ['Zones'];

            $order = ['Groups.sort' => 'desc', 'Groups.modified' => 'desc', 'Groups.created' => 'desc', 'Groups.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->category_count = $this->Groups->Categories->find()->where(['group_id' => $row->id])->count();
            $row->product_count  = $this->Groups->Products->find()->where(['group_id' => $row->id])->count();
            return $row;
        });
    }
}
