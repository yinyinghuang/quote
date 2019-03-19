// pages/Home/merchant_detail/merchant_detail.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    location_param:{
      page:1,
      reach_bottom:false
    },
    quote_param:{
      page: 1,
      reach_bottom: false
    },
    locations:[],
    quotes:[]
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
    this.setData({
      ...options
    })
    app.openSetting(this.initPage)   
  },
  initPage:function(){
    this.getMerchantDetail()
    this.getMerchantLocations()
    this.getQuoteList()
  },
  //获取商户详情
  getMerchantDetail:function(){
    const _this = this
    comm.request({
      loadingMsg: '获取商户详情...',
      url: glbd.host + 'merchants/detail/' + _this.data.id,
      method: glbd.method,
      data: comm.requestData(glbd),
      success: function (res) {
        let detail = res.data.data
        if (detail.logos.middle){
          detail.logos.middle = glbd.hosts + detail.logos.middle
          detail.logos.full = glbd.hosts + detail.logos.full
          detail.logos.thumb = glbd.hosts + detail.logos.thumb
        }
        _this.setData({
          ...detail
        })
      }
    })
  },
  //获取商户地址列表
  getMerchantLocations: function () {
    const _this = this
    const {page,reach_bottom} = this.data.location_param
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'merchants/location-lists/' + _this.data.id,
      method: glbd.method,
      data: { page},
      success: function (res) {
        let data = res.data.data
        //商户没有地址信息        
        if(data.length===0 && page==1){
          _this.setData({
            'location_param.reach_bottom': true,
          })
        } else if (data.length === 1){
          //只有一个地址，则不显示地址列表
          _this.setData({
            ...data[0]
          })
        }else{
          _this.setData({
            address: data[0].address,
            latitude: data[0].latitude,
            longitude:data[0].longitude,
            locations: page == 1 ? data : _this.data.locations.concat(data),
            'location_param.page': page + 1,
            'location_param.reach_bottom': !data.length,
          })
        }
        
      }
    })
  },
  //获取在售产品列表
  getQuoteList: function () {
    const _this = this
    const { page, reach_bottom } = this.data.quote_param
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'merchants/quote-lists/' + _this.data.id,
      method: glbd.method,
      data: {page},
      success: function (res) {
        let data = res.data.data
        data.forEach((quote) => {
          if (quote.price_hong) quote.price_hong = comm.formatPrice(quote.price_hong)
          if (quote.price_water) quote.price_water = comm.formatPrice(quote.price_water)
        })
        _this.setData({
          quotes: page == 1 ? data : _this.data.quotes.concat(data),
          'quote_param.page': page + 1,
          'quote_param.reach_bottom': !data.length,
        })
      }
    })
  },
  //图片不存在
  handlerImageError: function (e) {
    const index = e.currentTarget.dataset.index
    this.setData({
      [index]: '/static/images/icon-red/nopic.png'
    })
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {

  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})