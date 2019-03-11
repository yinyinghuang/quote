// pages/Home/product_detail/product_detail.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    merchants:[],
    attribute_group:[]
  },
  //生命周期函数--监听页面加载
  onLoad: function (options) {
    this.setData({
      ...options
    })
    app.openSetting(this.initPage)
  },
  initPage:function(){
    const _this = this 
    _this.getProductDetail()
    _this.getMerchantList()
  },
  //获取产品详情
  getProductDetail: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'products/detail/' + _this.data.id,
      method: glbd.method,
      data: comm.requestData(glbd),
      success: function (res) {
        let detail = res.data.data
        detail.albums.forEach((item) => {
          item.middle = glbd.hosts + item.middle
          item.full = glbd.hosts + item.full
          item.thumb = glbd.hosts + item.thumb
        })
        if (detail.price_hong_max) detail.price_hong_max = comm.formatPrice(detail.price_hong_max)
        if (detail.price_hong_min) detail.price_hong_min = comm.formatPrice(detail.price_hong_min)
        if (detail.price_water_max) detail.price_water_max = comm.formatPrice(detail.price_water_max)
        if (detail.price_water_min) detail.price_water_min = comm.formatPrice(detail.price_water_min)

        let attribute_group = []
        if (detail.attributes.length){
          const half = Math.ceil(detail.attributes.length / 2)
          attribute_group.push(detail.attributes.slice(0, half))
          attribute_group.push(detail.attributes.slice(half))
        }
        _this.setData({
          ...detail,
          attribute_group
        })
        _this._saveTrack()
      }
    })
  },
  //获取在售商户列表
  getMerchantList: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'products/quote-lists/' + _this.data.id,
      method: glbd.method,
      success: function (res) {
        let data = res.data.data
        data.forEach((quote) => {
          if (quote.price_hong) quote.price_hong = comm.formatPrice(quote.price_hong)
          if (quote.price_water) quote.price_water = comm.formatPrice(quote.price_water)
        })
        _this.setData({
          merchants:data
        })
      }
    })
  },
  //保存浏览记录
  _saveTrack: function (){
    const { id, album, name} = this.data
    let recent = wx.getStorageSync('recent')
    recent = recent ? recent : []

    for (let i in recent) {
      if (recent[i].id === id) {
        recent.splice(i, 1)
        break
      }
    }
    recent.unshift({ id, album, name, time: Date.now() })
    wx.setStorageSync('recent', recent.slice(0, 50))
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})