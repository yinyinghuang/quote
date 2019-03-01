<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * Categories Controller
 *
 *
 * @method \Api\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{

    public function lists()
    {
        $params = $this->request->getData();
        switch ($params['type']) {
            case 'zones':
                $zones = $this
                    ->loadModel('Zones')
                    ->find()
                    ->select(['id','name'])
                    ->where(['Zones.is_visible' => 1])
                    ->order(['Zones.sort desc','Zones.id desc'])
                    ->toArray();
                $this->ret(0,$zones,'加载成功');
                break;
            case 'zone_children':
                if (isset($params['id']) && $params['id']) {
                    $zone = $this->loadModel('Zones')
                        ->find()
                        ->select(['id','name'])
                        ->where(['Zones.is_visible' => 1])
                        ->first();
                    if (empty($zone)) $this->ret(2,null,'空间不存在');
                    $groups = $this->loadModel('Groups')
                        ->find()
                        ->select(['Groups.id','Groups.name'])
                        ->contain(['Categories' => function ($query){
                            return $query->select(['Categories.id','Categories.group_id','Categories.name','product_count' => $query->func()->count('Products.id')])
                                ->where(['Categories.is_visible' => 1])
                                ->order(['Categories.sort desc','Categories.id desc'])
                                ->leftJoinWith('Products')
                                ->group(['Categories.id']);
                        }])
                        ->where(['Groups.is_visible' => 1,'Groups.zone_id' => $params['id']])
                        ->order(['Groups.sort desc','Groups.id desc'])
                        ->toArray();
                    $this->ret(0,compact('zone','groups'),'加载成功');
                }else{
                    $this->ret(1,null,'参数错误');
                }
                
                break;
            
            default:
                # code...
                break;
        }
    }

    public function detail()
    {
        $category_id = $this->request->getData('category_id');
        $category = $this->loadModel('Categories')
            ->find()
            ->where(['Categories.is_visible' => 1,'Categories.id' => $category_id])
            ->first();
        $this->ret(0,$category,['分类信息加载成功']);
    }
}
