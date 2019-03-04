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
                    ->select(['id', 'name'])
                    ->where(['Zones.is_visible' => 1])
                    ->order(['Zones.sort desc', 'Zones.id desc'])
                    ->toArray();
                $this->ret(0, $zones, '加载成功');
                break;
            case 'zone_children':
                if (isset($params['id']) && $params['id']) {
                    $zone = $this->loadModel('Zones')
                        ->find()
                        ->select(['id', 'name'])
                        ->where(['Zones.is_visible' => 1])
                        ->first();
                    if (empty($zone)) {
                        $this->ret(2, null, '空间不存在');
                    }

                    $groups = $this->loadModel('Groups')
                        ->find()
                        ->select(['Groups.id', 'Groups.name'])
                        ->contain(['Categories' => function ($query) {
                            return $query->select(['Categories.id', 'Categories.group_id', 'Categories.name', 'product_count' => $query->func()->count('Products.id')])
                                ->where(['Categories.is_visible' => 1])
                                ->order(['Categories.sort desc', 'Categories.id desc'])
                                ->leftJoinWith('Products')
                                ->group(['Categories.id']);
                        }])
                        ->where(['Groups.is_visible' => 1, 'Groups.zone_id' => $params['id']])
                        ->order(['Groups.sort desc', 'Groups.id desc'])
                        ->toArray();
                    $this->ret(0, compact('zone', 'groups'), '加载成功');
                } else {
                    $this->ret(1, null, '参数错误');
                }

                break;
            default:
                # code...
                break;
        }
    }

    // public function getCategoryRelated()
    // {
    //     $category_id = $this->request->query('category_id');
    //     $category    = $this->loadModel('Categories')
    //         ->find('all', [
    //             'contain'    => ['Zones', 'Groups'],
    //             'conditions' => ['Categories.is_visible' => 1, 'Categories.id' => $category_id],
    //             'fields'     => [
    //                 'id'         => 'Categories.id',
    //                 'name'       => 'Categories.name',
    //                 'zone_name'  => 'Zones.name',
    //                 'zone_id'    => 'Zones.id',
    //                 'group_name' => 'Groups.name',
    //                 'group_id'   => 'Groups.id',
    //             ],

    //         ])
    //         ->first();

    //     if (!empty($category)) {
    //         $category->filter = $this->getCategoryFilter($category_id);
    //     }

    //     $this->ret(0, $category, ['分类信息加载成功']);
    // }

    //获取分类的属性键值,及为筛选项的属性键
    public function getCategoryIsFilter()
    {

        $category_id = $this->request->query('category_id');
        //分类下为筛选项的属性
        $cateFilterAttrs = $this->loadModel('CategoriesAttributes')->find('all', [
            'contain'    => ['Attributes'],
            'conditions' => [
                'CategoriesAttributes.category_id' => $category_id,
                'CategoriesAttributes.is_filter'   => 1,
                'CategoriesAttributes.is_visible'  => 1,
            ],
            'fields'     => [
                'id'          => 'CategoriesAttributes.id',
                'name'        => 'Attributes.name',
                'filter_type' => 'CategoriesAttributes.filter_type',
            ],
            'order' => $this->getDefaultOrder('CategoriesAttributes'),
        ])
            ->toArray();
        $this->ret(0, $cateFilterAttrs, ['分类信息加载成功']);
    }
    //获取分类的属性键值,及为筛选项的属性键
    public function getCategoryFilterOption()
    {

        $category_attribute_id = $this->request->query('category_attribute_id');
        //分类下为筛选项的属性
        $cateFilterAttrs = $this->loadModel('CategoryAttributeFilters')->find('all', [
            'conditions' => [
                'CategoryAttributeFilters.category_attribute_id' => $category_attribute_id,
                'CategoryAttributeFilters.is_visible'  => 1,
            ],
            'fields'     => [
                'id'          => 'CategoryAttributeFilters.id',
                'filter'        => 'CategoryAttributeFilters.filter',
            ],
            'order' => $this->getDefaultOrder('CategoryAttributeFilters'),
        ])
            ->toArray();
        $this->ret(0, $cateFilterAttrs, ['分类信息加载成功']);
    }
}
