// pages/Home/category_filter/category_filter.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    filter:{},
    category_id:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      options: Object.assign({}, this.data.options, options)
    })
    app.openSetting(this.initPage)
  },
  //初始化页面
  initPage: function () {
    const _this = this
    _this.getCategoryFilter(_this.data.options, _this.data.page)
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