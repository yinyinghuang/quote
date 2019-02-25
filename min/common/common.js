const showToast = (title) => wx.showToast({
  title: title,
  duration: 1000,
})
const requestHandler = {
  url:'',
  data:{},
  method:'get',
  success: function () { },
  fail: function () { },
  complete:function(){},

}
const requestData = (glbd,data={}) => {
  const req_data = {
    pkey:glbd.pkey
  }
  return Object.assign({}, req_data,data)
}
function request(requestHandler){
  wx.showLoading({
    title: '加载中',
    mask:true
  })
  wx.request({
    url: requestHandler.url,
    data:requestHandler.data,
    method:requestHandler.method,
    success:function(res){
      wx.hideLoading()
      typeof(requestHandler.success) === 'function' && requestHandler.success(res)
    },
    fail:function(){
      wx.hideLoading()
      typeof(requestHandler.fail) === 'function' && requestHandler.fail()
    },
    complete:function(res){
      wx.hideLoading()
      typeof (requestHandler.complete) === 'function' && requestHandler.complete()
    }
  })
}


export {
  showToast,
  request,
  requestData
}