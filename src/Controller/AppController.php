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
use Cake\Cache\Cache;
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
                ['url' => '/keywords', 'name' => '热门搜索', 'tabs' => ['Keywords']],
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
        // 'Configs'      => [
        //     'url'  => '/configs',
        //     'name' => '配置',
        //     'tabs' => ['Configs'],
        // ],
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
        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Products',
                'action' => 'index'
            ],
            'unauthorizedRedirect' => $this->referer(),
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'Login'
            ]
        ]);
        // 更新分类的最高最低价
        // $cates = $this->loadModel('Categories')->find('list');
        // foreach ($cates as $cate_id => $cate_name) {
        //     if(empty($this->loadModel('Products')->find()->where(['category_id' => $cate_id])->first())){
        //         continue;
        //     }
        //     $price_hong_max = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_hong_max desc')->first()->price_hong_max;
        //     $price_water_max = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_water_max desc')->first()->price_water_max;
        //     $price_max = max($price_hong_max,$price_water_max);

        //     $price_hong_min = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_hong_min asc')->first()->price_hong_min;
        //     $price_water_min = $this->loadModel('Products')->find()->where(['category_id' => $cate_id])->order('Products.price_water_min asc')->first()->price_water_min;
        //     $price_min = max($price_hong_min,$price_water_min);

        //     $this->Categories->query()
        //         ->update()
        //         ->set(compact('price_min','price_max'))
        //         ->where(['id' => $cate_id])
        //         ->execute();

        // }        
        $Navs  = $this->navs;
        $Navs['Products']['children'][] = ['url' => '/comments', 'name' => '待审核评论', 'tabs' => ['Comments'],'bage' => $this->loadModel('Comments')->find()->where(['is_checked' => -1])->count()];
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
            'Comments'             => '评价',
            'Keywords'             => '关键词',
            'Configs'             => '配置',
        ];
        $actionsMap = [
            'add'       => '添加 ',
            'view'      => '详情',
            'index'     => '列表',
            'login'     => '登陆',
            'apiDelete' => '列表',
        ];
        $breadcrumbs = [
            ['href' => '/' . $controller, 'title' => $breadcrumbsMap[$controller]],
            ['href' => '', 'title' => $actionsMap[$action]],
        ];
        $brands = $this->loadModel('Brands')->find('all');
        foreach ($brands as $row) {
            $alpha = $this->getfirstchar($row->brand);
            debug($alpha);
            $this->Brands->query()->update()->set(['alpha' => $alpha])->where(['brand' => $row->brand])->execute();
        }
        
        $this->set(compact('breadcrumbs','brands'));

    }
    protected function getfirstchar($s0){   //获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
        $fchar = ord($s0{0});
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
        $s1 = iconv("UTF-8","gb2312", $s0);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "H";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return NULL;
        //return $s0;
    }

    //前台自动填充
    public function apiAutocomplete()
    {
        $controllerSqlParam = [
            'Brands' => ['keywordField' => 'brand', 'selectFields' => ['id' => 'brand', 'name' => 'brand']],
        ];

        $data       = [];
        $params     = $this->request->query();
        $controller = $params['c'];
        $keywords   = $params['keywords'];
        if (isset($controllerSqlParam[$controller])) {
            $keywordField = $controllerSqlParam[$controller]['keywordField'];
            $selectFields = $controllerSqlParam[$controller]['selectFields'];
        } else {
            $keywordField = 'name';
            $selectFields = ['id', 'name'];
        }
        // $sqlParam = $controllerSqlParam[$controller];

        $data    = $code    = 0;
        $type    = 'success';
        $content = $this->$controller
            ->find()
            ->select($selectFields)
            ->where([$keywordField . ' like' => '%' . $keywords . '%'])
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
    //更新产品数据
    protected function setProductMetaData($product_id, $data)
    {
        $conditions = compact('product_id');
        $metaData = $this->loadModel('ProductData')->find('all')->where($conditions)->first();
        $query = $this->ProductData->query();
        if($metaData){
            foreach ($data as $key => &$value) {
                $value = $metaData->$key+$value;
            }
            $query->update()->set($data)->where($conditions)->execute();
        }else{
            $quote_count = $this->loadModel('Quotes')->find()->where(['product_id' => $product_id])->count();
            if (isset($data['quote_count'])) {
                $data['quote_count'] = $quote_count+$data['quote_count'];
            }
            $values = array_merge(['view_count' =>0,'collect_count'=>0,'comment_count'=>0,'comment_score_total'=>0,'share_count'=>0,'quote_count'=>$quote_count],$data,$conditions);
            $query->insert(['view_count','collect_count','comment_count','comment_score_total','product_id','quote_count','share_count'])->values($values)->execute();
        }
    }
}
