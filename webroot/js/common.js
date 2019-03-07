/**
 * 异步获取，添加csrftoken
 * res:{
 * code:返回码，0:成功，1:method出错
 * data:返回数据
 * msg:提示语
 * }
 * 若返回失败，重载页面
 */
const pageReload = () => setTimeout(() => {window.location.reload()},1000)
const ajax = ($,requestHandler) => {
    layer.msg('加载中');
    $.ajax({
        headers: {
            'X-CSRF-Token': requestHandler.token
        },
        url: requestHandler.url,
        type: requestHandler.type ? requestHandler.type : 'get',
        data: requestHandler.data ? requestHandler.data : {},
        success: (res) => {
            
            res = JSON.parse(res)
            if(res.code){
            	layer.msg(res.msg);
                pageReload()
            }else{
            	layer.msg(res.msg);
            	typeof(requestHandler.success) === 'function' ? requestHandler.success(res): null
            }
            
        },
        fail:() =>{
            layer.closeAll()
        	layer.msg('ajaxfail系统出错，页面重载');
	        pageReload()
        }

    })

}

const c = (first,...rest) => {console.log(first,...rest)}

