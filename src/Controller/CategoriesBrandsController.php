<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoriesBrands Controller
 *
 * @property \App\Model\Table\CategoriesBrandsTable $CategoriesBrands
 *
 * @method \App\Model\Entity\CategoriesBrand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesBrandsController extends AppController
{

    //浏览
    public function view($id = null)
    {
        $brand = $this->CategoriesBrands->get($id, [
            'contain' => ['Categories'],
        ]);

        $zones                  = $this->loadModel('Zones')->find('list')->where(['id' => $brand->category->zone_id]);
        $groups                 = $this->loadModel('Groups')->find('list')->where(['id' => $brand->category->group_id]);
        $categories             = $this->loadModel('Categories')->find('list')->where(['id' => $brand->category->id]);
        $brand->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id'  => $brand->category->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' => $brand->category->group_id,
                'disabled' => true,
                'options'  => $groups,
            ],
            'category' => [
                'category_id' => $brand->category->id,
                'disabled'    => true,
                'options'     => $categories,
            ],
        ]);
        $this->set(compact('brand'));
    }

    //添加
    public function add()
    {
        if ($this->request->query('category_id')) {
            $brand       = $this->CategoriesBrands->newEntity();
            $category_id = $this->request->query('category_id');
            $category    = $this->CategoriesBrands->Categories->find()->where(['id' => $category_id])->first();
            !$category && $this->redirect(['controller' => 'categories']);

            $brand->category        = $category;
            $zones                  = $this->loadModel('Zones')->find('list')->where(['id' => $brand->category->zone_id]);
            $groups                 = $this->loadModel('Groups')->find('list')->where(['id' => $brand->category->group_id]);
            $categories             = $this->loadModel('Categories')->find('list')->where(['id' => $brand->category->id]);
            $brand->category_select = $this->getCasecadeTplParam('category_select', [
                'zone'     => [
                    'zone_id'  => $brand->category->zone_id,
                    'disabled' => true,
                    'options'  => $zones,
                ],
                'group'    => [
                    'group_id' => $brand->category->group_id,
                    'disabled' => true,
                    'options'  => $groups,
                ],
                'category' => [
                    'category_id' => $brand->category->id,
                    'disabled'    => true,
                    'options'     => $categories,
                ],
            ]);
            $autocompleteFields = [
                ['controller' => 'Brands', 'inputElem' => '#brand', 'idElem' => '#brand','template_val' => 'brand','template_txt' => 'brand'],
            ];
            $this->set(compact('autocompleteFields', 'brand'));
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
        $category_brand_ids = $this->CategoriesBrands->find()->where(['id in' => $ids])->extract('id')->toArray();
        if (count($category_brand_ids)) {
            $this->CategoriesBrands->deleteAll(['id in' => $category_brand_ids]);
        }

        $data = ['code' => 0, 'ids' => $category_brand_ids];
        $this->resApi(0, $data, $msg_arr[0]);
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数cbid缺失', '记录不存在或已删除', '内容填写有误', '参数cid缺失', '请选择筛选项类型'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';   
        $category_id =      $this->request->query('category_id') || $this->request->getData('category_id');
        if (!$category_id ) {
            $data = 4;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $brand = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->CategoriesBrands->find('all')
            ->where(['id' => $params['id'],'category_id' => $category_id ])
            ->first() : $this->CategoriesBrands->newEntity();

        if (!$brand) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        $brand->category_id = $category_id;
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible']                         = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $brand = $this->CategoriesBrands->patchEntity($brand, $params);     
        $data = $this->CategoriesBrands->save($brand) ? 0 : 3;

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

    //ajax获取list
    public function apiLists()
    {
        $this->getTableData(function () {
            $fields = [
                'id'         => 'CategoriesBrands.id',
                'brand'         => 'CategoriesBrands.brand',
                'is_visible'    => 'CategoriesBrands.is_visible',
                'sort'          => 'CategoriesBrands.sort',
                'category_name' => 'Categories.name',
                'category_id'   => 'Categories.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = ['CategoriesBrands.category_id' => $this->request->getQuery('category_id')];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['brand']) && trim($params['brand'])) {
                    $where['CategoriesBrands.brand like'] = '%' . trim($params['brand']) . '%';
                }
                if (isset($params['id']) && intval($params['id'])) {
                    $where['CategoriesBrands.id'] = intval($params['id']);
                }
                if (isset($params['category_id']) && intval($params['category_id'])) {
                    $where['CategoriesBrands.category_id'] = intval($params['category_id']);
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['CategoriesBrands.is_visible'] = $params['is_visible'];
                }
            }
            $contain = ['Categories'];

            $order = ['CategoriesBrands.sort' => 'desc', 'CategoriesBrands.brand' => 'asc'];
            return [$fields, $where, $contain, $order];

        }, function () {
            $msg_arr = ['加载完成', '访问参数无cid'];
            if (!($this->request->getQuery('category_id'))) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        });
    }
}
