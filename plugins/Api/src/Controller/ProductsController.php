<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\I18n\Time;

/**
 * Products Controller
 */
class ProductsController extends AppController
{
    public function lists()
    {
        $params     = $this->request->getData();
        $fields     = ['Products.id', 'Products.name', 'Products.album', 'Products.price_hong_min', 'Products.price_hong_max', 'Products.price_water_min', 'Products.price_water_max', 'Products.is_hot', 'Products.is_new',];
        $conditions = ['Products.is_visible' => 1, 'Categories.is_visible' => 1];
        $contain    = ['Categories'];
        $order      = ['Products.sort'=> 'desc', 'Products.is_hot'=>'desc', 'Products.is_new'=>'desc', 'Products.id'=>'desc'];
        $limit      = 20;
        $offset     = $this->getOffset(isset($params['page']) ? $params['page'] : 1, $limit);

        if (isset($params['category_id']) && $params['category_id']) {
            $conditions['Products.category_id'] = $params['category_id'];
        }
        //最新更新
        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'last':
                    $order = array_merge(['Products.created' => 'Desc'], $order);
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
                if(!empty($price_range[0])){
                    $conditions['or'][0]['Products.price_hong_min >='] = $price_range[0];
                    $conditions['or'][1]['Products.price_water_min >='] = $price_range[0];
                }
                if(!empty($price_range[1])){
                    $conditions['or'][0]['Products.price_hong_max <='] = $price_range[1];
                    $conditions['or'][1]['Products.price_water_max <='] = $price_range[1];
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
                    $order = array_merge(['ProductData.collect_count' => 'Desc'], $order);
                    break;

                case 'newest':
                    $order = array_merge(['Products.created' => 'Desc'], $order);
                    break;
            }
        }
        //关键词存在
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $conditions['Products.name like'] = '%' . $params['keyword'] . '%';
            //保存记录
            $keyword = $this->loadModel('Keywords')->find('all', [
                'conditions' => ['name' => $params['keyword']],
            ])->first() ?: $this->loadModel('Keywords')->newEntity(['name' => $params['keyword'], 'count' => 0,'is_visible' => 1]);
            $keyword->count = $keyword->count + 1;

            $this->loadModel('Keywords')->save($keyword);
        }
        //最近浏览
        if (isset($params['product_ids']) && is_array($params['product_ids']) && count($params['product_ids'])) {
            $conditions['Products.id in'] = $params['product_ids'];
        }
        $products = $this->loadModel('Products')
            ->find('all', compact('fields', 'conditions', 'contain', 'order', 'offset', 'limit'))
            ->leftJoinWith('ProductData')
            ->map(function ($row) {
                $row->cover = $this->_getProductCover($row->id, $row->album);
                return $row;
            })
            ->toArray();
        $this->ret(0, $products, '加载成功');
    }
    public function detail($id)
    {
        if (empty($id)) {
            $this->ret(1, null, '产品id缺失');
        }
        $params = $this->request->getData();
        $fan = $this->_getFanFormPkey($params['pkey']);
        $pkey = $fan['pkey'];
        $fan_id  = $fan['id'];
        $product = $this->loadModel('Products')->find('all', [
            'conditions' => ['Products.id' => $id, 'Products.is_visible' => 1],
        ])->first();
        if (empty($product)) {
            $this->ret(1, null, '产品不存在或已被删除');
        }
        //更新产品数据统计
        $this->setProductMetaData($id, ['view_count' => 1]);
        $product->albums    = $this->_getProductAlbumUrl($product->id, $product->album);
        $product->meta_data = $this->loadModel('ProductData')->find('all', [
            'conditions' => ['product_id' => $product->id],
        ])->first();
        $product->commented = $this->loadModel('Comments')->find('all', [
            'conditions' => ['product_id' => $product->id, 'fan_id' => $fan_id],
        ])->count();
        $product->liked = $this->loadModel('Likes')->find('all', [
            'conditions' => ['product_id' => $product->id, 'fan_id' => $fan_id],
        ])->count();
        $product->attributes = $this->loadModel('ProductsAttributes')->find('all', [
            'conditions' => ['ProductsAttributes.product_id' => $id, 'CategoriesAttributes.is_visible' => 1],
            'contain'    => ['CategoriesAttributes'],
            'fields'     => ['value' => 'ProductsAttributes.value', 'attribute_id' => 'CategoriesAttributes.attribute_id'],
        ])->map(function ($row) {
            $row->attribute_name = $this->loadModel('Attributes')->find()->where(['id' => $row->attribute_id])->first()->name;
            return $row;
        });
        $this->ret(0, $product, '产品加载成功');
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
        $limit      = 100;
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
                        $row->address                            = $location->address;
                        $location->latitude && $row->latitude    = $location->latitude;
                        $location->longtitude && $row->longitude = $location->longtitude;
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
        $fan = $this->_getFanFormPkey($params['pkey']);
        $fan_id = $fan['id'];
        $type       = $params['type'];
        $conditions = compact('product_id', 'fan_id');
        if ($type === 'dislike') {
            $delt = -1;
            $this->loadModel('Likes')->deleteAll($conditions);
        } else {
            $delt = 1;
            $like = $this->loadModel('Likes')->find('all')->where($conditions)->first();
            if (!$like) {
                $conditions['created'] = date('Y-m-d H:i:s');
                $this->loadModel('Likes')->query()->insert(['fan_id', 'product_id', 'created'])->values($conditions)->execute();
            }
        }
        //更新产品数据统计
        $this->setProductMetaData($product_id, ['collect_count' => $delt]);
        $this->ret(0, ['pkey' => $fan['pkey']], '加载成功');
    }
    public function commentLists($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失');
        }
        $params     = $this->request->getData();
        $fields     = ['fan_name' => 'Fans.nickName', 'fan_avatar' => 'Fans.avatarUrl', 'created' => 'Comments.created', 'rating' => 'Comments.rating', 'content' => 'Comments.content'];
        $conditions = ['product_id' => $product_id, 'is_checked' => 1];
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
        $fan = $this->_getFanFormPkey($this->request->getData('pkey'));
        $pkey = $fan['pkey'];
        $fan_id  = $fan['id'];

        $rating  = $params['rating'];
        $content = $params['content'];
        $created = date('Y-m-d H:i:s');
        $comment_need_check = $this->redis->read('config.comment_need_check');
        if(empty($comment_need_check)){
            $comment_need_check = $this->loadModel('Configs')->findByName('comment_need_check')->first()->value;
            $this->redis->write('config.comment_need_check',$comment_need_check);
        }
        
        $is_checked =  $comment_need_check?-1:1;
        $fields  = ['product_id', 'fan_id', 'rating', 'content', 'created','is_checked'];
        $this->loadModel('Comments')->query()->insert($fields)->values(compact($fields))->execute();

        $this->ret(0, compact('pkey'), '提交成功');
    }
    public function shareCount($product_id)
    {
        if (empty($product_id)) {
            $this->ret(1, null, '产品id缺失');
        }
        // //更新产品数据统计
        $this->setProductMetaData($product_id, ['share_count' => 1]);
        $this->ret(0, 1, '提交成功');
    }
    public function hotKeywordLists()
    {
        $keywords = $this->loadModel('Keywords')->find('all', [
            'conditions' => ['is_visible' => 1],
            'limit'      => 10,
            'offset'     => 0,
            'order'      => ['sort desc','count desc', 'id desc'],
        ])->extract('name')->toArray();
        $this->ret(0, $keywords, '加载成功');
    }
}
