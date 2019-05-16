<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Brands Controller
 *
 * @property \App\Model\Table\BrandsTable $Brands
 *
 * @method \App\Model\Entity\Brand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BrandsController extends AppController
{

    //首页
    public function index()
    {
        $tableParams = [
            'name'        => 'brands',
            'renderUrl'   => '/brands/api-lists',
            'deleteUrl'   => '/brands/api-delete',
            'editUrl'     => '/brands/api-save',
            'addUrl'      => '/brands/add',
            'can_search'  => true,
            'delIndex'    => 'brand',
            'tableFields' => [
                ['field' => '\'brand\'', 'title' => '\'品牌名\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => true],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['brands' => $tableParams];
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams'));

        $brands = $this->Brands->find('all')
            /*->map(function($row){
                $alpha = $this->getfirstchar($row->brand);
                $this->Brands->query()->update()->set(['alpha' => $alpha])->where(['brand' => $row->brand])->execute();
                return $row;
            })*/->count();
        die($brands);
    }

    //浏览
    public function view($id = null)
    {
        $brand = $this->Brands->get($id);

        $this->set('brand', $brand);
    }

    //添加
    public function add()
    {
        $brand = $this->Brands->newEntity();
        $this->set(compact('brand', 'categories', 'products'));
        $this->render('view');
    }

    //ajax修改
    public function apiSave()
    {

        $this->allowMethod(['POST', 'PUT', 'PATCH']);
        $code    = 0;
        $msg_arr = ['保存成功', '参数bid缺失', '记录不存在或已删除', '属性名已存在'];

        $params         = $this->request->getData();
        $params['type'] = isset($params['type']) ? $params['type'] : 'edit';
        if (!isset($params['brand']) && $params['type'] === 'edit') {
            $data = 1;
            $this->resApi($code, $data, $msg_arr[$data]);
        }

        $brand = (isset($params['brand']) && $params['brand'] && $params['type'] == 'edit') ? $this->Brands->find('all')
            ->where(['id' => $params['brand']])
            ->first() : $this->Brands->newEntity();
        if (!$brand) {
            $data = 2;
            $this->resApi($code, $data, $msg_arr[$data]);
        }
        //详情编辑情提交请求
        if (isset($params['detail']) && $params['detail']) {
            $params['is_visible'] = isset($params['is_visible']) ? $params['is_visible'] : 0;
        }
        $brand = $this->Brands->patchEntity($brand, $params);

        $data = $this->Brands->save($brand) ? 0 : 3;

        //内容填写错误导致记录无法更新
        if ($data === 3) {
            $msgs = [];
            foreach ($brand->__debugInfo()['[errors]'] as $name => $error) {
                $msgs[] = $name . ':' . implode(',', array_values($error));
            }
            $this->resApi($code, $data, implode(';', $msgs));
        }

        $this->resApi($code, $data, $msg_arr[$data]);

    }
    //ajax删除
    public function apiDelete()
    {
        $msg_arr = ['删除完成', '删除失败，刷新页面再重试', '未选中'];
        $this->allowMethod(['POST']);
        $ids = $this->request->getData('ids');

        if (count($ids) == 0) {
            $data = ['code' => 2];
            $this->resApi(0, $data, $msg_arr[2]);
        }

        //删除产品相关属性值
        
        $this->Brands->deleteAll(['brand in' => $ids]);
        $this->Brands->CategoriesBrands->deleteAll(['brand in' => $ids]);

        $data = ['code' => 0, 'brands' => $ids];
        $this->resApi(0, $data, $msg_arr[0]);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'brand'      => 'Brands.brand',
                'is_visible' => 'Brands.is_visible',
                'sort'       => 'Brands.sort',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['brand']) && trim($params['brand'])) {
                    $where['Brands.brand like'] = '%' . trim($params['brand']) . '%';
                }
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1, 0])) {
                    $where['Brands.is_visible'] = $params['is_visible'];
                }
            }
            $contain = [];

            $order = ['Brands.sort' => 'desc', 'Brands.modified' => 'desc', 'Brands.created' => 'desc', 'Brands.brand' => 'desc'];
            return [$fields, $where, $contain, $order];

        });
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
}
