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
    commented:1,
    merchants:[],
    attribute_group:[],
    liked:false,
    style:{
      show_merchant_filter: false,
      show_price_type:false,
      show_area:false,
      merchant_filtered:false,
    },
    merchant_filter: {},
    merchant_filter_name:{},
    default_merchant_filter_name:{
      price_type: '行货|水货',
      area:'地区',
    },
    areas:[],
    merchant_reach_bottom:false,
    page:1
  },
  //生命周期函数--监听页面加载
  onLoad: function (options) {
    this.setData({
      ...options,
      merchant_filter_name: Object.assign({}, this.data.default_merchant_filter_name)
    })
    app.openSetting(this.initPage)
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const _this = this
    _this.getMerchantList()
  },
  initPage:function(){
    const _this = this 
    _this.getProductDetail()
    _this.getMerchantList()
    _this.getAreaList()
    _this.getCode()
  },
  //获取glbd.code
  getCode:function(){
    wx.login({
      success:function(res){glbd.code = res.code}
    })
  },
  //获取产品详情
  getProductDetail: function () {
    const _this = this
    comm.request({
      loadingMsg: '获取产品详情中...',
      url: glbd.host + 'products/detail/' + _this.data.id,
      method: glbd.method,
      data: comm.requestData(glbd),
      success: function (res) {
        let detail = res.data.data
        if (detail.rating == 0 && detail.meta_data&&detail.meta_data.comment_score_total && detail.meta_data.comment_count) {
          detail.rating = Math.ceil(detail.meta_data.comment_score_total / detail.meta_data.comment_count)
        }
        detail.albums.forEach((item) => {
          item.middle = glbd.hosts + item.middle
          item.full = glbd.hosts + item.full
          item.thumb = glbd.hosts + item.thumb
        })
        if (detail.price_hong_max) detail.price_hong_max = comm.formatPrice(detail.price_hong_max)
        if (detail.price_hong_min) detail.price_hong_min = comm.formatPrice(detail.price_hong_min)
        if (detail.price_water_max) detail.price_water_max = comm.formatPrice(detail.price_water_max)
        if (detail.price_water_min) detail.price_water_min = comm.formatPrice(detail.price_water_min)

        // let attribute_group = []
        // if (detail.attributes.length){
        //   const half = Math.ceil(detail.attributes.length / 2)
        //   attribute_group.push(detail.attributes.slice(0, half))
        //   attribute_group.push(detail.attributes.slice(half))
        // }
        _this.setData({
          ...detail,
          // attribute_group
        })
        _this._saveTrack()
      }
    })
  },
  //获取在售商户列表
  getMerchantList: function () {
    const _this = this
    if(_this.data.merchant_reach_bottom) return false
    comm.request({
      loadingMsg: '获取报价列表中...',
      url: glbd.host + 'products/quote-lists/' + _this.data.id,
      method: glbd.method,
      data:{
        ..._this.data.merchant_filter,
        page:this.data.page
      },
      success: function (res) {
        let data = res.data.data
        const { page } = _this.data
        data.forEach((quote) => {
          if (quote.price_hong) quote.price_hong = comm.formatPrice(quote.price_hong)
          if (quote.price_water) quote.price_water = comm.formatPrice(quote.price_water)
        })
        _this.setData({
          merchants: page == 1 ? data : _this.data.merchants.concat(data),
          page: page + 1,
          merchant_reach_bottom: !data.length,
        })
      }
    })
  },
  //获取地区列表
  getAreaList: function () {
    let areas = wx.getStorageSync('areaList')
    const _this = this
    if(areas === undefined || !areas.length){
      const _this = this
      comm.request({
        loadingMsg:'获取地区列表中...',
        url: glbd.host + 'merchants/area-lists/',
        method: glbd.method,
        data: _this.data.merchant_filter,
        success: function (res) {
          _this.setData({
            areas: res.data.data
          })
          wx.setStorageSync('areaList', res.data.data)
        }
      })
    }else{
      _this.setData({
        areas
      })
    }    
  },
  //保存浏览记录
  _saveTrack: function (){
    const { id, name} = this.data
    const album = this.data.albums.length ? this.data.albums[0].thumb :'/static/images/icon-red/nopic.png'
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
  //点赞收藏
  handlerLike:function(e){
    const {id} = e.currentTarget.dataset
    const _this = this
    const type = _this.data.liked ? 'dislike' : 'like'
    comm.request({
      loadingMsg: type=='like'?'收藏中...':'取消收藏中...',
      url: glbd.host + 'products/setLike/' + id,
      method: glbd.method,
      data: comm.requestData(glbd,{
        type
      }),
      success: function (res) {
        if(res.data.data) _this.setData({
          liked:!_this.data.liked
        })
        comm.refreshPkey(glbd, res.data.data.pkey)
      }
    })
  },
  //跳转至评价填写
  handlerNavigatorToCommentAdd:function(e){
    const {id}  =e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_comment_add/product_comment_add?product_id='+id,
    })
  },
  //跳转至评价列表
  handlerNavigatorToCommentList: function (e) {
    const detail = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_comment_list/product_comment_list?product_id=' + detail.id+'&detail='+JSON.stringify(detail),
    })
  },
  //跳转至附近商家
  handlerNavigatorToNearBy: function (e) {
    const {id} = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_merchant_near_by/product_merchant_near_by?product_id=' + id,
    })
  },
  //显示/隐藏筛选页面
  handlerShowMerchantFilter:function(){
    this.setData({
      'style.show_merchant_filter': !this.data.style.show_merchant_filter
    })
  },
  //显示/隐藏筛选项
  handlerShowMerchantFilterOption:function(e){
    const {type}  =e.currentTarget.dataset
    this.setData({
      ['style.show_'+type]:!this.data.style['show_'+type]
    })
  },
  //设置商户筛选选项
  handlerSetMerchantFilter:function(e){
    const {key,value,style,name} =e.currentTarget.dataset
    //点击已选的选项，取消选中
    if (this.data.merchant_filter[key] == value){
      this.setData({
        ['merchant_filter.' + key]: null,
        ['merchant_filter_name.' + style]: this.data.default_merchant_filter_name[style],
        ['style.show_' + style]: false
      })
    }else{
      if(key=='area_id' || key==='district_id') {
        this.setData({
          'merchant_filter.area_id': null,
          'merchant_filter.district_id':null,
        })
      }
      this.setData({
        ['merchant_filter.' + key]: value,
        ['merchant_filter_name.' + style]: name,
        ['style.show_' + style]: false
      })
    }
    
  },
  //重置商户筛选选项
  handlerMerchantFilterReset:function(){
    this.setData({
      merchant_filter:{},
      merchant_filter_name: Object.assign({}, this.data.default_merchant_filter_name),
    })
  },
  //确认选项，刷新商户
  handlerMerchantFilterConfirm:function(){
    const _this = this
    const merchant_filtered = Object.values(_this.data.merchant_filter).some((item) => item)

    _this.setData({
      'style.merchant_filtered': merchant_filtered,
      'style.show_merchant_filter': false,
      page:1,
      merchant_reach_bottom:0
    })
    _this.getMerchantList()
  },
  //跳转至商户详情
  handlerNavigatorToMerchantDetail:function(e){
    const {merchant_id} =e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/merchant_detail/merchant_detail?id='+merchant_id,
    }) 
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
  //产品图片不存在
  handlerImageError: function (e) {
    const index = e.currentTarget.dataset.index.split('.')
    switch (index.length) {
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
  //用户点击右上角分享
  onShareAppMessage: function () {
    const _this = this
    _this.shareCount()
  },
  shareCount:function(e){
    const _this= this
    comm.request({
      url: glbd.host + 'products/share-count/' + _this.data.id,
      method: glbd.method
    })
  },
})