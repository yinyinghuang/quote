<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\I18n\Time;

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
        $params     = $this->request->getData();
        $fields     = ['Products.id', 'Products.name', 'Products.album', 'Products.price_hong_min', 'Products.price_hong_max', 'Products.price_water_min', 'Products.price_water_max'];
        $conditions = ['Products.is_visible' => 1, 'Categories.is_visible' => 1];
        $contain    = ['Categories'];
        $order      = ['Products.sort desc', 'Products.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        if (isset($params['category_id']) && $params['category_id']) {
            $conditions['Products.category_id'] = $params['category_id'];
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
            $conditions['Products.brand'] = $params['brand'];
        }
        //获取价格
        if (isset($params['price']) && !empty($params['price'])) {
            $price_range = explode('-', $params['price']);
            if (count($price_range) === 2) {
                if (!empty($price_range[0])) {
                    $conditions['or'] = [
                        'Products.price_hong_min >='  => $price_range[0],
                        'Products.price_water_min >=' => $price_range[0],
                    ];
                }
                if (!empty($price_range[1])) {
                    $conditions['or'] = [
                        'Products.price_hong_max >='  => $price_range[1],
                        'Products.price_water_max >=' => $price_range[1],
                    ];
                }

            }

        }
        //获取筛选条件
        if (isset($params['filter']) && !empty($params['filter'])) {
            foreach ($params['filter'] as $filter) {
                $conditions[] = 'Products.filter LIKE "%' . $filter . ',%"';
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
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
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
        $fan_id  = $this->request->getData('pkey');
        $product = $this->loadModel('Products')->find('all', [
            'conditions' => ['Products.id' => $id, 'Products.is_visible' => 1],
        ])->first();
        if (empty($product)) {
            $this->ret(1, null, '产品不存在或已被删除');
        }
        $product->albums        = $this->_getProductAlbumUrl($product->id, $product->album);
        $product->comment_count = $this->loadModel('Comments')->find('all', [
            'conditions' => ['product_id' => $product->id, 'is_checked' => 1],
        ])->count();
        $product->commented = $this->loadModel('Comments')->find('all', [
            'conditions' => compact('product_id', 'fan_id'),
        ])->count();
        $product->liked = $this->loadModel('Likes')->find('all', [
            'conditions' => compact('product_id', 'fan_id'),
        ])->count();
        $product->attributes = $this->loadModel('ProductsAttributes')->find('all', [
            'conditions' => ['ProductsAttributes.product_id' => $id, 'CategoriesAttributes.is_visible' => 1],
            'contain'    => ['CategoriesAttributes'],
            'fields'     => ['value' => 'ProductsAttributes.value', 'attribute_id' => 'CategoriesAttributes.attribute_id'],
        ])->map(function ($row) {
            $row->attribute_name = $this->loadModel('Attributes')->find()->where(['id' => $row->attribute_id])->first()->name;
            return $row;
        });        
        //更新产品数据统计
        $this->setProductMetaData($id,['view_count' => 'view_count'+2]);
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
    public function quoteLists($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失');
        }

        $params = $this->request->getData();
        $fields = [
            'merchant_id'   => 'Merchants.id',
            'merchant_name' => 'Merchants.name',
            'price_hong'    => 'Quotes.price_hong',
            'price_water'   => 'Quotes.price_water',
        ];
        $conditions = ['Quotes.is_visible' => 1, 'Quotes.product_id' => $product_id];
        $contain    = ['Merchants'];
        $order      = ['Quotes.sort desc', 'Merchants.sort desc', 'Quotes.id desc', 'Merchants.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        //存在地区筛选项
        $area_id     = isset($params['area_id']) ? intval($params['area_id']) : 0;
        $district_id = isset($params['district_id']) ? intval($params['district_id']) : 0;
        if ($area_id || $district_id) {
            $locationWhere                                = [];
            $area_id && $locationWhere['area_id']         = $area_id;
            $district_id && $locationWhere['district_id'] = $district_id;
            $merchant_ids                                 = $this->loadModel('MerchantLocations')->find('all', [
                'conditions' => $locationWhere,
            ])->extract('id')->toArray();
        }
        //存在水货/行货筛选项
        if (isset($params['price_type']) && in_array($params['price_type'], ['1', '2'])) {
            $params['price_type'] == 1 && $conditions['Quotes.price_hong !=']  = 0;
            $params['price_type'] == 2 && $conditions['Quotes.price_water !='] = 0;
        }

        //存在地区筛选项且无满足该条件的商户
        if (isset($merchant_ids) && empty($merchant_ids)) {
            $merchants = [];
        } else {
            isset($merchant_ids) && $conditions['Merchants.id in'] = $merchant_ids;

            $merchants = $this->loadModel('Quotes')
                ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
                ->map(function ($row) {
                    $conditions = ['merchant_id' => $row->merchant_id, 'address is not null'];
                    $location   = $this->loadModel('MerchantLocations')->find('all', [
                        'conditions' => $conditions,
                    ])->first();
                    if ($location) {
                        $row->address = $location->address;
                    }

                    return $row;
                })
                ->toArray();
        }

        $this->ret(0, $merchants, '加载成功');
    }
    public function setLike($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失');
        }
        $params     = $this->request->getData();
        $fan_id     = $params['pkey'];
        $type       = $params['type'];
        $conditions = compact('product_id', 'fan_id');
        if ($type === 'dislike') {
            $this->loadModel('Likes')->deleteAll($conditions);
        } else {
            $like = $this->loadModel('Likes')->find('all')->where($conditions)->first();
            if (!$like) {
                $conditions['created'] = date('Y-m-d H:i:s');
                $this->loadModel('Likes')->query()->insert(['fan_id', 'product_id', 'created'])->values($conditions)->execute();
            }
        }
        //更新产品数据统计
        $this->setProductMetaData($product_id,['collect_count' => 'collect_count'+1]);
        $this->ret(0, 1, '加载成功');
    }
    public function commentLists($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失');
        }
        $params     = $this->request->getData();
        $fields     = ['fan_name' => 'Fans.nickName', 'fan_avatar' => 'Fans.avatarUrl', 'created' => 'Comments.created', 'rating' => 'Comments.rating', 'content' => 'Comments.content'];
        $conditions = ['product_id' => $product_id,'is_checked' => 1];
        $contain    = ['Fans'];
        $order      = ['Comments.sort desc', 'Comments.id desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        $comments = $this->loadModel('Comments')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->map(function ($row) {
                $row->created = (new Time($row->created))->i18nFormat('yyyy-MM-dd');
                return $row;
            })
            ->toArray();
        $this->ret(0, $comments, '加载成功');
    }
    public function addComment($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失'); 
        }
        $params = $this->request->getData();

        if ((!isset($params['content'])) || strlen($params['content']) < 10) {
            $this->ret(0, 0, '评价内容必填');
        }
        $fan_id  = $params['pkey'];
        $rating  = $params['rating'];
        $content = $params['content'];
        $created = date('Y-m-d H:i:s');
        $fields  = ['product_id', 'fan_id', 'rating', 'content', 'created'];
        $this->loadModel('Comments')->query()->insert($fields)->values(compact($fields))->execute();
        //更新产品数据统计
        $this->setProductMetaData($product_id,['comment_count' => 'comment_count'+1]);
        $this->ret(0, 1, '提交成功');
    }
    //获取产品图片文件夹
    private function _getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
    //获取产品图片文件夹
    private function getLogoDir($merchant_id)
    {
        return intval($merchant_id / 100) . '00' . '/';
    }
    private function setProductMetaData($product_id,$data)
    {
        $conditions = compact('product_id');
        $metaData = $this->loadModel('ProductData')->find('all')->where($conditions)->first();
        $query = $this->ProductData->query();
        if($metaData){
            $query->update()->set($data)->where($conditions)->execute();
        }else{
            $values = array_merge(['view_count' =>0,'collect_count'=>0,'comment_count'=>0],$data,$conditions);
            $query->insert(['view_count','collect_count','comment_count','product_id'])->values($values)->execute();
        }
    }
}
 