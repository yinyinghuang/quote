const showToast = (title, icon='none') => wx.showToast({
  icon,
  title,
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
    pkey:glbd.pkey,
    code:glbd.code
  }
  return Object.assign({}, req_data,data)
}
function request(requestHandler){
  wx.showLoading({
    icon:'loading',
    title: requestHandler.loadingMsg ? requestHandler.loadingMsg:'加载中',
    mask:true
  })
  wx.request({
    url: requestHandler.url,
    data:requestHandler.data,
    method:requestHandler.method,
    success:function(res){
      wx.hideLoading()
      if(res.data.errCode){
        showToast(res.data.errMsg)
      }else{
        typeof (requestHandler.success) === 'function' && requestHandler.success(res)
      }
      
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

const type = (varible) => Object.prototype.toString.call(varible)
const formatPrice = (price) => {
  price = (price.toFixed(2)+'').split('.')
  let price_positive = price[0]
  let result = 0
  let str = []
  while (price_positive > 1000){
    str.push(price_positive % 1000 ? price_positive % 1000:'000')
    price_positive = Math.floor(price_positive / 1000)
  }
  return price_positive + (str.length ? ',' + str.join(',') : '') + (price[1] ? '.' + price[1]:'')
}
export {
  showToast,
  request,
  requestData,
  type,
  formatPrice
}