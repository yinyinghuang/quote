<?php

namespace Api\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
	
	protected function ret($errCode, $data, $errMsg, $extra = [])
	{
	    $this->autoRender = false;
	    $res              = [
	        'errCode' => $errCode,
	        'data' => $data,
	        'errMsg'  => $errMsg,
	    ] + $extra;
	    die(json_encode($res));
	}

	protected function getOffset($page,$limit){
		return ($page-1)*$limit;
	}
}
