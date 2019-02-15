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
                ['field' => '\'name\'', 'title' => '\'分组\'', 'minWidth' => 280, 'fixed' => '\'left\'', 'unresize' => true, 'edit' => '\'text\''],
                ['field' => '\'zone_name\'', 'title' => '\'空间\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/zones/view/\'+res.zone_id+\'">\'+res.zone_name+\'</a>\')'],
                ['field' => '\'group_name\'', 'title' => '\'分组\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/groups/view/\'+res.group_id+\'">\'+res.group_name+\'</a>\')'],
                ['field' => '\'product_count\'', 'title' => '\'产品\'', 'unresize' => true, 'templet' => '(res) => (\'<a href="/categories/view/\'+res.id+\'?active=products">\'+res.product_count+\'</a>\')'],
                ['field' => '\'is_visible\'', 'title' => '\'可见\'', 'unresize' => true, 'templet' => '\'#switchTpl_3\''],
                ['field' => '\'sort\'', 'title' => '\'顺序\'', 'unresize' => true, 'edit' => '\'number\'', 'sort' => true],
            ],
            'switchTpls'  => [
                ['id' => 'switchTpl_3', 'name' => 'is_visible', 'text' => '是|否'],
            ],
        ];

        $tableParams = ['categories' => $tableParams];
        $groups       = $this->Categories->Groups->find('list');
        $this->set(compact('table_fields', 'switch_tpls', 'tableParams','groups'));
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Groups', 'Attributes', 'Brands', 'Products']
        ]);

        $this->set('category', $category);
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
                if (isset($params['is_visible']) && trim($params['is_visible']) == 'on') {
                    $where['Categories.is_visible'] = 1;
                }
            }
            debug($params);
            debug($where);
            $contain = ['Zones','Groups'];

            $order = ['Categories.sort' => 'desc', 'Categories.modified' => 'desc', 'Categories.created' => 'desc', 'Categories.id' => 'desc'];
            return [$fields, $where, $contain, $order];

        }, null, function ($row) {
            $row->product_count = $this->Categories->Products->find()->where(['category_id' => $row->id])->count();
            return $row;
        });
    }
}
