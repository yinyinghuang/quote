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

        if(isset($params['type'])){
            switch ($params['type']) {
                case 'last':
                    $order = ['Products.modified desc'] + $order;
                    break;
            }
        }
        $products = $this->Products
            ->find()
            ->select($select)
            ->where($where)
            ->contain($contain)
            ->order($order)
            ->limit($limit)
            ->map(function ($row)
            {                
                $row->albums = $this->getProductAlbumUrl($row->id,$row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $products, '加载成功');
    }

    private function getProductAlbumUrl($product_id,$product_album)     
    {
        $albumDir        = $this->getAlbumDir($product_id);
        $albums = [];
        if ($product_album) {
            foreach (json_decode($product_album,true) as $key => $album) {
                $albums[] = [
                    'thumb'  => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_2.' . $album[1],
                    'middle' => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_4.' . $album[1],
                    'full'   => 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_0.' . $album[1],
                ];
            }
        }
        return $albums;

    }

    //获取产品图片文件夹
    private function getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
}
