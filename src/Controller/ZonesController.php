<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Zones Controller
 *
 * @property \App\Model\Table\ZonesTable $Zones
 *
 * @method \App\Model\Entity\Zone[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ZonesController extends AppController
{

    //首页列表
    public function index()
    {
        $tableParams = [
            'name'        => 'zones',
            'renderUrl'   => '/zones/api-lists',
            'deleteUrl'   => '/zones/api-delete',
            'editUrl'     => '/zones/api-save',
            'addUrl'      => '/zones/add',
            'viewUrl'     => '/zones/view',
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'空间\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'group_count\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.id+\'?active=groups">\'+res.group_count+\'</a>\')'],
                ['field' => '\'category_count\'', 'title' => '\'分类\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.id+\'?active=categories">\'+res.category_count+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['zones' => $tableParams];

        $this->set(compact('table_fields', 'switch_tpls', 'tableParams'));
    }


    //浏览详情
    public function view($id = null)
    {
        $zone = $this->Zones->get($id);
        //分组
        $zone->groupCount = $this->Zones->Groups->find()->where(['zone_id' => $zone->id])->count();        
        $groupTableParams    = [
            'name'        => 'groups',
            'renderUrl'   => '/groups/api-lists?search[zone_id]=' . $zone->id,
            'deleteUrl'   => '/groups/api-delete',
            'editUrl'     => '/groups/api-save',
            'addUrl'      => '/groups/add?zone_id=' . $zone->id,
            'viewUrl'     => '/groups/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分组\'', 'unresize' => true, 'edit' => '\'text\'', 'templet' => '(res) => (\'<a href="/groups/view/\'+res.id+\'">\'+res.name+\'</a>\')'],
                ['field' => '\'category_count\'', 'title' => '\'分类数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.id+\'?active=categories">\'+res.category_count+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        //分类
        $zone->categoryCount = $this->Zones->Categories->find()->where(['zone_id' => $zone->id])->count();
        $groups       = $this->Zones->Groups->find('list')->where(['zone_id' => $zone->id]);
        $categoryTableParams    = [
            'name'        => 'categories',
            'renderUrl'   => '/categories/api-lists?search[zone_id]=' . $zone->id,
            'deleteUrl'   => '/categories/api-delete',
            'editUrl'     => '/categories/api-save',
            'addUrl'      => '/categories/add?zone_id=' . $zone->id,
            'viewUrl'     => '/categories/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分类\'', 'unresize' => true, 'edit' => '\'text\'', 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'">\'+res.name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品数\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];        
        //产品
        $zone->productCount = $this->Zones->Products->find()->where(['zone_id' => $zone->id])->count();
        $productTableParams    = [
            'name'        => 'products',
            'renderUrl'   => '/products/api-lists?search[zone_id]=' . $zone->id,
            'deleteUrl'   => '/products/api-delete',
            'editUrl'     => '/products/api-save',
            'addUrl'      => '/products/add?zone_id=' . $zone->id,
            'viewUrl'     => '/products/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'产品\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
                ['field' => '\'category_name\'', 'title' => '\'分类\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.category_id+\'">\'+res.category_name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
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
        $categories       = $this->Zones->Categories->find('list')->where(['zone_id' => $zone->id]);
        $tableParams = ['groups' => $groupTableParams, 'categories' => $categoryTableParams, 'products' => $productTableParams];
        $active = $this->request->query('active');
        $this->set(compact('zone', 'tableParams','active','groups','categories'));
    }

    //添加
    public function add()
    {
        $zone = $this->Zones->newEntity();
        $this->set(compact('zone'));
        $this->render('view');
    }

    //ajax删除空间
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中','暂不支持删除'];
        $this->allowMethod(['POST']);
        $data = 3;
        $this->resApi(0, $data, $msg_arr[$data]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Zones.id',
                'name'       => 'Zones.name',
                'is_visible' => 'Zones.is_visible',
                'sort'       => 'Zones.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where   = [];
            $contain = [];

            $order = ['Zones.sort' => 'desc', 'Zones.modified' => 'desc', 'Zones.created' => 'desc', 'Zones.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->group_count    = $this->Zones->Groups->find()->where(['zone_id' => $row->id])->count();
            $row->category_count = $this->Zones->Categories->find()->where(['zone_id' => $row->id])->count();
            $row->product_count = $this->Zones->Products->find()->where(['zone_id' => $row->id])->count();
            return $row;
        });
    }

    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数zid缺失', '记录不存在或已删除', '内容填写有误'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $zone = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Zones->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Zones->newEntity();

        if (!$zone) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible']                       = isset($params['is_visible']) ? $params['is_visible'] : 0;

        }

        $zone = $this->Zones->patchEntity($zone, $params);
        $data    = $this->Zones->save($zone) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {

            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }

}
