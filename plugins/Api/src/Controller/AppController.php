<?php

namespace Api\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
	//返回结果
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
	//获取offset
	protected function getOffset($page,$limit){
		return ($page-1)*$limit;
	}
	//获取默认顺序
	protected function getDefaultOrder($controller){
		return [$controller.'.sort desc',$controller.'.id desc'];
	}
	//获取产品封面
	protected function _getProductCover($product_id, $product_album)
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
    //获取产品图片列表
    protected function _getProductAlbumUrl($product_id, $product_album)
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
    //获取产品图片文件夹
    protected function _getAlbumDir($product_id)
    {
        return intval($product_id / 1000) . '000' . '/';
    }
}
