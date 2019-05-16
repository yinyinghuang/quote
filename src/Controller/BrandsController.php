<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Brands Controller
 *
 * @property \App\Model\Table\BrandsTable $Brands
 *
 * @method \App\Model\Entity\Brand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BrandsController extends AppController
{

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'brands',
            'renderUrl'   => '/brands/api-lists',
            'deleteUrl'   => '/brands/api-delete',
            'editUrl'     => '/brands/api-save',
            'addUrl'      => '/brands/add',
            'can_search'  => true,
            'delIndex'    => 'brand',
            'tableFields' => [
                ['field' => '\'brand\'', 'title' => '\'品牌名\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['brands' => $tableParams];
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams'));
    }

    //浏览
    public function view($id = null)
    {
        $brand = $this->Brands->get($id);

        $this->set('brand', $brand);
    }

    //添加
    public function add()
    {
        $brand = $this->Brands->newEntity();
        $this->set(compact('brand', 'categories', 'products'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数bid缺失', '记录不存在或已删除', '属性名已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['brand']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $brand = (isset($params['brand']) && $params['brand'] && $params['type'] == 'edit') ? $this->Brands->find('all')
            ->where(['id' => $params['brand']])
            ->first() : $this->Brands->newEntity();
        if (!$brand) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $brand = $this->Brands->patchEntity($brand, $params);

        $data = $this->Brands->save($brand) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($brand->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] = $name . ':' . implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }
    //ajax删除
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
        
        $this->Brands->deleteAll(['brand in' => $ids]);
        $this->Brands->CategoriesBrands->deleteAll(['brand in' => $ids]);

        $data = ['code' => 0, 'brands' => $ids];
        $this->resApi(0, $data, $msg_arr[0]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'brand'      => 'Brands.brand',
                'is_visible' => 'Brands.is_visible',
                'sort'       => 'Brands.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['brand']) && trim($params['brand'])) {
                    $where['Brands.brand like'] = '%' . trim($params['brand']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Brands.is_visible'] = $params['is_visible'];
                }
            }
            $contain = [];

            $order = ['Brands.sort' => 'desc', 'Brands.modified' => 'desc', 'Brands.created' => 'desc', 'Brands.brand' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
