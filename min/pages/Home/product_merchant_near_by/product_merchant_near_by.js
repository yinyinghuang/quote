// pages/Home/product_merchant_near_by/product_merchant_near_by.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
const coordinate = require('../../../utils/WSCoordinate.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    markers: [],
    scale:16,
    latitude:0,
    longitude: 0,
    name: '',
    address: '',
    merchants:[],
    page:1,
    merchant_reach_bottom:false,
    show_goto_button:false,
    scrollTop:0,
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
    wx.removeStorageSync('user_location')
  },
  //页面上拉触底事件的处理函数
  // onReachBottom: function () {
  //   const _this = this
  //   _this.getMerchantList()
  // },
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
    // const {page} = this.data
    comm.request({
      loadingMsg: '获取报价列表中...',
      url: glbd.host + 'products/quote-lists/' + _this.data.product_id,
      method: glbd.method,
      // data:{page},
      success: function (res) {
        let data = res.data.data
        // const { page } = _this.data
        let markers = []
        const length = _this.data.markers.length
        data.forEach((quote,index) => {
          if (quote.price_hong) quote.price_hong = comm.formatPrice(quote.price_hong)
          if (quote.price_water) quote.price_water = comm.formatPrice(quote.price_water)
          if (quote.latitude && quote.longitude){            
            var { latitude, longitude} = coordinate.transformFromWGSToGCJ(Number(quote.latitude), Number(quote.longitude))
            quote.latitude = latitude
            quote.longitude = longitude
            markers.push({
              id: index + length,
              latitude,
              longitude
            })
          }         
        })       

        _this.setData({
          markers: _this.data.markers.concat(markers),
          merchants: _this.data.merchants.concat(data),
          // page: page + 1,
          // merchant_reach_bottom: !data.length,
          merchant_reach_bottom: 1,
        })
      }
    })
  },
  //点击标记点，修改标价滚动条
  handlerMarkerTap:function(e){
    const _this = this
    const {markerId} = e
    const query = wx.createSelectorQuery()
    query.selectAll('.merchant-item').boundingClientRect()    
    query.exec(function (res) {
      if(!res.length) return 
      let scrollTop = 0
      const margin_bottom = 10
      for(let i=0;i<res[0].length;i++){
        const cur = res[0][i]
        if(cur.dataset.id>=markerId) break
        scrollTop += cur.height + margin_bottom
      }
      _this.setData({
        scrollTop
      })
    })
    const match = this.data.merchants[markerId]
    this.setData({
      latitude: match.latitude,
      longitude: match.longitude,
      name: match.merchant_name,
      address: match.address,
      scale: 20,
      show_goto_button: 1,
    }) 
  },
  //点击报价项，商店地址设为中心
  handlerLinkMap:function(e){
    const {id} = e.currentTarget.dataset
    let match = this.data.merchants[id] 
    if (!match) return 
    // console.log(match)
    this.setData({
      latitude: match.latitude,
      longitude: match.longitude,
      name: match.merchant_name,
      address:match.address,
      scale: 20,
      show_goto_button: 1,
    }) 
  },
  //打开地图
  handlerOpenLocation: function (e) {
    var { latitude, longitude, name, address } = this.data
    latitude = Number(latitude)
    longitude = Number(longitude)
    if (!latitude || !longitude) return;
    // var { latitude, longitude } = coordinate.transformFromWGSToGCJ(latitude, longitude)
    wx.openLocation({
      latitude,
      longitude,
      name,
      address,
    })
  },
  //显示我的位置
  handlerSwithToMyLocation: function (e) {
    const user_location = wx.getStorageSync('user_location')
    const _this =this
    if (!user_location){
      wx.getLocation({
        success: function(res) {
          _this.setData({
            ...res,
            show_goto_button:false,
            scale: 16,
          })
        },
      })
    }else{
      _this.setData({
        ...user_location,
        show_goto_button: false,
        scale: 16,
      })
    }
    
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})