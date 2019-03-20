// pages/My/index/index.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    recents:[],
    merchants:[],
    collections:[],
    comments:[],
    params:{
      recents:{
        page:1,
        reach_bottom:false
      },
      merchants: {
        page: 1,
        reach_bottom: false
      },
      collections: {
        page: 1,
        reach_bottom: false
      },
      comments: {
        page: 1,
        reach_bottom: false
      },
    },
    active:'recents'
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
    app.openSetting(this.initPage)
  },
  initPage:function(){
    this.getList('recents')
  },
  //获取各种列表
  getList:function(active){
    switch(active){
      case 'recents':
        this._getRecentView()
        break
      case 'merchants':
        this.getMerchants()
        break
      case 'collections':
        this.getCollections()
        break
      case 'comments':
        this.getComments()
        break
    }
  },
  //获取最近浏览记录
  _getRecentView:function(){
    const recents = wx.getStorageSync('recent')
    this.setData({recents})
  },
  //获取收藏店铺
  getMerchants: function () {
    const _this = this
    const {page,reach_bottom} = this.data.params.merchants
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'fans/merchants/' + glbd.pkey,
      method: glbd.method,
      data: {
        page
      },
      success: function (res) {
        let data = res.data.data
        data.forEach((merchant) => {
          if (merchant.logos.middle) {
            merchant.logos.middle = glbd.hosts + merchant.logos.middle
            merchant.logos.full = glbd.hosts + merchant.logos.full
            merchant.logos.thumb = glbd.hosts + merchant.logos.thumb
          }
        })
        _this.setData({
          merchants: page == 1 ? data : _this.data.merchants.concat(data),
          'params.merchants.page': page + 1,
          'params.merchants.reach_bottom': !data.length,
        })
      }
    })
  },
  //获取收藏产品
  getCollections: function () {
    const _this = this
    const { page, reach_bottom } = this.data.params.collections
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'products/lists/',
      method: glbd.method,
      data: {
        page
      },
      success: function (res) {
        let data = res.data.data
        data.forEach((product) => {
          product.album = product.cover ? glbd.hosts + product.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (product.albums)
          if (product.price_hong_max) product.price_hong_max = comm.formatPrice(product.price_hong_max)
          if (product.price_hong_min) product.price_hong_min = comm.formatPrice(product.price_hong_min)
          if (product.price_water_max) product.price_water_max = comm.formatPrice(product.price_water_max)
          if (product.price_water_min) product.price_water_min = comm.formatPrice(product.price_water_min)
        })
        _this.setData({
          collections: page == 1 ? data : _this.data.collections.concat(data),
          'params.collections.page': page + 1,
          'params.collections.reach_bottom': !data.length,
        })
      }
    })
  },
  //获取发表的评论
  getComments: function () {

  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {

  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})