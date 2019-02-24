<?php

namespace Api\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
	
	protected function resApi($code, $data, $msg, $extra = [])
	{
	    $this->autoRender = false;
	    $res              = [
	        'code' => $code,
	        'data' => $data,
	        'msg'  => $msg,
	    ] + $extra;

	    $this->response->body(json_encode($res));
	    die($this->response);
	}
}
