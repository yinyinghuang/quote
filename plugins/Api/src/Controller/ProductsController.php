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
        $offset  = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        if (isset($params['category_id']) && $params['category_id']) {
            $where['Products.category_id'] = $params['category_id'];
        }

        //最新更新
        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'last':
                    $order = ['Products.modified desc'] + $order;
                    break;
            }
        }
        //获取品牌
        if (isset($params['brand']) && !empty($params['brand'])) {
            $where['Products.brand'] = $params['brand'];
        }
        //获取价格
        if (isset($params['price']) && !empty($params['price'])) {
            $price_range = explode('-', $params['price']);
            if(count($price_range)===2){
                if(!empty($price_range[0])){
                    $where['Products.price_hong_min >='] = $price_range[0];
                    $where['Products.price_water_min >='] = $price_range[0];
                }
                if(!empty($price_range[1])){
                    $where['Products.price_hong_max <='] = $price_range[1];
                    $where['Products.price_water_max <='] = $price_range[1];
                }
                
            }
            
        }
        //获取筛选条件
        if (isset($params['filter']) && !empty($params['filter'])) {
            foreach ($params['filter'] as $filter) {
                $where[] = 'Products.filter LIKE "%' . $filter . ',%"';
            }
        }
        //获取排序
        if (isset($params['sort'])) {
            switch ($params['sort']) {
                case 'hotest':

                    break;

                default:
                    # code...
                    break;
            }
        }
        $products = $this->Products
            ->find()
            ->select($select)
            ->where($where)
            ->contain($contain)
            ->order($order)
            ->offset($offset)
            ->limit($limit)
            ->map(function ($row) {
                $row->cover = $this->_getProductCover($row->id, $row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $products, '加载成功');
    }

    private function _getProductCover($product_id, $product_album)
    {

        $cover = '';
        if ($product_album) {
            $albumDir = $this->_getAlbumDir($product_id);
            $albums   = json_decode($product_album, true);
            if (count($albums)) {
                $album = $albums[0];
                $cover = 'album/product/' . $albumDir . $product_id . '_' . $album[0] . '_2.' . $album[1];
            }
        }
        return $cover;

    }
    public function detail($id)
    {
        if (empty($id)) {
            $this->ret(1, null, '产品id缺失');
        }

        $product = $this->loadModel('Products')->find('all', [
            'conditions' => ['Products.id' => $id, 'Products.is_visible' => 1],
        ])->first();
        if (empty($product)) {
            $this->ret(1, null, '产品不存在或已被删除');
        }

        $product->attributes = $this->loadModel('ProductsAttributes')->find('all', [
            'conditions' => ['ProductsAttributes.product_id' => $id, 'CategoriesAttributes.is_visible' => 1],
            'contain'    => ['CategoriesAttributes'],
            'fields'    => ['value' => 'ProductsAttributes.value','attribute_id' => 'CategoriesAttributes.attribute_id'],
        ])->map(function($row){
            $row->attribute_name = $this->loadModel('Attributes')->find()->where(['id' => $row->attribute_id])->first()->name;
            return $row;
        });
        $this->ret(0, $product, '产品加载成功');
    }
    private function _getProductAlbumUrl($product_id, $product_album)
    {
        $albumDir = $this->_getAlbumDir($product_id);
        $albums   = [];
        if ($product_album) {
            foreach (json_decode($product_album, true) as $key => $album) {
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
    private function _getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
}
