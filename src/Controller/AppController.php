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
}
