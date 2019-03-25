<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Keywords Controller
 *
 * @property \App\Model\Table\KeywordsTable $Keywords
 *
 * @method \App\Model\Entity\Fan[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class KeywordsController extends AppController
{

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'keywords',
            'renderUrl'   => '/keywords/api-lists',
            'deleteUrl'   => '/keywords/api-delete',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'关键词\'', 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'count\'', 'title' => '\'搜索次数\'', 'unresize' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['keywords' => $tableParams];
        $this->set(compact('table_fields', 'tableParams'));
    }

    //浏览
    public function view($id = null)
    {
        $keyword = $this->Keywords->get($id);

        $this->set('keyword', $keyword);
    }

    //ajax修改
    public function apiSave()
    {
        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数aid缺失', '记录不存在或已删除', '粉丝昵称已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $keyword = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Keywords->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Keywords->newEntity();
        if (!$keyword) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $keyword = $this->Keywords->patchEntity($keyword, $params);

        $data = $this->Keywords->save($keyword) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($keyword->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] = $name . ':' . implode(',', array_values($error));
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

        $ids = $this->request->getData('ids');
        if (count($ids) == 0) {
            $data = 2;
            $this->resApi(0, $data, $msg_arr[$res]);
        }
        //更新keywords表
        $this->Keywords->deleteAll(['id in' => $ids]);
        $data = ['code' => 3];
        $this->resApi(0, $data, $msg_arr[3]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'       => 'Keywords.id',
                'name' => 'Keywords.name',
                'count' => 'Keywords.count',
                'is_visible' => 'Keywords.is_visible',
                'sort' => 'Keywords.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Keywords.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Keywords.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Categories.is_visible'] = $params['is_visible'];
                }
            }
            $contain = [];

            $order = ['Keywords.sort' => 'desc','Keywords.id' => 'desc',];
            return [$fields, $where, $contain, $order];

        });
    }
}
