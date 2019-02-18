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
            'addUrl'      => null,
            'add'      => true,
            'can_search'  => false,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'filter\'', 'title' => '\'筛选项\'', 'unresize' => true, 'edit' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];
        $tableParams        = ['filters' => $filterTableParams];
        $autocompleteFields = [
            ['controller' => 'Attributes', 'inputElem' => '#attribute_name', 'idElem' => '#attribute_id'],
        ];
        $this->set(compact('attribute', 'autocompleteFields', 'tableParams'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoriesAttribute = $this->CategoriesAttributes->newEntity();
        if ($this->request->is('post')) {
            $categoriesAttribute = $this->CategoriesAttributes->patchEntity($categoriesAttribute, $this->request->getData());
            if ($this->CategoriesAttributes->save($categoriesAttribute)) {
                $this->Flash->success(__('The categories attribute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The categories attribute could not be saved. Please, try again.'));
        }
        $categories = $this->CategoriesAttributes->Categories->find('list', ['limit' => 200]);
        $attributes = $this->CategoriesAttributes->Attributes->find('list', ['limit' => 200]);
        $this->set(compact('categoriesAttribute', 'categories', 'attributes'));
    }

    //ajax删除产品
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');

        if (count($ids) == 0) {
            $data = 2;
            $this->resApi(0, $data, $msg_arr[$res]);
        }

        //删除产品相关属性值
        $this->CategoriesAttributes->deleteAll(['id in' => $ids]);
        $this->CategoriesAttributes->ProductsAttributes->deleteAll(['category_attribute_id in' => $ids]);
        $data = 0;
        $this->resApi(0, $data, $msg_arr[$data]);
    }

    //ajax修改
    public function apiSave()
    {

        // $this->allowMethod(['POST', 'PUT', 'PATCH']);
        // $code    = 0;
        // $msg_arr = ['保存成功', '参数caid缺失', '记录不存在或已删除', '内容填写有误', '参数cid缺失'];

        // $params         = $this->request->getData();
        // $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        // if (!isset($params['id']) && $params['type'] === 'edit') {
        //     $data = 1;
        //     $this->resApi($code, $data, $msg_arr[$data]);
        // }

        // $attribute = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->CategoriesAttributes->find('all')
        //     ->where(['id' => $params['id']])
        //     ->first() : $this->CategoriesAttributes->newEntity();
        // if (!$attribute) {
        //     $data = 2;
        //     $this->resApi($code, $data, $msg_arr[$data]);
        // }
        // //详情编辑情提交请求
        // if (isset($params['detail']) && $params['detail']) {
        //     $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        // }
        // $attribute = $this->CategoriesAttributes->patchEntity($attribute, $params);
        // if (!$attribute->category_id) {
        //     $data = 4;
        //     $this->resApi($code, $data, $msg_arr[$data]);
        // }

        // $data = $this->CategoriesAttributes->save($attribute) ? 0 : 3;

        // //内容填写错误导致记录无法更新
        // if ($data === 3) {
        //     $this->resApi($code, $data, $msg_arr[$data]);
        // }

        // $this->resApi($code, $data, $msg_arr[$data]);

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

            $order = ['CategoriesAttributes.sort' => 'desc', 'CategoriesAttributes.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
