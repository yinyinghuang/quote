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
  //跳转至分类列表
  handlerNavigatorToCateList: function (e) {
    const { id, type } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/category_list/category_list?type=' + type + '&id=' + id })
  },
  //跳转至产品列表
  handlerNavigatorToProductList:function(e){
    const id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/Home/product_list/product_list?category_id='+id,
    })
  },
  //跳转至产品详情
  handlerNavigatorToSearch: function (e) {
    const { keyword } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/search/search?keyword=' + keyword })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})