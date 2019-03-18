// pages/Home/product_merchant_near_by/product_merchant_near_by.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    markers: [],
    controls: [{
      id: 1,
      iconPath: '/static/images/icon/home.png',
      position: {
        left: 0,
        top: 300 - 50,
        width: 50,
        height: 50
      },
      clickable: true
    }],
    scale:16,
    latitude:0,
    longitude:0,
    merchants:[],
    page:1,
    merchant_reach_bottom:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      ...options,
    })
    app.openSetting(this.initPage)    
  },
  onUnload:function(){
    wx.clearStorageSync('user_location')
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {console.log('ssssss')
    const _this = this
    _this.getMerchantList()
  },
  initPage:function(){
    const _this = this
    wx.getLocation({
      success: function(res) {
        wx.setStorageSync('user_location', res)
        _this.setData({
          ...res
        })
        _this.getMerchantList()
      },
    })
  },
  //获取在售商户列表
  getMerchantList: function () {
    const _this = this
    if (_this.data.merchant_reach_bottom) return false
    const {page} = this.data
    comm.request({
      loadingMsg: '获取报价列表中...',
      url: glbd.host + 'products/quote-lists/' + _this.data.product_id,
      method: glbd.method,
      data:{page},
      success: function (res) {
        let data = res.data.data
        const { page } = _this.data
        let markers = []
        const length = _this.data.markers.length
        data.forEach((quote,index) => {
          if (quote.price_hong) quote.price_hong = comm.formatPrice(quote.price_hong)
          if (quote.price_water) quote.price_water = comm.formatPrice(quote.price_water)
          if (quote.latitude && quote.longitude){
            markers.push({
              id: index + length,
              latitude: quote.latitude,
              longitude: quote.longitude
            })
          }          
        })       

        _this.setData({
          markers: _this.data.markers.concat(markers),
          merchants: _this.data.merchants.concat(data),
          page: page + 1,
          merchant_reach_bottom: !data.length,
        })
      }
    })
  },
  //点击标记点，显示相应报价
  handlerMarkerTap:function(e){
    const {markerId} = e

    
  },
  //点击报价项，商店地址设为中心
  handlerLinkMap:function(e){
    const {id} = e.currentTarget.dataset
    let match = this.data.markers.filter((item) => item.id==id)
    if(!match.length) return 
    this.setData({
      latitude: match[0].latitude,
      longitude:match[0].longitude,
      scale:20,
    }) 
    wx.createSelectorQuery()
      .select('.merchant-list')
      .selectViewport()
      .scrollOffset()
      .exec((rect) => console.log(rect) )
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})