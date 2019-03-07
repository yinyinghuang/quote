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
use Cake\Event\Event;
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

    protected $defaultLimit    = 10;
    protected $category_select = [
        'search'  => false,
        'label'   => '分类',
        'selects' => [
            'zone'     => [
                'label'    => '空间',
                'zone_id'  => null,
                'disabled' => false,
                'show'     => true,
            ],
            'group'    => [
                'label'    => '分组',
                'group_id' => null,
                'disabled' => false,
                'show'     => true,
            ],
            'category' => [
                'label'       => '分类',
                'category_id' => null,
                'disabled'    => false,
                'show'        => true,
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
                'show'     => true,
            ],
            'district' => [
                'label'       => '区域',
                'district_id' => null,
                'disabled'    => false,
                'show'        => true,
            ],
        ],
    ];
    protected $navs = [
        'Products'  => [
            'url'      => 'javascript:;',
            'name'     => '产品',
            'tabs'     => ['Products', 'Zones', 'Groups', 'Categories', 'Attributes', 'Brands'],
            'children' => [
                ['url' => '/products', 'name' => '产品列表', 'tabs' => ['Products', 'ProductsAttributes']],
                ['url' => '/zones', 'name' => '空间列表', 'tabs' => ['Zones']],
                ['url' => '/groups', 'name' => '分组列表', 'tabs' => ['Groups']],
                ['url' => '/categories', 'name' => '分类列表', 'tabs' => ['Categories']],
                ['url' => '/attributes', 'name' => '属性列表', 'tabs' => ['Attributes']],
                ['url' => '/brands', 'name' => '品牌列表', 'tabs' => ['Brands']],
            ],
        ],
        'Merchants' => [
            'url'      => 'javascript:;',
            'name'     => '商户',
            'tabs'     => ['Merchants', 'Areas', 'Districts'],
            'children' => [
                ['url' => '/merchants', 'name' => '商户列表', 'tabs' => ['Merchants', 'MerchantLocations']],
                ['url' => '/areas', 'name' => '地区列表', 'tabs' => ['Areas', 'Districts']],
            ],
        ],
        'Users'     => [
            'url'  => '/users',
            'name' => '管理员',
            'tabs' => ['Users'],
        ],
        'Fans'      => [
            'url'  => '/fans',
            'name' => '粉丝',
            'tabs' => ['Fans'],
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
        // $this->loadComponent('Auth', [
        //     'loginRedirect' => [
        //         'controller' => 'Products',
        //         'action' => 'index'
        //     ],
        //     'unauthorizedRedirect' => $this->referer()
        // ]);
        $cates = $this->loadModel('Categories')->find('list');
        foreach ($cates as $cate_id => $cate_name) {

            $price_hong_max = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_hong_max desc')->first();
            if(empty($price_hong_max)){
                continue;
            }
            $price_hong_max = $price_hong_max->price_hong_max;
            $price_water_max = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_water_max desc')->first()->price_water_max;
            $price_max = max($price_hong_max,$price_water_max);


            $price_hong_min = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_hong_min asc')->first()->price_hong_min;
            $price_water_min = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_water_min asc')->first()->price_water_min;
            $price_min = max($price_hong_min,$price_water_min);
            $this->Categories->query()
                ->update()
                ->set(compact('price_min','price_max'))
                ->where(['id' => $cate_id])
                ->execute();

        }
        $Navs  = $this->navs;
        $token = $this->request->getParam('_csrfToken');
        $this->set(compact('Navs', 'token'));
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    public function beforeRender(Event $event)
    {

        $controller     = $this->request->getParam('controller');
        $action         = $this->request->getParam('action');
        $pass           = $this->request->getParam('pass');
        $breadcrumbsMap = [
            'Products'             => '产品',
            'Merchants'            => '商户',
            'Users'                => '管理员',
            'Fans'                 => '粉丝',
            'Categories'           => '分类',
            'Groups'               => '分组',
            'Zones'                => '空间',
            'CategoriesAttributes' => '分类属性',
            'Attributes'           => '属性',
            'Quotes'               => '报价',
            'Areas'                => '地区',
            'Districts'            => '地区',
            'Brands'               => '品牌',
            'CategoriesBrands'     => '分类品牌',
        ];
        $actionsMap = [
            'add'   => '添加 ',
            'view'  => '详情',
            'index' => '列表',
            'login' => '登陆',
            'apiDelete' => '列表',
        ];
        $breadcrumbs = [
            ['href' => '/' . $controller, 'title' => $breadcrumbsMap[$controller]],
            ['href' => '', 'title' => $actionsMap[$action]],
        ];
        $this->set(compact('breadcrumbs'));
    }

    //前台自动填充
    public function apiAutocomplete()
    {
        $controllerSqlParam = [
            'Brands' => ['keywordField' => 'brand','selectFields' => ['id' => 'brand','name' => 'brand']],
        ];

        $data       = [];
        $params     = $this->request->query();
        $controller = $params['c'];
        $keywords   = $params['keywords'];
        if (isset($controllerSqlParam[$controller])) {
            $keywordField = $controllerSqlParam[$controller]['keywordField'];
            $selectFields = $controllerSqlParam[$controller]['selectFields'];
        }else{
            $keywordField = 'name';
            $selectFields = ['id','name'];
        }
        // $sqlParam = $controllerSqlParam[$controller];

        $data    = $code    = 0;
        $type    = 'success';
        $content = $this->$controller
            ->find()
            ->select($selectFields)
            ->where([$keywordField .' like' => '%' . $keywords . '%'])
            ->toArray();

        $this->resApi($code, $data, $type, ['type' => $type, 'content' => $content]);

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
        $sql = [
            'contain'    => $contain,
            'fields'     => $fields,
            'conditions' => $where,
            'limit'      => $limit,
            'offset'     => $offset,
            'order'      => $order,

        ];
        $data  = $mapFn ? $this->$controller->find('all', $sql)->map($mapFn)->toArray() : $this->$controller->find('all', $sql)->toArray();
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
    protected function getCasecadeTplParam($type, $selects = [], $search = false)
    {
        $params           = $this->$type;
        $params['search'] = $search;
        //选框参数自定义
        foreach ($selects as $name => $select) {
            $params['selects'][$name] = array_merge($params['selects'][$name], $select);
        }
        //填充未定义选框选项为默认
        foreach ($params['selects'] as $name => $value) {
            if (!isset($params['selects'][$name]['options'])) {
                $model                               = ucwords(Inflector::pluralize($name));
                $params['selects'][$name]['options'] = $this->loadModel($model)->find('list');
            }
        }
        return $params;
    }
    protected function getPid($controller = null)
    {
        $controller = $controller ?: $this->request->controller;
        $entity     = $this->loadModel($controller)->find()->where(['pid <' => 0])->select(['pid'])->order(['pid' => 'asc'])->first();

        return $entity ? $entity->pid - 1 : -1;
    }

    //获取6位随机字符串
    protected function generateRandomStr()
    {
        $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $name = substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 7), 6);
        return $name;
    }
}
