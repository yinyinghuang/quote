// pages/Home/merchant_detail/merchant_detail.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    active:'quote',
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
        let data = [].concat(res.data.data)
        data = page==1?data:_this.data.locations.concat(data)
        //商户没有更多地址信息        
        if (res.data.data.length===0){
          _this.setData({
            'location_param.reach_bottom': true,
          })
        }
        if(data.length==0){

        }else if (data.length === 1){
          //只有一个地址，则不显示地址列表
          _this.setData({
            ...data[0]
          })
        }else{
          _this.setData({
            address: data[0].address,
            latitude: data[0].latitude,
            longitude:data[0].longitude,
            locations: data,
            'location_param.page': page + 1
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
          if (quote.cover) quote.cover = glbd.hosts+quote.cover
        })
        _this.setData({
          quotes: page == 1 ? data : _this.data.quotes.concat(data),
          'quote_param.page': page + 1,
          'quote_param.reach_bottom': !data.length,
        })
      }
    })
  },
  //跳转至产品详情
  handlerNavigatorToProductDetail: function (e) {
    const { id } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/product_detail/product_detail?id=' + id })
  },  
  //产品图片不存在
  handlerImageError: function (e) {
    const index = e.currentTarget.dataset.index.split('.')
    switch(index.length){
      case 1:
        this.data[index[0]] = '/static/images/icon-red/nopic.png'
        break
      case 2:
        this.data[index[0]][index[1]] = '/static/images/icon-red/nopic.png'
        break
      case 3:
        this.data[index[0]][index[1]][index[2]] = '/static/images/icon-red/nopic.png'
        break
    }
    this.setData(this.data)
  },
  //拨打电话
  handlerMakePhoneCall:function(e){
    const {phone} = e.currentTarget.dataset
    console.log(phone)
    if(phone){
      wx.makePhoneCall({
        phoneNumber: phone,
      })
    }
  },
  //切换下方显示列表类型
  handlerToggleActive:function(e){
    const { active } = e.currentTarget.dataset
    this.setData({active})
  },
  //打开地图
  handlerOpenLocation: function (e) {
    let { latitude, longitude, name, address } = e.currentTarget.dataset
    latitude = Number(latitude)
    longitude = Number(longitude)
    if (!latitude || !longitude) return;
    wx.openLocation({
      latitude,
      longitude,
      name,
      address,
    })
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const {active} = this.data
    if(active === 'quote'){
      this.getQuoteList()
    }else if(active === 'location'){
      this.getMerchantLocations()
    }
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})