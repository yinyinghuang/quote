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
    public function index()
    {
        
        $tableParams    = [
            'name'        => 'comments',
            'renderUrl'   => '/comments/api-lists?is_checked=-1',
            'deleteUrl'   => '/comments/api-delete',
            'editUrl'     => '/comments/api-save',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'product_name\'', 'title' => '\'产品\'', 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/products/view/\'+res.product_id+\'">\'+res.product_name+\'</a>\')', 'minWidth' => 150],
                ['field' => '\'fan_name\'', 'title' => '\'粉丝\'', 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/fans/view/\'+res.fan_id+\'">\'+res.fan_name+\'</a>\')'],
                ['field' => '\'content\'', 'title' => '\'内容\'', 'minWidth' => 280, 'unresize' => true, ],
                ['field' => '\'rating\'', 'title' => '\'评级\'', 'unresize' => true,],
                ['field' => '\'is_checked\'', 'title' => '\'审核通过\'', 'unresize' => true, 'templet' => '\'#switchTpl_4\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
                ['field' => '\'created\'', 'title' => '\'评论时间\'','minWidth' => 180, 'unresize' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_4', 'name' => 'is_checked', 'text' => '是|否']],
        ];

        $tableParams     = ['comments' => $tableParams];
        $this->set(compact('tableParams'));
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
        $delt = $params['is_checked'] - $comment->is_checked;
        if ($delt!=0) {
            $comment_delt = $delt>0 ? 1 : -1;
            $comment_score_delt = $params['is_checked'] ? $comment->rating : -$comment->rating;
            $this->setProductMetaData($comment->product_id, ['comment_count' => $comment_delt,'comment_score_total' => $comment_score_delt]);
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
                'fields'     => ['id', 'product_id','rating'],
            ])
                ->groupBy('product_id');
            foreach ($comments as $product_id => $value) {
                $count = count($value);
                $comment_score_delt = -array_sum(array_column($value, 'rating'));
                if ($count) {
                    $this->setProductMetaData($product_id, ['comment_count' => -($count),'comment_score_total' => $comment_score_delt]);
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
                'product_name' => 'Products.name',
                'product_id'   => 'Products.id',
            ];
            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['product_id']) && $params['product_id']) {
                $where['product_id'] = $params['product_id'];
            }            
            if (isset($params['is_checked']) && in_array($params['is_checked'], [0,1,-1])) {
                $where['is_checked'] = $params['is_checked'];
            }            
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['fan_id']) && intval($params['fan_id'])) {
                    $where['Comments.fan_id'] = intval($params['fan_id']);
                }
                if (isset($params['is_checked']) && in_array($params['is_checked'], [1, 0])) {
                    $where['Products.is_checked'] = $params['is_checked'];
                }
            }
            $contain = ['Fans','Products'];
            $order   = ['Comments.sort' => 'desc', 'Comments.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, /*function () {
            $msg_arr = ['加载完成', '访问参数无pid'];
            if (!($this->request->getQuery('product_id') || $this->request->getQuery('merchant_id'))) {
                $this->resApi(0, [], $msg_arr[1]);
            }
        }*/null, function ($row) {
            $row->created = (new Time($row->created))->i18nFormat('yyyy-MM-dd HH:mm:ss');
            return $row;
        });
    }
}
