<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Attributes Controller
 *
 * @property \App\Model\Table\AttributesTable $Attributes
 *
 * @method \App\Model\Entity\Attribute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AttributesController extends AppController
{

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'attributes',
            'renderUrl'   => '/attributes/api-lists',
            'deleteUrl'   => '/attributes/api-delete',
            'editUrl'     => '/attributes/api-save',
            'addUrl'      => '/attributes/add',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'属性名\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true,'edit' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['attributes' => $tableParams];
        $category_select = $this->getCasecadeTplParam('category_select', [], true);
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams', 'category_select'));
    }

    //浏览
    public function view($id = null)
    {
        $attribute = $this->Attributes->get($id);

        $this->set('attribute', $attribute);
    }

    //添加
    public function add()
    {
        $attribute = $this->Attributes->newEntity();        
        $this->set(compact('attribute', 'categories', 'products'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数aid缺失', '记录不存在或已删除', '属性名已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $attribute = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Attributes->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Attributes->newEntity();
        if (!$attribute) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $attribute = $this->Attributes->patchEntity($attribute, $params);

        $data = $this->Attributes->save($attribute) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }
    //ajax删除
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中', '暂不支持删除'];
        $this->allowMethod(['POST']);

        // $ids = $this->request->getData('ids');
        // if (count($ids) == 0) {
        //     $data = 2;
        //     $this->resApi(0, $data, $msg_arr[$res]);
        // }
        // //更新attributes表
        // $this->Attributes->deleteAll(['id in' => $ids]);
        // //更新products_attributes及categories_attributes及category_attribute_filters
        // $category_attribute_ids = $this->Attributes->CategoriesAttributes->find()->where(['id in' => $ids])->extract('id')->toArray();
        // if (count($category_attribute_ids)) {
        //     $this->loadModel('ProductsAttribtues')->deleteAll(['category_attribute_id in' => $category_attribute_ids]);
        //     $this->loadModel('CategoryAttributeFilters')->deleteAll(['category_attribute_id in' => $category_attribute_ids]);
        //     $this->Attributes->CategoriesAttributes->deleteAll(['id in' => $category_attribute_ids]);
        // }
        $data = 3;
        $this->resApi(0, $data, $msg_arr[$data]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Attributes.id',
                'name'       => 'Attributes.name',
                'is_visible' => 'Attributes.is_visible',
                'sort'       => 'Attributes.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Attributes.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Attributes.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Attributes.is_visible'] = $params['is_visible'];
                }
            }
            $contain = [];

            $order = ['Attributes.sort' => 'desc', 'Attributes.created' => 'desc', 'Attributes.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
