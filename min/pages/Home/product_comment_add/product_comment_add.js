// pages/Home/product_comment_add/product_comment_add.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    rating:0,
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
    this.setData({
      ...options
    })
    app.openSetting(this.initPage)
  },
  //填写评分
  handlerSetRating:function(e){
    const rating = e.currentTarget.dataset.rating
    this.setData({
      rating
    })
  },
  //提交表单
  handlerSubmit:function(e){
    const {content} = e.detail.value
    const {product_id} = this.data
    if(content.length<10) {
      comm.showToast('评价字数不能少于10个哦')
      return false
    }
    const _this = this
    comm.request({
      loadingMsg: '提交中...',
      url: glbd.host + 'products/add-comment/' + product_id,
      method: glbd.method,
      data: comm.requestData(glbd, {
        ..._this.data,
        content
      }),
      success: function (res) {
        if(res.data.data){
          comm.showToast('跳转至产品详情', 'success')
          comm.refreshPkey(glbd, res.data.data)
          wx.navigateTo({
            url: '/pages/Home/product_detail/product_detail?id=' + product_id,
          })
          wx.hideLoading()
        }else{
          comm.showToast(res.data.errMsg)
        }
      }
    })
  }
})