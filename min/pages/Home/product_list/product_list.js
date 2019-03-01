// pages/Home/product_list/product_list.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    category: null,
    products: [],
    options: {
      filter:null,
      sort: 'default',
      page:1
    },
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
    _this.data.options.category_id && _this.getCategory(_this.data.options.category_id)
    _this.getProductList(_this.data.options,_this.data.page)
  },
  //获取分类信息
  getCategory: function (category_id) {
    const _this = this
    comm.request({
      url: glbd.host + 'categories/get-detail',
      method: glbd.method,
      data: {
        category_id
      },
      success: function (res) {
        _this.setData({
          category: res.data.data
        })
      },
    })
  },
  //获取产品列表
  getProductList: function (options) {
    const _this = this

    comm.request({
      url: glbd.host + 'products/lists',
      method: glbd.method,
      data: options,
      success: function (res) {
        let time = Date.now()
        res.data.data.map((item) => {
          item.album = item.cover ? glbd.hosts + item.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (item.albums)
        })
        _this.setData({
          products: res.data.data,
          'options.page': ++_this.data.options.page
        })
      },
    })
  },
  //跳转至首页
  handlerNavigatorToIndex: function () {
    wx.switchTab({
      url: '/pages/Home/index/index',
    })
  },
  //排序产品,page归零
  handlerProductSort: function (e) {
    const _this = this
    const sort = e.currentTarget.dataset.sort

    if (sort !== _this.data.options.sort) {
      const options = Object.assign({}, _this.data.options, { sort,page:1 })
      _this.setData({
        options,
      })
      _this.getProductList(options)
    }
  },
  //跳转至筛选项页面
  handlerNavigatorToFilter:function(e){
    const id = e.currentTarget.dataset.id
    const _this = this
    wx.navigateTo({
      url: '/pages/Home/category_filter/category_filter?category_id='+id+'&sort='+_this.data.options.sort,
    })
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const _this = this
    _this.getProductList(_this.data.options)
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})