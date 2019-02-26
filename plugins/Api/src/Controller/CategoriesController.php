<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * Categories Controller
 *
 *
 * @method \Api\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{

    public function lists()
    {
        $params = $this->request->getData();
        switch ($params['type']) {
            case 'zones':
                $zones = $this->loadModel('Zones')->find('list')->order(['Zones.sort desc','Zones.id desc']);
                $this->ret(0,$zones,'加载成功');
                break;
            
            default:
                # code...
                break;
        }
    }
}
