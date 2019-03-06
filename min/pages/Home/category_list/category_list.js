// pages/Home/category_list/category_list.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    zone:null,
    groups:null,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      ...options
    })
    app.openSetting(this.initPage)
  },
  //初始化页面
  initPage:function(){
    const _this = this
    _this.getCategoryList(_this.data.zone_id)
  },
  //获取分类列表
  getCategoryList:function(zone_id){
    const _this = this
    const {type,id} = _this.data
    comm.request({
      url:glbd.host+'categories/lists',
      method:glbd.method,
      data:{
        type,id
      },
      success:function(res){
        let data = res.data.data
        _this.setData({
          ...data
        })
      },
      fail:function(res){
        comm.showToast('分类获取失败')
      }
    })
  },
  //跳转至首页
  handlerNavigatorToIndex: function () {
    wx.switchTab({
      url: '/pages/Home/index/index',
    })
  },
  //跳转至产品列表
  handlerNavigatorToProductList:function(e){
    const id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/Home/product_list/product_list?category_id='+id,
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})