<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    //首页列表
    public function index()
    {
        $tableParams = [
            'name'        => 'categories',
            'renderUrl'   => '/categories/api-lists',
            'deleteUrl'   => '/categories/api-delete',
            'editUrl'     => '/categories/api-save',
            'addUrl'      => '/categories/add',
            'viewUrl'     => '/categories/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'分类\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'zone_name\'', 'title' => '\'空间\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.zone_id+\'">\'+res.zone_name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'attribute_count\'', 'title' => '\'属性\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=attributes">\'+res.attribute_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['categories' => $tableParams];
        $zones       = $this->Categories->Zones->find('list');
        $groups      = $this->Categories->Groups->find('list');
        $categories  = $this->Categories->find('list');
        $category_select = $this->getCasecadeTplParam('category_select',[ 
            'category' =>[
                'disabled' => true,
                'options' => [],
            ],
        ],true);
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams', 'category_select'));
    }
    //浏览详情
    public function view($id = null)
    {
        $category = $this->Categories->find()->where(['Categories.id' => $id])->contain(['Zones','Groups'])->first();

        $zones      = $this->Categories->Zones->find('list')->where(['id' => $category->zone_id]);
        $groups     = $this->Categories->Groups->find('list')->where(['id' => $category->group_id]);
        $categories = $this->Categories->find('list')->where(['id' => $category->id]);        
        //产品
        $category->productCount = $this->Categories->Products->find()->where(['category_id' => $category->id])->count();
        $searchTpl['product']['category_select'] = $this->getCasecadeTplParam('category_select',[
            'zone'     => [
                'zone_id'  => $category->zone_id,
                'disabled' => true,
                'options'  => $zones,
            ],
            'group'    => [
                'group_id' =>$category->group_id,
                'disabled' => true,
                'options' => $groups,
            ],
            'category' => [
                'category_id' =>$category->id,
                'disabled' => true,
                'options'  => $categories,
            ],
        ], true);
        $productTableParams  = [
            'name'        => 'products',
            'renderUrl'   => '/products/api-lists?search[category_id]=' . $category->id,
            'deleteUrl'   => '/products/api-delete',
            'editUrl'     => '/products/api-save',
            'addUrl'      => '/products/add?category_id=' . $category->id,
            'viewUrl'     => '/products/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'产品\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true],
                ['field' => '\'brand\'', 'title' => '\'品牌\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/brands/view/\'+res.brand+\'">\'+res.brand+\'</a>\')'],
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
        //分类属性
        $category->attributeCount = $this->Categories->CategoriesAttributes->find()->where(['category_id' => $category->id])->count();
        $attributeTableParams  = [
            'name'        => 'attributes',
            'renderUrl'   => '/categories-attributes/api-lists?search[category_id]=' . $category->id,
            'deleteUrl'   => '/categories-attributes/api-delete',
            'editUrl'     => '/categories-attributes/api-save',
            'addUrl'      => '/categories-attributes/add?category_id=' . $category->id,
            'viewUrl'     => '/categories-attributes/view',
            'can_search'  => true,
            'tableFields' => [
                ['field' => '\'id\'', 'title' => '\'ID\'', 'fixed' => '\'left\'', 'unresize' => true, 'sort' => true],
                ['field' => '\'name\'', 'title' => '\'属性\'','fixed' => '\'left\'', 'unresize' => true],                
                ['field' => '\'is_filter\'', 'title' => '\'筛选项\'', 'unresize' => true, 'templet' => '\'#switchTpl_4\''],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
                ['id' => 'switchTpl_4', 'name' => 'is_filter', 'text' => '是|否'],
            ],
        ];
        $tableParams = ['products' => $productTableParams, 'attributes' => $attributeTableParams];
        $active      = $this->request->query('active');
        $this->set(compact('category', 'tableParams', 'active', 'searchTpl','autocompleteFields'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $groups = $this->Categories->Groups->find('list', ['limit' => 200]);
        $attributes = $this->Categories->Attributes->find('list', ['limit' => 200]);
        $brands = $this->Categories->Brands->find('list', ['limit' => 200]);
        $this->set(compact('category', 'groups', 'attributes', 'brands'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Attributes', 'Brands']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $groups = $this->Categories->Groups->find('list', ['limit' => 200]);
        $attributes = $this->Categories->Attributes->find('list', ['limit' => 200]);
        $brands = $this->Categories->Brands->find('list', ['limit' => 200]);
        $this->set(compact('category', 'groups', 'attributes', 'brands'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //ajax获取list
    public function apiLists()
    {

        $this->getTableData(function () {
            $fields = [
                'id'         => 'Categories.id',
                'name'       => 'Categories.name',
                'is_visible' => 'Categories.is_visible',
                'sort'       => 'Categories.sort',
                'group_name' => 'Groups.name',
                'group_id' => 'Groups.id',
                'zone_name' => 'Zones.name',
                'zone_id' => 'Zones.id',
            ];

            $paramFn = $this->request->is('get') ? 'getQuery' : 'getData';
            $params  = $this->request->$paramFn();

            $where   = [];
            if (isset($params['search'])) {
                $params = $params['search'];
                if (isset($params['id']) && intval($params['id'])) {
                    $where['Categories.id'] = intval($params['id']);
                }
                if (isset($params['name']) && trim($params['name'])) {
                    $where['Categories.name like'] = '%' . trim($params['name']) . '%';
                }
                if (isset($params['zone_id']) && intval($params['zone_id'])) {
                    $where['Categories.zone_id'] = intval($params['zone_id']);
                }
                if (isset($params['group_id']) && intval($params['group_id'])) {
                    $where['Categories.group_id'] = intval($params['group_id']);
                }       
                if (isset($params['is_visible']) && in_array($params['is_visible'], [1,0])) {
                    $where['Categories.is_visible'] = $params['is_visible'];
                }
            }
            $contain = ['Zones','Groups'];

            $order = ['Categories.sort' => 'desc', 'Categories.modified' => 'desc', 'Categories.created' => 'desc', 'Categories.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->product_count = $this->Categories->Products->find()->where(['category_id' => $row->id])->count();
            $row->attribute_count = $this->Categories->CategoriesAttributes->find()->where(['category_id' => $row->id])->count();
            return $row;
        });
    }
}
