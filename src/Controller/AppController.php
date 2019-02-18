<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Utility\Inflector;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    protected $defaultLimit = 10;
    protected $category_select = [
            'search'  => false,
            'label'   => '分类',
            'selects' => [
                'zone'     => [
                    'label'    => '空间',
                    'zone_id'  => null,
                    'disabled' => false,
                    'show' => true,
                ],
                'group'    => [
                    'label'    => '分组',
                    'group_id' => null,
                    'disabled' => false,
                    'show' => true,
                ],
                'category' => [
                    'label'       => '分类',
                    'category_id' => null,
                    'disabled'    => false,
                    'show' => true,
                ],
            ],
        ];
    protected $district_select = [
            'search'  => false,
            'label'   => '地区',
            'selects' => [
                'area'     => [
                    'label'    => '区',
                    'area_id'  => null,
                    'disabled' => false,
                ],
                'district' => [
                    'label'       => '区域',
                    'district_id' => null,
                    'disabled'    => false,
                ],
            ],

        ];
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadModel('Areas');
        $this->loadModel('Districts');
        $this->loadModel('MerchantLocations');

        //修复merchant_location 的area_id
        // $areas = $this->Areas->find('list');
        // foreach ($areas as $area_id => $area_name) {
        //     $districts = array_keys($this->Districts->find('list', ['conditions' => ['area_id' => $area_id]])->toArray());
        //     !empty($districts) && $this->MerchantLocations->query()->update()->set(['area_id' => $area_id])->where(['district_id in ' => $districts])->execute();
        // }
        $Navs = [
            [
                'url'      => 'javascript:;',
                'name'     => '产品',
                'tabs'     => ['Products', 'Zones', 'Groups', 'Categories'],
                'children' => [
                    ['url' => '/products', 'name' => '列表', 'tabs' => ['Products', 'ProductsAttributes']],
                    ['url' => '/zones', 'name' => '空间', 'tabs' => ['Zones']],
                    ['url' => '/groups', 'name' => '分组', 'tabs' => ['Groups']],
                    ['url' => '/categories', 'name' => '分类', 'tabs' => ['Categories']],
                    ['url' => '/attributes', 'name' => '属性', 'tabs' => ['Attributes']],
                ],
            ], [
                'url'      => 'javascript:;',
                'name'     => '商户',
                'tabs'     => ['Merchants', 'Areas'],
                'children' => [
                    ['url' => '/merchants', 'name' => '列表', 'tabs' => ['Merchants', 'MerchantLocations']],
                    ['url' => '/areas', 'name' => '地区', 'tabs' => ['Areas', 'Districts']],
                ],
            ], [
                'url'  => '/users',
                'name' => '用户',
                'tabs' => ['Users'],
            ], [
                'url'  => '/fans',
                'name' => '粉丝',
                'tabs' => ['Fans'],
            ],
        ];
        $token = $this->request->getParam('_csrfToken');
        $this->set(compact('Navs', 'token'));
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    //前台自动填充
    public function apiAutocomplete()
    {
        // $controllerSqlParam = [
        //     'Products' => ['keywordField' => 'name','selectFields' => ['id','name']],
        //     'Merchants' => ['keywordField' => 'name','selectFields' => ['id','name']],
        //     'Zones' => ['keywordField' => 'name','selectFields' => ['id','name']],
        //     'Attributes' => ['keywordField' => 'name','selectFields' => ['id','name']],
        // ];
        $data = [];
        $params = $this->request->query();
        $controller = $params['c'];
        $keywords = $params['keywords'];
        // $sqlParam = $controllerSqlParam[$controller];

        $data = $code = 0;
        $type = 'success';
        $content = $this->$controller
            ->find()
            ->select(['id','name'])
            ->where(['name like' => '%'.$keywords .'%'])
            ->toArray();

        $this->resApi($code, $data, $type,['type' => $type, 'content' => $content]);

    }

    protected function getTableData($sqlFn, $checkFn = null, $mapFn = null)
    {
        is_callable($checkFn) && $checkFn();

        $controller = $this->request->getParam('controller');

        $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
        $params  = $this->request->$paramFn();

        $limit  = isset($params['limit']) ? $params['limit'] : $this->defaultLimit;
        $page   = isset($params['page']) ? $params['page'] : 1;
        $offset = $limit * ($page - 1);

        $order = [$controller . '.sort' => 'desc', $controller . '.modified' => 'desc', $controller . '.id' => 'desc', $controller . '.created' => 'desc'];

        list($fields, $where, $contain, $order) = $sqlFn();

        if (isset($params['order']) && is_array($params['order'])) {
            foreach ($params['order'] as $key => $value) {
                $order = [$controller . '.' . $key => $value] + $order;
            }
        }
       
        $data = $mapFn ? $this->$controller->find('all', [
            'contain'    => $contain,
            'fields'     => $fields,
            'conditions' => $where,
            'limit'      => $limit,
            'offset'     => $offset,
            'order'      => $order,

        ])->map($mapFn) : $this->$controller->find('all', [
            'contain'    => $contain,
            'fields'     => $fields,
            'conditions' => $where,
            'limit'      => $limit,
            'offset'     => $offset,
            'order'      => $order,

        ]);
        $count = $this->$controller->find('all', [
            'contain'    => $contain,
            'conditions' => $where,
        ])->count();

        $this->resApi(0, $data, '加载完成', ['count' => $count]);
    }

    protected function resApi($code, $data, $msg, $extra = [])
    {
        $this->autoRender = false;
        $res              = [
            'code' => $code,
            'data' => $data,
            'msg'  => $msg,
        ] + $extra;

        $this->response->body(json_encode($res));
        die($this->response);
    }

    protected function allowMethod($methods)
    {

        !in_array($this->request->getMethod(), $methods) && $this->resApi(1, 0, '访问出错');
    }
    //获取联动选框模板参数
    protected function getCasecadeTplParam($type,$selects=[],$search=false){
        $params = $this->$type;
        $params['search'] = $search;
        //选框参数自定义
        foreach ($selects as $name => $select) {
            $params['selects'][$name] = array_merge($params['selects'][$name],$select);
        }
        //填充未定义选框选项为默认
        foreach ($params['selects'] as $name => $value) {
            if (!isset($params['selects'][$name]['options'])) {
                $model = ucwords(Inflector::pluralize($name));
                $params['selects'][$name]['options'] = $this->loadModel($model)->find('list');
            }
        }
        return $params;
    }   
    protected function getPid()
    {
        $controller = $this->request->controller;
        $entity = $this->$controller->find()->where(['pid <' =>0])->select(['pid'])->order(['pid' => 'asc'])->first();
        
        return $entity?$entity->pid-1:-1;
    } 
}
