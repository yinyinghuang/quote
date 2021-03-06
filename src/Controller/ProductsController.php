<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 *
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{

    //产品列表
    public function index()
    {
        $tableParams = [
            'name'        => 'products',
            'renderUrl'   => '/products/api-lists',
            'deleteUrl'   => '/products/api-delete',
            'editUrl'     => '/products/api-save',
            'addUrl'      => '/products/add',
            'viewUrl'     => '/products/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'产品\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
                ['field' => '\'category_name\'', 'title' => '\'分类\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.category_id+\'">\'+res.category_name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
                ['field' => '\'zone_name\'', 'title' => '\'空间\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.zone_id+\'">\'+res.zone_name+\'</a>\')'],
                ['field' => '\'is_new\'', 'title' => '\'新品\'', 'unresize' => true, 'templet' => '\'#switchTpl_1\''],
                ['field' => '\'is_hot\'', 'title' => '\'热门\'', 'unresize' => true, 'templet' => '\'#switchTpl_2\''],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_1', 'name' => 'is_new', 'text' => '是|否'],
                ['id' => 'switchTpl_2', 'name' => 'is_hot', 'text' => '是|否'],
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['products' => $tableParams];
        $category_select = $this->getCasecadeTplParam('category_select',[],true);
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams', 'category_select'));
    }

    //浏览产品详情
    public function view($id = null)
    {
        $product = $this->Products->get($id);
        $product->category_select = $this->getCasecadeTplParam('category_select',[
            'zone'     => [
                'zone_id'  => $product->zone_id,
            ],
            'group'    => [
                'group_id' => $product->group_id,
            ],
            'category' => [
                'category_id' => $product->category_id,
            ],
        ]);
        $albumDir        = $this->getAlbumDir($id);
        $product->albums = [];

        if ($product->album) {
            $product->album = json_decode($product->album, true);
            foreach ($product->album as $key => $album) {
                $product->albums[] = [
                    'thumb'  => '/album/product/' . $albumDir . $product->id . '_' . $album[0] . '_2.' . $album[1],
                    'middle' => '/album/product/' . $albumDir . $product->id . '_' . $album[0] . '_4.' . $album[1],
                    'full'   => '/album/product/' . $albumDir . $product->id . '_' . $album[0] . '_0.' . $album[1],
                ];
            }
        } else {
            $product->albums = [];
        }

        // 产品属性值
        $product->attributes = $this->getProductAttr($product->id);
        // 分类属性名，分类筛选项
        list($cateAttrs, $cateAttrFilterOptions) = $this->getCategoryAttr($product->category_id);
        // 产品筛选项
        $product->filter = $product->filter ? explode(',', substr($product->filter, 0, -1)) : [];
        //产品报价
        $product->quoteCount = $this->Products->Quotes->find()->where(['product_id' => $product->id])->count();
        $quoteTableParams    = [
            'name'        => 'quotes',
            'renderUrl'   => '/quotes/api-lists?product_id=' . $product->id,
            'deleteUrl'   => '/quotes/api-delete',
            'editUrl'     => '/quotes/api-save',
            'addUrl'      => '/quotes/add?product_id=' . $product->id,
            'viewUrl'     => '/quotes/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'merchant_name\'', 'title' => '\'商户\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/merchants/view/\'+res.merchant_id+\'">\'+res.merchant_name+\'</a>\')'],
                ['field' => '\'price_hong\'', 'title' => '\'行货价格\'', 'edit' => '\'number\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'price_water\'', 'title' => '\'水货价格\'', 'edit' => '\'number\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
                ['field' => '\'modified\'', 'title' => '\'更新时间\'', 'minWidth' => 200, 'unresize' => true, 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        $district_select =$this->getCasecadeTplParam('district_select',[],true);
        //产品评论
        $product->commentCount = $this->Products->Comments->find()->where(['product_id' => $product->id])->count();
        $commentTableParams    = [
            'name'        => 'comments',
            'renderUrl'   => '/comments/api-lists?product_id=' . $product->id,
            'deleteUrl'   => '/comments/api-delete',
            'editUrl'     => '/comments/api-save',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'fan_name\'', 'title' => '\'粉丝\'', 'fixed' => '\'left\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/fans/view/\'+res.fan_id+\'">\'+res.fan_name+\'</a>\')'],
                ['field' => '\'content\'', 'title' => '\'内容\'', 'minWidth' => 280, 'unresize' => true, ],
                ['field' => '\'rating\'', 'title' => '\'评级\'', 'unresize' => true,],
                ['field' => '\'is_checked\'', 'title' => '\'审核通过\'', 'unresize' => true, 'templet' => '\'#switchTpl_4\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
                ['field' => '\'created\'', 'title' => '\'评论时间\'', 'unresize' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_4', 'name' => 'is_checked', 'text' => '是|否']],
        ];

        $tableParams = ['quotes' => $quoteTableParams, 'comments' => $commentTableParams];
        $active      = $this->request->query('active');
        $this->set(compact('product', 'category_select', 'product_attributes', 'cateAttrs', 'cateAttrFilterOptions', 'tableParams', 'district_select','active'));
    }

    //获取产品图片文件夹
    private function getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }

    //获取产品属性值
    private function getProductAttr($product_id)
    {
        //产品属性
        $product_attributes = $this->Products->ProductsAttributes->find('all', [
            'conditions' => ['product_id' => $product_id],
            'fields'     => ['category_attribute_id', 'value'],

        ])
            ->combine('category_attribute_id', 'value')
            ->toArray();
        return $product_attributes;
    }

    //获取分类的属性键值,及为筛选项的属性键
    private function getCategoryAttr($category_id)
    {
        //分类下属性
        $cateAttrs = $this->loadModel('CategoriesAttributes')->find('all', [
            'contain'    => ['Attributes'],
            'conditions' => ['category_id' => $category_id],
            'fields'     => [
                'cateAttrId' => 'CategoriesAttributes.id',
                'name'       => 'Attributes.name',
            ],
        ])
            ->combine('cateAttrId', 'name')
            ->toArray();

        //分类下为筛选项的属性
        $cateFilterAttrs = $this->CategoriesAttributes->find('all', [
            'contain'    => ['Attributes'],
            'conditions' => [
                'category_id' => $category_id,
                'is_filter' => 1,
            ],
            'fields'     => [
                'cateAttrId' => 'CategoriesAttributes.id',
            ],
        ])
            ->extract('cateAttrId')
            ->toArray();
        //属性筛选值
        $cateAttrFilterOptions = empty($cateFilterAttrs) ? []:$this->loadModel('CategoryAttributeFilters')->find('all', [
            'conditions' => [
                'category_attribute_id in ' => $cateFilterAttrs,
            ],
            'fields'     => [
                'cateAttrId' => 'CategoryAttributeFilters.category_attribute_id',
                'option_id'  => 'CategoryAttributeFilters.id',
                'option'     => 'CategoryAttributeFilters.filter',
            ],
            'order'      => ['sort desc'],
        ])
            ->groupBy('cateAttrId')
            ->toArray();

        return [$cateAttrs, $cateAttrFilterOptions];
    }

    //新增产品
    public function add()
    {
        $product         = $this->Products->newEntity();
        $zone_id  = $group_id  = $category_id  = null;
        $params   = $this->request->query();
        if (isset($params['category_id']) && $params['category_id']) {
            $category = $this->Products->Categories->find()->where(['id' => $params['category_id']])->first();
            if ($category) {
                $zone_id  = $category->zone_id;
                $group_id  = $category->group_id;
                $category_id = $category->id;
            }
        } elseif (isset($params['group_id']) && $params['group_id']) {
            $group = $this->Products->Groups->find()->where(['id' => $params['group_id']])->first();
            if ($group) {
                $zone_id = $group->zone_id;
                $group_id = $group->id;
            }
        } elseif (isset($params['zone_id']) && $params['zone_id']) {
            $zone = $this->Products->Zones->find()->where(['id' => $params['zone_id']])->first();
            if ($zone) {
                $zone_id = $zone->id;
            }
        }
        $product->category_select = $this->getCasecadeTplParam('category_select', [
            'zone'     => [
                'zone_id' => $zone_id,
            ],
            'group'    => [
                'group_id' => $group_id,
            ],
            'category' => [
                'category_id' => $category_id,
            ],
        ]);
        $product->albums = $product->filter = [];
        
        $this->set(compact('product'));
        $this->render('view');

    }

    //新增产品过程中ajax获取属性内容
    public function apiGetCategoryAttr()
    {
        $msg_arr     = ['加载完成', '参数cid不存在', '分类不存在或已删除'];
        $paramFn     = $this->request->is('get') ? 'getQuery' : 'getData';
        $category_id = $this->request->$paramFn('cid');

        if (!$category_id) {
            $this->resApi(0, 1, $msg_arr[1]);
        } elseif (!$this->Products->Categories->find()->where(['id' => $category_id])->count()) {
            $this->resApi(0, 2, $msg_arr[2]);
        }
        list($cateAttrs, $cateAttrFilterOptions) = $this->getCategoryAttr($category_id);
        $this->resApi(0, ['cateAttrs' => $cateAttrs, 'cateAttrFilterOptions' => $cateAttrFilterOptions], '加载完成');
    }

    //ajax修改产品
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数pid缺失', '记录不存在或已删除', '内容填写有误', '图片保存失败'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $product = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Products->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Products->newEntity();

        if (!$product) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        unset($params['search']);
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible']                       = isset($params['is_visible']) ? $params['is_visible'] : 0;
            $params['is_new']                           = isset($params['is_new']) ? $params['is_new'] : 0;
            $params['is_hot']                           = isset($params['is_hot']) ? $params['is_hot'] : 0;
            isset($params['filter']) ? $product->filter = implode(',', $params['filter']) . ',' : $product->filter = null;

        }
        unset($params['filter']);
        
        if (!$product->pid) {
            $product->pid = $this->getPid();
        }
        $product = $this->Products->patchEntity($product, $params);
        $data    = $this->Products->save($product) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($product->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] =$name.':'.implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }
        
        //详情编辑页面提交请求
        if (isset($params['detail']) && $params['detail']) {
            //更新所属分类的最高最低值
            $category = $this->Products->Categories->find()->where(['id' => $product->category_id])->first();
            $price_max = max($product->price_hong_max,$product->price_water_max);
            $price_min = min($product->price_hong_min,$product->price_water_min);
            if($price_max>$category->price_max || $price_min<$category->price_min){
                $category->price_max = $price_max;
                $category->price_min = $price_min;
                $this->Products->Categories->save($category);
            }
            //商户图片被修改
            if (isset($params['albums']) && is_array($params['albums']) && !empty($params['albums']) && !isset($params['albums']['error'])) {
                $albumArr = $this->saveAlbums($params['albums'], $product->id);

                if (!empty($albumArr)) {
                    if ($product->album) {
                        $product->album = json_encode(array_merge(json_decode($product->album, true), $albumArr));
                    } else {
                        $product->album = json_encode($albumArr);
                    }

                    $data = $this->Products->save($product) ? 0 : 4;
                }

            }
            //保存属性
            if (isset($params['categories_attributes'])) {
                $productAttrQuery = $this->Products->ProductsAttributes->query();
                foreach ($params['categories_attributes'] as $category_attribute_id => $value) {
                    $entity = $this->Products->ProductsAttributes
                        ->find()
                        ->where([
                            'product_id'            => $product->id,
                            'category_attribute_id' => $category_attribute_id,
                        ])
                        ->first();

                    //不存在相同的属性值
                    if (!(($entity && $entity->value == $value) || (!$entity && !$value))) {
                        $this->Products->ProductsAttributes
                            ->deleteAll([
                                'product_id'            => $product->id,
                                'category_attribute_id' => $category_attribute_id,
                            ]);

                        $value && $productAttrQuery
                            ->insert(['product_id', 'category_attribute_id', 'value'])
                            ->values(['product_id'  => $product->id,
                                'category_attribute_id' => $category_attribute_id,
                                'value'                 => $value,
                            ]);
                    }

                }
                $productAttrQuery->execute();
            } else {
                //参数为空，删除所有属性值
                $this->Products->ProductsAttributes->deleteAll([
                    'product_id' => $product->id,
                ]);
            }
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }

    //保存产品图片
    private function saveAlbums($albums, $product_id)
    {
        $albumArr = [];
        $path     = PRO_ROOT . $this->getAlbumDir($product_id);
        foreach ($albums as $album) {
            if ($album['error'] === 0) {
                $ext = 'png';

                do {
                    $name          = $this->generateRandomStr();
                    $idtf          = $product_id . '_' . $name . '_';
                    $fullAlbumPath = $path . $idtf . '0.' . $ext;
                } while (file_exists($fullAlbumPath));

                if (move_uploaded_file($album['tmp_name'], $fullAlbumPath)) {
                    $this->watermarkPrint($idtf, $path);
                    $albumArr[] = [$name, 'png'];
                }

            }

        }
        return $albumArr;
    }


    //为产品图片加水印及保存缩略图
    private function watermarkPrint($idtf, $path)
    {

        ini_set('memory_limit', -1);
        $ext            = 'png';
        $watermark_path = PRO_ROOT . 'watermark.png';
        $src_path       = $path . $idtf . '0.' . $ext;
        $src            = imagecreatefromstring(file_get_contents($src_path));
        $watermark      = imagecreatefromstring(file_get_contents($watermark_path));
        //获取水印图片的宽高
        list($watermark_w, $watermark_h) = getimagesize($watermark_path);
        list($src_w, $src_h)             = getimagesize($src_path);

        $pos_x = intval(($src_w - $watermark_w) / 2);
        $pos_y = intval(($src_h - $watermark_h) / 2);
        imagecopy($src, $watermark, $pos_x, $pos_y, 0, 0, $watermark_w, $watermark_h);

        #存为大图

        $dst_full_path = $path . $idtf . '0.' . $ext;
        imagepng($src, $dst_full_path);
        imagedestroy($watermark);

        #存为小图
        $dst_small_path = $path . $idtf . '2.' . $ext;
        $dst_w          = 100;
        $dst_h          = 100;
        $src_small_im   = $this->resizeImageObject($src, $dst_w, $dst_h, $src_w, $src_h);

        imagepng($src_small_im, $dst_small_path);
        imagedestroy($src_small_im);

        #存为中图
        $dst_middle_path = $path . $idtf . '4.' . $ext;
        $dst_w           = 200;
        $dst_h           = 200;
        $src_middle_im   = $this->resizeImageObject($src, $dst_w, $dst_h, $src_w, $src_h);

        imagepng($src_middle_im, $dst_middle_path);
        imagedestroy($src_middle_im);

        imagedestroy($src);

    }

    //修改图片尺寸
    private function resizeImageObject($src_im, $dst_w, $dst_h, $src_w, $src_h)
    {
        $target = imagecreatetruecolor($dst_w, $dst_h);
        imagecopyresampled($target, $src_im, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        return $target;
    }

    //ajax删除产品
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');
        
        if (count($ids) == 0) {
            $code = 2;
            $this->resApi(0, compact('code'), $msg_arr[$code]);
        }

        //删除产品图片本地文件
        $products = $this->Products
            ->find()
            ->select(['album', 'id'])
            ->where(['id in ' => $ids, 'album is not null']);
        foreach ($products as $product) {
            $albums = json_decode($product->album, true);
            foreach ($albums as $album) {
                $this->deleteAlbumFile($album, $product->id);
            }
        }

        $code = count($ids) ? ($this->Products->deleteAll(['id in' => $ids]) ? 0 : 1) : 2;
        if ($code != 0) {
            $this->resApi(0,compact('code'), $msg_arr[$code]);
        }

        //删除属性值
        $this->Products->ProductsAttributes->deleteAll(['product_id in' => $ids]);
        $code = 0;

        $this->resApi(0,compact('code','ids'), $msg_arr[$data['code']]);
    }

    //ajax删除产品图片
    public function apiDeleteAlbum()
    {
        $this->allowMethod(['POST']);
        $msg_arr    = ['删除完成', '参数pid缺失', '参数album缺失', '记录不存在或已删除', '删除失败，刷新页面再重试'];
        $product_id = $this->request->getQuery('pid');
        if (!$product_id) {
            $this->resApi(0, 1, $msg_arr[1]);
        }
        $img = $this->request->getData('img');
        if (!$img) {
            $this->resApi(0, 2, $msg_arr[2]);
        }

        $product = $this->Products->find()->where(['id' => $product_id])->first();
        if (!$product) {
            $this->resApi(0, 3, $msg_arr[3]);
        }

        $albums = json_decode($product->album, true);

        $exists = false;

        foreach ($albums as $key => $album) {

            if ($img == $album[0]) {
                $exists = true;
                unset($albums[$key]);
                $this->deleteAlbumFile($album, $product->id);
                break;
            }
        }

        if ($exists) {
            $albums         = array_splice($albums, 0, count($albums));
            $product->album = count($albums) ? json_encode($albums) : null;
            $this->Products->save($product);
        }
        $res = 0;
        $this->resApi(0, $res, $msg_arr[$res]);
    }

    //删除产品图片本地文件
    private function deleteAlbumFile($album, $product_id)
    {
        $albumDir = $this->getAlbumDir($product_id);
        foreach ([0, 2, 4] as $key) {
            $file = new File(PRO_ROOT . $albumDir . $product_id . '_' . $album[0] . '_' . $key . '.' . $album[1]);
            $file->delete();
        }
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'            => 'Products.id',
                'name'          => 'Products.name',
                'category_name' => 'Categories.name',
                'category_id'   => 'Categories.id',
                'group_name'    => 'Groups.name',
                'group_id'      => 'Groups.id',
                'zone_name'     => 'Zones.name',
                'zone_id'       => 'Zones.id',
                'is_new'        => 'Products.is_new',
                'is_hot'        => 'Products.is_hot',
                'album'         => 'Products.album',
                'brand'         => 'Products.brand',
                'is_visible'    => 'Products.is_visible',
                'sort'          => 'Products.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Products.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Products.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['brand']) && trim($params['brand'])) {
                    $where['Products.brand'] = trim($params['brand']);
                }
                if (isset($params['price_hong_min']) && floatval($params['price_hong_min'])) {
                    $where['Products.price_hong_min >='] = floatval($params['price_hong_min']);
                }
                if (isset($params['price_hong_max']) && floatval($params['price_hong_max'])) {
                    $where['Products.price_hong_max <'] = floatval($params['price_hong_max']);
                }
                if (isset($params['price_water_min']) && floatval($params['price_water_min'])) {
                    $where['Products.price_water_min >='] = floatval($params['price_water_min']);
                }
                if (isset($params['price_water_max']) && floatval($params['price_water_max'])) {
                    $where['Products.price_water_max <'] = floatval($params['price_water_max']);
                }

                if (isset($params['category_id']) && intval($params['category_id'])) {
                    $where['Products.category_id'] = intval($params['category_id']);
                } elseif (isset($params['group_id']) && intval($params['group_id'])) {
                    $where['Products.group_id'] = intval($params['group_id']);
                } elseif (isset($params['zone_id']) && intval($params['zone_id'])) {
                    $where['Products.zone_id'] = intval($params['zone_id']);
                }
                if (isset($params['is_new']) && in_array($params['is_new'], [1, 0])) {
                    $where['Products.is_new'] = $params['is_new'];
                }
                if (isset($params['is_hot']) && in_array($params['is_hot'], [1, 0])) {
                    $where['Products.is_hot'] = $params['is_hot'];
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Products.is_visible'] = $params['is_visible'];
                }
            }

            $contain = ['Zones', 'Groups', 'Categories'];

            $order = ['Products.sort' => 'desc', 'Products.created' => 'desc', 'Products.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
    }

    //返回联动多级分类
    /**
     *
     * @return [
     *           selected_zone_id/selected_group_id/selected_category_id当前值
     *          zones/groups/categorys:select选项
     *           ]
     */
    public function apiGetCategorySelect()
    {
        $msg_arr = ['加载完成', '未选中', '记录不存在或已删除', 'type错误'];

        $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
        $params  = $this->request->$paramFn();

        $data = [
            'zone'     => [
                'selected' => 0,
                'list'     => [],
            ],

            'group'    => [
                'selected' => 0,
                'list'     => [],
            ],

            'category' => [
                'selected' => 0,
                'list'     => [],
            ],
        ];

        $data[$params['type']]['selected'] = $params['pid'];
        $data['zone']['list']              = $this->Products->Zones->find('list');
        $where_g                           = $where_c                           = [];
        switch ($params['type']) {
            case 'zone':
                if ($data['zone']['selected']) {
                    $zone = $this->Products->Zones->find()->where(['id' => $data['zone']['selected']])->first();
                    if (!$zone) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $where_c = $where_g = ['zone_id' => $data['zone']['selected']];
                }
                break;
            case 'group':
                if ($data['group']['selected']) {
                    $group = $this->Products->Groups->find()->where(['id' => $data['group']['selected']])->first();
                    if (!$group) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $data['zone']['selected'] = $group->zone_id;

                } else {
                    $data['zone']['selected'] = $params['origin']['zone'];
                }
                if ($data['zone']['selected']) {
                    $where_g = $where_c = ['zone_id' => $data['zone']['selected']];
                }

                break;
            case 'category':
                if ($data['category']['selected']) {

                    $category = $this->Products->Categories->find()
                        ->where(['Categories.id' => $data['category']['selected']])
                        ->first();
                    if (!$category) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $data['group']['selected'] = $category->group_id;
                    $data['zone']['selected']  = $category->zone_id;
                } else {

                    $data['zone']['selected']                               =  $params['origin']['zone'];
                    $data['zone']['selected'] && $data['group']['selected'] = $params['origin']['group'];
                }

                $data['zone']['selected'] && $where_g = ['zone_id' => $data['zone']['selected']];

                $where_c = $data['group']['selected'] ?
                ['group_id' => $data['group']['selected']] : $data['zone']['selected'] ?
                ['zone_id' => $data['zone']['selected']] :
                [];

                break;
            default:
                $this->resApi(0, 3, $msg_arr[3]);
        }

        $data['group']['list']    = $this->Products->Groups->find('list')->where($where_g);
        $data['category']['list'] = $this->Products->Categories->find('list')->where($where_c);
        $this->resApi(0, $data, $msg_arr[0]);
    }

}
