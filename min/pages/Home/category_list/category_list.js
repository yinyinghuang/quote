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
    zone_id:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      zone_id:options.id
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
    comm.request({
      url:glbd.host+'categories/lists',
      method:glbd.method,
      data:{
        type:'zone_children',
        id:zone_id
      },
      success:function(res){
        this.setData({
          zone:res.data.zone,
          groups:res.data.groups
        })
      },
      fail:function(res){
        comm.showToast('分类获取失败')
      }
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