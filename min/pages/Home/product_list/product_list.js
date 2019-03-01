// pages/Home/product_list/product_list.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    category:null,
    products:[],
    options:{}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      options
    })
    app.openSetting(this.initPage)
  },
  //初始化页面
  initPage:function(){
    const _this = this
    _this.data.options.category_id && _this.getCategory(_this.data.options.category_id)
    _this.getProductList(_this.data.options)
  },
  //获取分类信息
  getCategory:function(category_id){
    const _this = this
    comm.request({
      url:glbd.host+'categories/detail',
      method:glbd.method,
      data:{
        category_id
      },
      success:function(res){
        this.setData({
          category:res.data.data
        })
      },
    })
  },
  //获取产品列表
  getProductList:function(options){
    const _this = this 
    
    comm.request({
      url:glbd.host+'products/lists',
      method:glbd.method,
      data:options,
      success:function(res){
        let time = Date.now()
        res.data.data.map((item) => {
          item.album = item.cover ? glbd.hosts + item.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (item.albums)
        })
        _this.setData({
          products:res.data.data
        })
      },
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