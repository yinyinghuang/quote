<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoryAttributeFilters Controller
 *
 * @property \App\Model\Table\CategoryAttributeFiltersTable $CategoryAttributeFilters
 *
 * @method \App\Model\Entity\CategoryAttributeFilter[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoryAttributeFiltersController extends AppController
{
    
    //ajax删除产品
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');

        if (count($ids) == 0) {
            $code = 2;
            $this->resApi(0, compact('code'), $msg_arr[$code]);
        }

        //删除产品相关属性值
        $this->CategoryAttributeFilters->deleteAll(['id in' => $ids]);
        $code = 0;
        $this->resApi(0, compact('code','ids'),  $msg_arr[$code]);
    }

    //ajax修改
    public function apiSave()
    {
        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数cafid缺失', '记录不存在或已删除', '内容填写有误', '参数caid缺失'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $filter = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->CategoryAttributeFilters->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->CategoryAttributeFilters->newEntity();
        if (!$filter) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $filter = $this->CategoryAttributeFilters->patchEntity($filter, $params);
        if (!$filter->category_attribute_id) {
            $data = 4;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        
        $data = $this->CategoryAttributeFilters->save($filter) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($filter->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
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
                'id'             => 'CategoryAttributeFilters.id',
                'filter'           => 'CategoryAttributeFilters.filter',
                'is_visible'     => 'CategoryAttributeFilters.is_visible',
                'sort'           => 'CategoryAttributeFilters.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['CategoryAttributeFilters.id'] = intval($params['id']);
                }
                if (isset($params['filter']) && trim($params['filter'])) {
                    $where['CategoryAttributeFilters.filter like'] = '%' . trim($params['filter']) . '%';
                }
                if (isset($params['category_attribute_id']) && intval($params['category_attribute_id'])) {
                    $where['CategoryAttributeFilters.category_attribute_id'] = intval($params['category_attribute_id']);
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['CategoryAttributeFilters.is_visible'] = $params['is_visible'];
                }
            }
            $contain = [];

            $order = ['CategoryAttributeFilters.sort' => 'desc', 'CategoryAttributeFilters.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }
}
