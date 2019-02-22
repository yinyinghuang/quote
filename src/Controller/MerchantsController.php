<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Filesystem\File;

/**
 * Merchants Controller
 *
 * @property \App\Model\Table\MerchantsTable $Merchants
 *
 * @method \App\Model\Entity\Merchant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MerchantsController extends AppController
{

    //列表
    public function index()
    {
        $tableParams = [
            'name'        => 'merchants',
            'renderUrl'   => '/merchants/api-lists',
            'deleteUrl'   => '/merchants/api-delete',
            'editUrl'     => '/merchants/api-save',
            'addUrl'      => '/merchants/add',
            'viewUrl'     => '/merchants/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'商户\'', 'minWidth' => 350, 'fixed' => '\'left\'', 'edit' => '\'text\'', 'unresize' => true],
                ['field' => '\'quote_count\'', 'title' => '\'在售产品\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/merchants/view/\'+res.id+\'?active=quotes">\'+res.quote_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams     = ['merchants' => $tableParams];
        $district_select = $this->getCasecadeTplParam('district_select', [], true);
        $this->set(compact('tableParams', 'district_select'));
    }

    //浏览产品详情
    public function view($id = null)
    {
        $merchant        = $this->Merchants->get($id);
        $logoDir         = $this->getLogoDir($id);
        $merchant->logos = [];

        if ($merchant->logo) {
            $merchant->logos = [
                'thumb'  => '/album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_2.' . $merchant->logo_ext,
                'middle' => '/album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_4.' . $merchant->logo_ext,
                'full'   => '/album/merchant/' . $logoDir . $merchant->id . '_' . $merchant->logo . '_0.' . $merchant->logo_ext,
            ];
        } else {
            $merchant->logos = [];
        }

        //产品报价
        $merchant->locationCount = $this->Merchants->MerchantLocations->find()->where(['merchant_id' => $merchant->id])->count();
        $locationTableParams     = [
            'name'        => 'merchant-locations',
            'renderUrl'   => '/merchant-locations/api-lists?merchant_id= ' . $merchant->id . '&search[merchant_id]=' . $merchant->id,
            'deleteUrl'   => '/merchant-locations/api-delete',
            'editUrl'     => '/merchant-locations/api-save',
            'addUrl'      => '/merchant-locations/add?merchant_id=' . $merchant->id,
            'viewUrl'     => 'javascript:;',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'address\'', 'title' => '\'地址\'', 'minWidth' => 350, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'area_name\'', 'title' => '\'区\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/areas/view/\'+res.area_id+\'">\'+res.area_name+\'</a>\')'],
                ['field' => '\'district_name\'', 'title' => '\'区域\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/districts/view/\'+res.district_id+\'">\'+res.district_name+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        //产品报价
        $merchant->quoteCount = $this->Merchants->Quotes->find()->where(['merchant_id' => $merchant->id])->count();
        $quoteTableParams     = [
            'name'        => 'quotes',
            'renderUrl'   => '/quotes/api-lists?merchant_id= ' . $merchant->id . '&search[merchant_id]=' . $merchant->id,
            'deleteUrl'   => '/quotes/api-delete',
            'editUrl'     => '/quotes/api-save',
            'addUrl'      => '/quotes/add?merchant_id=' . $merchant->id,
            'viewUrl'     => '/quotes/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'product_name\'', 'title' => '\'商品名称\'', 'fixed' => '\'left\'', 'minWidth' => 280, 'unresize' => true, 'templet' => '(res) => (\'<a href="/products/view/\'+res.product_id+\'">\'+res.product_name+\'</a>\')', 'sort' => true],
                ['field' => '\'price_hong\'', 'title' => '\'行货价格\'', 'edit' => '\'number\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'price_water\'', 'title' => '\'水货价格\'', 'edit' => '\'number\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
                ['field' => '\'modified\'', 'title' => '\'更新时间\'', 'minWidth' => 200, 'unresize' => true, 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        $district_select = $this->getCasecadeTplParam('district_select', [], true);
        $tableParams     = ['quotes' => $quoteTableParams];

        $areas     = $this->loadModel('Areas')->find('list');
        $districts = $this->loadModel('Districts')->find('list');
        $active    = $this->request->query('active');
        $this->set(compact('merchant', 'tableParams', 'district_select', 'locationTableParams', 'areas', 'districts', 'active'));
    }

    //添加
    public function add()
    {
        $merchant            = $this->Merchants->newEntity();
        $locationTableParams = [
            'name'        => 'merchant-locations',
            'renderUrl'   => '/merchant-locations/api-lists?merchant_id=0',
            'deleteUrl'   => '/merchant-locations/api-delete',
            'editUrl'     => '/merchant-locations/api-save',
            'addUrl'      => '/merchant-locations/add?merchant_id=0',
            'viewUrl'     => 'javascript:;',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'address\'', 'title' => '\'地址\'', 'minWidth' => 350, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'area_name\'', 'title' => '\'区\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/areas/view/\'+res.area_id+\'">\'+res.area_name+\'</a>\')'],
                ['field' => '\'district_name\'', 'title' => '\'区域\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/districts/view/\'+res.district_id+\'">\'+res.district_name+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否']],
        ];
        $district_select = $this->getCasecadeTplParam('district_select', [], true);
        $areas           = $this->loadModel('Areas')->find('list');
        $districts       = $this->loadModel('Districts')->find('list');
        $this->set(compact('merchant', 'locationTableParams', 'district_select', 'areas', 'districts'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数mid缺失', '记录不存在或已删除', '内容填写有误'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['id']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        $merchant = (isset($params['id']) && $params['id'] && $params['type'] == 'edit') ? $this->Merchants->find('all')
            ->where(['id' => $params['id']])
            ->first() : $this->Merchants->newEntity();
        if (!$merchant) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $merchant = $this->Merchants->patchEntity($merchant, $params);
        if (!$merchant->pid) {
            $merchant->pid = $this->getPid();
        }

        $data = $this->Merchants->save($merchant) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($merchant->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] = $name . ':' . implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }
        //详情编辑页面提交请求
        if (isset($params['detail']) && $params['detail']) {

            //保存图片
            if (isset($params['logoImage']) && is_array($params['logoImage']) && !empty($params['logoImage']) && $params['logoImage']['error'] == 0) {
                $logoInfo = $this->saveLogo($params['logoImage'], $merchant->id);

                if (!empty($logoInfo)) {
                    $merchant->logo && $this->deleteLogoFile($merchant->logo, $merchant->logo_ext, $merchant->id);
                    $merchant = $this->Merchants->patchEntity($merchant, $logoInfo);
                }

            }
            //保存商户地址
            if (isset($params['location']) && !empty($params['location'])) {

                $locationQuery = $this->Merchants->MerchantLocations
                    ->query()
                    ->insert(['merchant_id', 'address', 'latitude', 'longtitude', 'openhour', 'contact', 'area_id', 'district_id', 'sort', 'is_visible', 'pid']);
                $pid = $this->getPid('MerchantLocations');
                foreach ($params['location'] as $location) {
                    list($latitude, $longtitude) = explode(',', $location['location']);

                    $locationQuery->values([
                        'merchant_id' => $merchant->id,
                        'address'     => $location['address'],
                        'latitude'    => $latitude,
                        'longtitude'  => $longtitude,
                        'openhour'    => $location['openhour'],
                        'contact'     => $location['contact'],
                        'area_id'     => $location['area_id'],
                        'district_id' => $location['district_id'],
                        'sort'        => $location['sort'],
                        'is_visible'  => $location['is_visible'],
                        'pid'         => $pid,
                    ]);
                    $pid--;

                }
                $locationQuery->execute();
            }
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }

    //保存产品图片
    private function saveLogo($logo, $merchant_id)
    {
        $path     = MER_ROOT . $this->getLogoDir($merchant_id);
        $logoInfo = [];
        if ($logo['error'] === 0) {
            $ext = 'png';

            do {
                $name          = $this->generateRandomStr();
                $idtf          = $merchant_id . '_' . $name . '_';
                $fullAlbumPath = $path . $idtf . '0.' . $ext;
            } while (file_exists($fullAlbumPath));

            if (move_uploaded_file($logo['tmp_name'], $fullAlbumPath)) {
                $this->watermarkPrint($idtf, $path);
                $logoInfo = ['logo' => $name, 'logo_ext' => 'png'];
            }

        }
        return $logoInfo;
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
        $dst_small_path = $path . $idtf . '1.' . $ext;
        $dst_w          = 200;
        $dst_h          = 100;
        $src_small_im   = $this->resizeImageObject($src, $dst_w, $dst_h, $src_w, $src_h);

        imagepng($src_small_im, $dst_small_path);
        imagedestroy($src_small_im);

        #存为中图
        $dst_middle_path = $path . $idtf . '2.' . $ext;
        $dst_w           = 200;
        $dst_h           = int($src_h * $dst_w / $src_w);
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

    //ajax删除
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');

        if (count($ids) == 0) {
            $code = 2;
            $this->resApi(0, compact('code'), $msg_arr[$code]);
        }

        //删除商户，商户图片，商户地址，商户报价，
        $ids = $this->Merchants->find()->where(['id in' => $ids])->extract('id')->toArray();
        if (count($ids)) {
            //商户图片
            $this->Merchants
                ->find()
                ->where(['id in' => $ids])
                ->map(function ($row) {
                    $this->deleteLogoFile($row->logo, $row->logo_ext, $row->id);
                });
            //删除商户
            $this->Merchants->deleteAll(['id in' => $ids]);
            //删除商户地址
            $this->Merchants->MerchantLocations->deleteAll(['merchant_id in' => $ids]);
            //删除商户报价
            $this->Merchants->Quotes->deleteAll(['merchant_id in' => $ids]);
        }

        $code = 0;
        $this->resApi(0, compact('code', 'ids'), $msg_arr[$code]);
    }

    //删除产品图片本地文件
    private function deleteLogoFile($logo, $logo_ext, $merchant_id)
    {
        $logoDir = $this->getLogoDir($merchant_id);
        foreach ([0, 1, 2] as $key) {
            $file = new File(MER_ROOT . $logoDir . $merchant_id . '_' . $logo . '_' . $key . '.' . $logo_ext);
            $file->delete();
        }
    }

    //获取产品图片文件夹
    private function getLogoDir($merchant_id)
    {
        return intval($merchant_id / 100) . '00' . '/';
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Merchants.id',
                'name'       => 'Merchants.name',
                'is_visible' => 'Merchants.is_visible',
                'sort'       => 'Merchants.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Merchants.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Merchants.name like'] = '%' . trim($params['name']) . '%';
                }

                if (isset($params['district_id']) && intval($params['district_id'])) {
                    $ids = $this->Merchants->MerchantLocations
                        ->find()
                        ->where(['district_id' => intval($params['district_id'])])
                        ->extract('merchant_id')
                        ->toArray();
                    empty($ids) ? $where['Merchants.id'] = 0 : $where['Merchants.id in'] = $ids;
                } elseif (isset($params['area_id']) && intval($params['area_id'])) {
                    $ids = $this->Merchants->MerchantLocations
                        ->find()
                        ->where(['area_id' => intval($params['area_id'])])
                        ->extract('merchant_id')
                        ->toArray();
                    empty($ids) ? $where['Merchants.id'] = 0 : $where['Merchants.id in'] = $ids;
                }

                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Merchants.is_visible'] = $params['is_visible'];
                }
            }

            $contain = [];
            $order   = ['Merchants.sort' => 'desc', 'Merchants.modified' => 'desc', 'Merchants.created' => 'desc', 'Merchants.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->quote_count = $this->loadModel('Quotes')->find()->where(['merchant_id' => $row->id])->count();
            return $row;
        });
    }

    //返回联动多级分类
    //[
    //  selected_zone_id/selected_group_id/selected_category_id当前值
    //  zones/groups/categorys:select选项
    //]
    public function apiGetDistrictSelect()
    {
        $msg_arr = ['加载完成', '未选中', '记录不存在或已删除', 'type错误'];

        $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
        $params  = $this->request->$paramFn();

        $data = [
            'area'     => [
                'selected' => 0,
                'list'     => [],
            ],

            'district' => [
                'selected' => 0,
                'list'     => [],
            ],
        ];
        $this->loadModel('Districts');
        $this->loadModel('Areas');
        $data[$params['type']]['selected'] = $params['pid'];
        $data['area']['list']              = $this->loadModel('Areas')->find('list');
        $where_d                           = [];

        switch ($params['type']) {
            case 'area':
                if ($data['area']['selected']) {
                    $area = $this->Areas->find()->where(['id' => $data['area']['selected']])->first();
                    if (!$area) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $where_d = ['area_id' => $data['area']['selected']];
                }
                break;
            case 'district':
                if ($data['district']['selected']) {
                    $district = $this->Districts->find()->where(['id' => $data['district']['selected']])->first();
                    if (!$district) {
                        $this->resApi(0, 2, $msg_arr[2]);
                    }
                    $data['area']['selected'] = $district->area_id;

                } else {
                    $data['area']['selected'] = $params['origin']['area'];
                }
                if ($data['area']['selected']) {
                    $where_d = ['area_id' => $data['area']['selected']];
                }

                break;
            default:
                $this->resApi(0, 3, $msg_arr[3]);
        }

        $data['district']['list'] = $this->Districts->find('list')->where($where_d);
        $this->resApi(0, $data, $msg_arr[0]);
    }
}
