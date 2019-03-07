<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoriesAttributes Controller
 *
 * @property \App\Model\Table\CategoriesAttributesTable $CategoriesAttributes
 *
 * @method \App\Model\Entity\CategoriesAttribute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesAttributesController extends AppController
{

    //浏览
    public function view($id = null)
    {
        $attribute = $this->CategoriesAttributes->get($id, [
            'contain' => ['Categories', 'Attributes'],
        ]);

        $zones                      = $this->loadModel('Zones')->find('list')->where(['id' => $attribute->category->zone_id]);
        $groups                     = $this->loadModel('Groups')->find('list')->where(['id' => $attribute->category->group_id]);
        $categories                 = $this->loadModel('Categories')->find('list')->where(['id' => $attribute->category->id]);
        $attribute->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $attribute->category->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $attribute->category->group_id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'category_id' => $attribute->category->id,
                'disabled'    => true,
                'options'     => $categories,
            ],
        ]);
        $filterTableParams = [
            'name'        => 'category-attribute-filters',
            'renderUrl'   => '/category-attribute-filters/api-lists?search[category_attribute_id]=' . $attribute->id,
            'deleteUrl'   => '/category-attribute-filters/api-delete',
            'editUrl'     => '/category-attribute-filters/api-save',
            'add'         => true,
            'can_search'  => false,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'filter\'', 'title' => '\'筛选项值\'', 'unresize' => true, 'edit' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];
        $this->set(compact('attribute', 'filterTableParams'));
    }

    //添加
    public function add()
    {
        if ($this->request->query('category_id')) {
            $attribute   = $this->CategoriesAttributes->newEntity();
            $category_id = $this->request->query('category_id');
            $category    = $this->CategoriesAttributes->Categories->find()->where(['id' => $category_id])->first();
            !$category && $this->redirect(['controller' => 'categories']);

            $attribute->category        = $category;
            $zones                      = $this->loadModel('Zones')->find('list')->where(['id' => $attribute->category->zone_id]);
            $groups                     = $this->loadModel('Groups')->find('list')->where(['id' => $attribute->category->group_id]);
            $categories                 = $this->loadModel('Categories')->find('list')->where(['id' => $attribute->category->id]);
            $attribute->category_select = $this->getCasecadeTplParam('category_select', [
                'zone'     => [
                    'zone_id'  => $attribute->category->zone_id,
                    'disabled' => true,
                    'options'  => $zones,
                ],
                'group'    => [
                    'group_id' => $attribute->category->group_id,
                    'disabled' => true,
                    'options'  => $groups,
                ],
                'category' => [
                    'category_id' => $attribute->category->id,
                    'disabled'    => true,
                    'options'     => $categories,
                ],
            ]);
            $filterTableParams = [
                'name'        => 'category-attribute-filters',
                'renderUrl'   => '/category-attribute-filters/api-lists?search[category_attribute_id]=1',
                'deleteUrl'   => '/category-attribute-filters/api-delete',
                'editUrl'     => '/category-attribute-filters/api-save',
                'add'         => true,
                'can_search'  => false,
                'tableFields' => [
                    ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                    ['field' => '\'filter\'', 'title' => '\'筛选项值\'', 'unresize' => true, 'edit' => true],
                    ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                    ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
                ],
                'switchTpls'  => [
                    ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
                ],
            ];
            $autocompleteFields = [
                ['controller' => 'Attributes', 'inputElem' => '#attribute_name', 'idElem' => '#attribute_id'],
            ];
            $this->set(compact('autocompleteFields', 'attribute', 'filterTableParams'));
            $this->render('view');
        } else {
            $this->redirect(['controller' => 'categories']);
        }

    }

    //ajax删除产品
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');

        if (count($ids) == 0) {
            $data = ['code' => 2];
            $this->resApi(0, $data, $msg_arr[2]);
        }

        //删除产品相关属性值
        $category_attribute_ids = $this->CategoriesAttributes->find()->where(['id in' => $ids,'pid <' => 0])->extract('id')->toArray();
        if (count($category_attribute_ids)) {
            $this->CategoriesAttributes->deleteAll(['id in' => $category_attribute_ids]);
            $this->CategoriesAttributes->ProductsAttributes->deleteAll(['category_attribute_id in' => $category_attribute_ids]);
        }
        
        $data =  ['code' => 0,'ids' => $category_attribute_ids];
        $this->resApi(0, $data, $msg_arr[0]);
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数caid缺失', '记录不存在或已删除', '内容填写有误', '参数cid缺失', '请选择筛选项类型'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $attribute = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->CategoriesAttributes->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->CategoriesAttributes->newEntity();
        if (!$attribute) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['type'] === 'add' && $params['level'] = -1;
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
            $params['is_filter']  = isset($params['is_filter']) ? $params['is_filter'] : 0;
            $params['filter_type']  = $params['is_filter'] ? (isset($params['fiter_type']) ? $params['fiter_type']:1) : 0;
        }
        $attribute = $this->CategoriesAttributes->patchEntity($attribute, $params);
        if (!$attribute->category_id) {
            $data = 4;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        $data = $this->CategoriesAttributes->save($attribute) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($attribute->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            if ($attribute->is_filter && isset($params['filters']) && !empty($params['filters']) && is_array($params['filters'])) {
                $filterQuery = $this->CategoriesAttributes->CategoryAttributeFilters
                    ->query()
                    ->insert(['category_attribute_id', 'filter', 'is_visible', 'sort', 'pid']);
                $pid = $this->getPid('CategoryAttributeFilters');
                foreach ($params['filters'] as $filter) {
                    $filterQuery->values([
                        'category_attribute_id' => $attribute->id,
                        'filter'                => $filter['filter'],
                        'is_visible'            => $filter['is_visible'] == 'true' ? 1 : 0,
                        'sort'                  => $filter['sort'],
                        'pid'                   => $pid,
                    ]);
                    $pid--;
                }
                $filterQuery->execute();
            }
        }
        $this->resApi($code, $data, $msg_arr[$data]);
    }

    //ajax获取list
    public function apiLists()
    {
        $this->getTableData(function () {
            $fields = [
                'id'             => 'CategoriesAttributes.id',
                'name'           => 'Attributes.name',
                'attribute_id'   => 'Attributes.id',
                'attribute_name' => 'Attributes.name',
                'is_visible'     => 'CategoriesAttributes.is_visible',
                'is_filter'      => 'CategoriesAttributes.is_filter',
                'sort'           => 'CategoriesAttributes.sort',
                'category_name'  => 'Categories.name',
                'category_id'    => 'Categories.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['CategoriesAttributes.id'] = intval($params['id']);
                }
                if (isset($params['attribute_name']) && trim($params['attribute_name'])) {
                    $where['Attributes.name like'] = '%' . trim($params['attribute_name']) . '%';
                }
                if (isset($params['category_id']) && intval($params['category_id'])) {
                    $where['CategoriesAttributes.category_id'] = intval($params['category_id']);
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['CategoriesAttributes.is_visible'] = $params['is_visible'];
                }
                if (isset($params['is_filter']) && in_array($params['is_filter'], [1, 0])) {
                    $where['CategoriesAttributes.is_filter'] = $params['is_filter'];
                }
                if (isset($params['fiter_type']) && in_array($params['fiter_type'], [1, 2])) {
                    $where['CategoriesAttributes.fiter_type'] = $params['fiter_type'];
                }
            }
            $contain = ['Categories', 'Attributes'];

            $order = ['CategoriesAttributes.sort' => 'desc', 'CategoriesAttributes.id' => 'asc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
