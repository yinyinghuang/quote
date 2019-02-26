// pages/Home/index/index.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    category:{},
    recent:{},
    last:{}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //每个页面必须判断用户是否授权
    app.openSetting(this.initPage)
  },

  /**
   * 初始化页面
   */
  initPage:function(){
    const _this = this
    _this.getCategoryList()
    _this.getRecentViewList()
    _this.getLastProductList()
  },
  /**
   * 获取一级分类
   */
  getCategoryList:function(){
    const _this = this
    comm.request({
      url:glbd.host + 'categories/lists',
      method:glbd.method,
      data:comm.requestData(glbd,{type:'zones'}),
      success:function(res){
        if(res.data.errCode === 0){
          _this.setData({
            category:res.data.data
          })
        }else{
          comm.showToast(res.data.errMsg ? res.data.errMsg : '分类获取失败')
        }
      },
      fail:function(){
        comm.showToast('分类获取失败')
      }
    })
  },
  /**
   * 获取最近浏览
   */
  getRecentViewList: function () {
    const _this = this
    
  },
  /**
   * 获取最新更新
   */
  getLastProductList: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'products/lists',
      method: glbd.method,
      data: comm.requestData(glbd, { type: 'last' }),
      success: function (res) {
        if (res.data.errCode === 0) {
          _this.setData({
            last: res.data.data
          })
        } else {
          comm.showToast(res.data.errMsg ? res.data.errMsg : '最新更新获取失败')
        }
      },
      fail: function () {
        comm.showToast('最新更新获取失败')
      }
    })
  },


  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})