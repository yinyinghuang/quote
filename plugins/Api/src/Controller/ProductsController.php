<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * Products Controller
 *
 *
 * @method \Api\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{
    public function lists()
    {
        $params  = $this->request->getData();
        $select  = ['Products.id', 'Products.name', 'Products.album', 'Products.price_hong_min', 'Products.price_hong_max', 'Products.price_water_min', 'Products.price_water_max'];
        $where   = ['Products.is_visible' => 1, 'Categories.is_visible' => 1];
        $contain = ['Categories'];
        $order   = ['Products.sort desc', 'Products.id desc'];
        $limit   = 20;

        switch ($params['type']) {
            case 'last':
                $order = ['Products.modified desc'] + $order;
                break;

            default:

                break;
        }
        $products = $this->Products
            ->find()
            ->select($select)
            ->where($where)
            ->order($order)
            ->toArray();
        $this->ret(0, $zones, '加载成功');
    }
}
