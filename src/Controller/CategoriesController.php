<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    //首页列表
    public function index()
    {
        $tableParams = [
            'name'        => 'categories',
            'renderUrl'   => '/categories/api-lists',
            'deleteUrl'   => '/categories/api-delete',
            'editUrl'     => '/categories/api-save',
            'addUrl'      => '/categories/add',
            'viewUrl'     => '/categories/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分类\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'zone_name\'', 'title' => '\'空间\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.zone_id+\'">\'+res.zone_name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'attribute_count\'', 'title' => '\'属性\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=categories-attributes">\'+res.attribute_count+\'</a>\')'],
                ['field' => '\'brand_count\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=categories-brands">\'+res.brand_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['categories' => $tableParams];
        $zones           = $this->Categories->Zones->find('list');
        $groups          = $this->Categories->Groups->find('list');
        $categories      = $this->Categories->find('list');
        $category_select = $this->getCasecadeTplParam('category_select', [
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
        $category = $this->Categories->find()->where(['Categories.id' => $id])->contain(['Zones', 'Groups'])->first();

        $zones           = $this->Categories->Zones->find('list')->where(['id' => $category->zone_id]);
        $groups          = $this->Categories->Groups->find('list')->where(['id' => $category->group_id]);
        $categories      = $this->Categories->find('list')->where(['id' => $category->id]);
        $category->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $category->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $category->group_id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'show' => false,
            ],
        ]);
        //产品
        $category->productCount                  = $this->Categories->Products->find()->where(['category_id' => $category->id])->count();
        $searchTpl['product']['category_select'] = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $category->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $category->group_id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'category_id' => $category->id,
                'disabled'    => true,
                'options'     => $categories,
            ],
        ], true);
        $productTableParams = [
            'name'        => 'products',
            'renderUrl'   => '/products/api-lists?search[category_id]=' . $category->id,
            'deleteUrl'   => '/products/api-delete',
            'editUrl'     => '/products/api-save',
            'addUrl'      => '/products/add?category_id=' . $category->id,
            'viewUrl'     => '/products/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'产品\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
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
        //分类属性
        $category->attributeCount = $this->Categories->CategoriesAttributes->find()->where(['category_id' => $category->id])->count();
        $attributeTableParams     = [
            'name'        => 'categories-attributes',
            'renderUrl'   => '/categories-attributes/api-lists?search[category_id]=' . $category->id,
            'deleteUrl'   => '/categories-attributes/api-delete',
            'editUrl'     => '/categories-attributes/api-save',
            'addUrl'      => '/categories-attributes/add?category_id=' . $category->id,
            'viewUrl'     => '/categories-attributes/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'属性\'', 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/attributes/view/\'+res.attribute_id+\'">\'+res.attribute_name+\'</a>\')'],
                ['field' => '\'is_filter\'', 'title' => '\'筛选项\'', 'unresize' => true, 'templet' => '\'#switchTpl_4\''],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
                ['id' => 'switchTpl_4', 'name' => 'is_filter', 'text' => '是|否'],
            ],
        ];

        //分类属性
        $category->brandCount = $this->Categories->CategoriesBrands->find()->where(['category_id' => $category->id])->count();
        $brandTableParams     = [
            'name'        => 'categories-brands',
            'renderUrl'   => '/categories-brands/api-lists?category_id='.$category->id.'&search[category_id]=' . $category->id,
            'deleteUrl'   => '/categories-brands/api-delete',
            'editUrl'     => '/categories-brands/api-save?category_id=' . $category->id,
            'addUrl'      => '/categories-brands/add?category_id=' . $category->id,
            'viewUrl'     => '/categories-brands/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_5\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_5', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];
        $tableParams = ['products' => $productTableParams, 'categories-attributes' => $attributeTableParams, 'categories-brands' => $brandTableParams];
        $active      = $this->request->query('active');
        $this->set(compact('category', 'tableParams', 'active', 'searchTpl','category_select'));
    }

    //添加
    public function add()
    {
        $category = $this->Categories->newEntity();
        $params   = $this->request->query();
        $zone_id  = $group_id  = null;
        if (isset($params['group_id']) && $params['group_id']) {
            $group = $this->Categories->Groups->find()->where(['id' => $params['group_id']])->first();
            if ($group) {
                $zone_id  = $group->zone_id;
                $group_id = $group->id;
            }
        } elseif (isset($params['zone_id']) && $params['zone_id']) {
            $zone = $this->Categories->Zones->find()->where(['id' => $params['zone_id']])->first();
            if ($zone) {
                $zone_id = $zone->id;
            }
        }
        $category->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id' => $zone_id,
            ],
            'group'    => [
                'group_id' => $group_id,
            ],
            'category' => [
                'show' => false,
            ],
        ], false);

        $this->set(compact('category'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数cid缺失', '记录不存在或已删除', '内容填写有误', '参数zid/gid缺失'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $category = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Categories->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Categories->newEntity();
        if (!$category) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $category = $this->Categories->patchEntity($category, $params);
        if (!$category->zone_id||!$category->group_id) {
            $data = 4;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        if (!$category->pid) {
            $category->pid = $this->getPid();
        }
        $data = $this->Categories->save($category) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($category->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }

    //ajax删除空间
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中', '暂不支持删除'];
        $this->allowMethod(['POST']);

        // $ids = $this->request->getData('ids');
        // if (count($ids) == 0) {
        //     $data = 2;
        //     $this->resApi(0, $data, $msg_arr[$res]);
        // }
        // //更新categories表
        // $this->Categories->deleteAll(['id in' => $ids]);
        // //更新categories_attributes/category_attribute_filters
        // $category_attribute_ids = $this->Categories->CategoriesAttributes->find()->where(['category_id in' => $ids])->extract('id')->toArray();
        // if (count($category_attribute_ids)) {
        //     $this->loadModel('CategoryAttributeFilters')->deleteAll(['category_attribute_id in' => $category_attribute_ids]);
        //     $this->Categories->CategoriesAttributes->deleteAll(['id in' => $category_attribute_ids]);
        // }
        // //更新categories_brands表
        // $this->Categories->CategoriesBrands->deleteAll(['category_id in' => $ids]);
        // //更新products/products_attributes
        // $product_ids = $this->Categories->Products->find()->where(['category_id in' => $ids])->extract('id')->toArray();
        // if (count($product_ids)) {
        //     $this->loadModel('ProductsAttribtues')->deleteAll(['product_id in' => $product_ids]);
        //     $this->Categories->Products->deleteAll(['id in' => $product_ids]);
        // }

        $data = ['code' =>3];
        $this->resApi(0, $data, $msg_arr[$data]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Categories.id',
                'name'       => 'Categories.name',
                'is_visible' => 'Categories.is_visible',
                'sort'       => 'Categories.sort',
                'group_name' => 'Groups.name',
                'group_id'   => 'Groups.id',
                'zone_name'  => 'Zones.name',
                'zone_id'    => 'Zones.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Categories.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Categories.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['zone_id']) && intval($params['zone_id'])) {
                    $where['Categories.zone_id'] = intval($params['zone_id']);
                }
                if (isset($params['group_id']) && intval($params['group_id'])) {
                    $where['Categories.group_id'] = intval($params['group_id']);
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Categories.is_visible'] = $params['is_visible'];
                }
            }
            $contain = ['Zones', 'Groups'];

            $order = ['Categories.sort' => 'desc', 'Categories.modified' => 'desc', 'Categories.created' => 'desc', 'Categories.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->product_count   = $this->Categories->Products->find()->where(['category_id' => $row->id])->count();
            $row->attribute_count = $this->Categories->CategoriesAttributes->find()->where(['category_id' => $row->id])->count();
            $row->brand_count = $this->Categories->CategoriesBrands->find()->where(['category_id' => $row->id])->count();
            return $row;
        });
    }
    //多级下拉框获取数据
    public function apiCascade(){
        $conditions = $this->request->getQuery('id')?['group_id' => $this->request->getQuery('id')]:[];
        $fields = ['key' => 'name','value' => 'id'];
        $list = $this->Categories->find('all',compact('conditions','fields'));
        $this->response->body(json_encode($list));
        return $this->response;
    }
}
