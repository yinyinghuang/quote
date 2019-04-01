<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    // Other methods..

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['logout']);
    }

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'users',
            'renderUrl'   => '/users/api-lists',
            'deleteUrl'   => '/users/api-delete',
            'editUrl'     => '/users/api-save',
            'addUrl'      => '/users/add',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'username\'', 'title' => '\'用户名\'', 'fixed' => '\'left\'', 'unresize' => true, 'edit' => true],
                ['field' => '\'pwd\'', 'title' => '\'密码\'', 'unresize' => true, 'edit' => true],
            ],
            'switchTpls'  => [

            ],
        ];

        $tableParams = ['users' => $tableParams];
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();

        $this->set(compact('user'));
        $this->render('view');
    }
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('用戶名稱或密碼不正確, 請重試!'));
        }
    }
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数uid缺失', '记录不存在或已删除', '属性名已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $user = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Users->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Users->newEntity();
        if (!$user) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        if(isset($params['pwd']) && strlen($params['pwd'])>6){
            $user->password = $params['pwd'];
        }
        $user = $this->Users->patchEntity($user, $params);

        $data = $this->Users->save($user) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($user->__debugInfo()['[errors]'] as $name => $error) {
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

        $this->Users->deleteAll(['id in' => $ids]);

        $data = ['code' => 0, 'ids' => $ids];
        $this->resApi(0, $data, $msg_arr[0]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'       => 'Users.id',
                'username' => 'Users.username',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Users.id'] = intval($params['id']);
                }
                if (isset($params['username']) && trim($params['username'])) {
                    $where['Users.username like'] = '%' . trim($params['username']) . '%';
                }
            }
            $contain = [];

            $order = ['Users.id' => 'desc', 'Users.modified' => 'desc', 'Users.created' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->pwd   = '******';
            return $row;
        });
    }
}
