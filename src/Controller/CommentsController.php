<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'Fans'],
        ];
        $comments = $this->paginate($this->Comments);

        $this->set(compact('comments'));
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['Products', 'Fans'],
        ]);

        $this->set('comment', $comment);
    }

    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code           = 0;
        $msg_arr        = ['保存成功', '记录不存在或已删除', '内容填写有误'];
        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $comment = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Comments->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Comments->newEntity();

        if (!$comment) {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        $params = $this->request->getData();
        //详情编辑页面提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_checked'] = isset($params['is_checked']) ? $params['is_checked'] : 0;
        }
        //若修改审核状态
        if ($params['is_checked'] != $comment->is_checked) {
            $comment_delt = $params['is_checked'] ? 1 : -1;
            $this->setProductMetaData($comment->product_id, ['comment_count' => $comment_delt]);
        }
        $comment = $this->Comments->patchEntity($comment, $params);
        $data    = $this->Comments->save($comment) ? 0 : 2;

        $this->resApi($code, $data, $msg_arr[$data]);

    }
    //前端删除
    public function apiDelete()
    {
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');
        if (count($ids)) {
            $code=0;
            //更新产品数据
            $comments = $this->Comments->find('all', [
                'conditions' => ['id in ' => $ids],
                'fields'     => ['id', 'product_id'],
            ])
                ->groupBy('product_id');
            foreach ($comments as $product_id => $value) {
                $count = count($value);
                if ($count) {
                    $this->setProductMetaData($product_id, ['comment_count' => -($count)]);
                }

            }
            $this->Comments->deleteAll(['id in' => $ids]);
        }else{
            $code=2;
        }
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->resApi(0, compact('code', 'ids'), $msg_arr[$code]);
    }
    //ajax获取list
    public function apiLists()
    {
        $this->getTableData(function () {
            $fields = [
                'id'       => 'Comments.id',
                'content'  => 'Comments.content',
                'rating'   => 'Comments.rating',
                'is_checked'  => 'Comments.is_checked',
                'created'  => 'Comments.created',
                'sort'     => 'Comments.sort',
                'fan_name' => 'Fans.nickName',
                'fan_id'   => 'Fans.id',
            ];
            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = ['product_id' => $params['product_id']];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['fan_id']) && intval($params['fan_id'])) {
                    $where['Comments.fan_id'] = intval($params['fan_id']);
                }
                if (isset($params['is_checked']) && in_array($params['is_checked'], [1, 0])) {
                    $where['Products.is_checked'] = $params['is_checked'];
                }
            }
            $contain = ['Fans'];
            $order   = ['Comments.sort' => 'desc', 'Comments.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, function () {
            $msg_arr = ['加载完成', '访问参数无pid'];
            if (!($this->request->getQuery('product_id') || $this->request->getQuery('merchant_id'))) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        }, function ($row) {
            $row->created = (new Time($row->created))->i18nFormat('yyyy-MM-dd HH:mm:ss');
            return $row;
        });
    }
}
