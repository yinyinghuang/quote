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
    products:[],
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
      products: {
        page: 1,
        reach_bottom: false
      },
      comments: {
        page: 1,
        reach_bottom: false
      },
    },
    active:'recents',
    userInfo:null
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
    app.openSetting(this.initPage)
  },
  //下拉刷新
  onPullDownRefresh:function(){
    this.setData({
      params:{
        recents:{
          page:1,
          reach_bottom:false
        },
        merchants: {
          page: 1,
          reach_bottom: false
        },
        products: {
          page: 1,
          reach_bottom: false
        },
        comments: {
          page: 1,
          reach_bottom: false
        },
      },
    })
    this.getList()
  },
  initPage:function(){
    this.getUserInfo()
    this.getList()
  },
  //获取用户详情
  getUserInfo:function(){
    const _this = this
    if (wx.getStorageSync('user_info')) {
      _this.setData({
        userInfo: wx.getStorageSync('user_info')
      })
    }else{
      wx.getUserInfo({
        success: (res) => {
          _this.setData({
            userInfo: res.userInfo
          })
        },
        fail: (res) => {
          wx.redirectTo({
            url: '/pages/login/login',
          })
        }
      })
    }
    wx.login({
      success:function(res){glbd.code = res.code},
    })
  },
  //获取各种列表
  getList:function(){
    const {active} = this.data
    switch(active){
      case 'recents':
        this._getRecentView()
        break
      case 'merchants':
        this.getMerchants()
        break
      case 'products':
        this.getCollections()
        break
      case 'comments':
        this.getComments()
        break
    }
  },
  //获取最近浏览记录
  _getRecentView:function(){
    const localStorage = wx.getStorageSync('recent')
    const _this = this
    if (localStorage && localStorage.length>0){
      let product_ids = [];
      localStorage.forEach((item) => {
        product_ids.push(item.id)
      })
      if(product_ids.length>0){
        comm.request({
          url: glbd.host + 'products/lists/',
          method: glbd.method,
          data: { product_ids },
          success: function (res) {
            let data = res.data.data
            let time = Date.now()
            data.forEach((product) => {
              product.album = product.cover ? glbd.hosts + product.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
              delete (product.albums)
              if (product.price_hong_max) product.price_hong_max = comm.formatPrice(product.price_hong_max)
              if (product.price_hong_min) product.price_hong_min = comm.formatPrice(product.price_hong_min)
              if (product.price_water_max) product.price_water_max = comm.formatPrice(product.price_water_max)
              if (product.price_water_min) product.price_water_min = comm.formatPrice(product.price_water_min)
            })
            _this.setData({
              recents: data,
              'params.recents.page': 2,
              'params.recents.reach_bottom': 1,
            })
            return
          }
        })
      }
    }
    _this.setData({
      'params.recents.page': 2,
      'params.recents.reach_bottom': 1,
    })
    
  },
  //获取收藏店铺
  getMerchants: function () {
    const _this = this
    const {page,reach_bottom} = this.data.params.merchants
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'fans/merchant-lists/' + glbd.pkey,
      method: glbd.method,
      data: comm.requestData(glbd,{page}),
      success: function (res) {
        let data = res.data.data
        let time = Date.now()
        data.forEach((merchant) => {
          merchant.liked=1
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
    const { page, reach_bottom } = this.data.params.products
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'fans/product-lists/' + glbd.pkey,
      method: glbd.method,
      data: comm.requestData(glbd, { page }),
      success: function (res) {
        let data = res.data.data
        let time = Date.now()
        data.forEach((product) => {
          product.liked=1
          product.album = product.cover ? glbd.hosts + product.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (product.albums)
          if (product.price_hong_max) product.price_hong_max = comm.formatPrice(product.price_hong_max)
          if (product.price_hong_min) product.price_hong_min = comm.formatPrice(product.price_hong_min)
          if (product.price_water_max) product.price_water_max = comm.formatPrice(product.price_water_max)
          if (product.price_water_min) product.price_water_min = comm.formatPrice(product.price_water_min)
        })
        _this.setData({
          products: page == 1 ? data : _this.data.products.concat(data),
          'params.products.page': page + 1,
          'params.products.reach_bottom': !data.length,
        })
      }
    })
  },
  //获取发表的评论
  getComments: function () {
    const _this = this
    const { page, reach_bottom } = this.data.params.comments
    if (reach_bottom) return false
    comm.request({
      url: glbd.host + 'fans/comment-lists/' + glbd.pkey,
      method: glbd.method,
      data: comm.requestData(glbd, { page }),
      success: function (res) {
        let data = res.data.data
        let time = Date.now()
        data.forEach((product) => {
          product.album = product.cover ? glbd.hosts + product.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (product.albums)
          if (product.price_hong_max) product.price_hong_max = comm.formatPrice(product.price_hong_max)
          if (product.price_hong_min) product.price_hong_min = comm.formatPrice(product.price_hong_min)
          if (product.price_water_max) product.price_water_max = comm.formatPrice(product.price_water_max)
          if (product.price_water_min) product.price_water_min = comm.formatPrice(product.price_water_min)
        })
        _this.setData({
          comments: page == 1 ? data : _this.data.comments.concat(data),
          'params.comments.page': page + 1,
          'params.comments.reach_bottom': !data.length,
        })
      }
    })
  },
  //切换显示内容
  handlerToggleAtice:function(e){
    const {active} =e.currentTarget.dataset
    this.setData({active})
    if(this.data.params[active].page==1){
      this.getList()
    }
  },
  //跳转至产品详情
  handlerNavigatorToProductDetail: function (e) {
    const { id } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/product_detail/product_detail?id=' + id })
  },
  //跳转至商户详情
  handlerNavigatorToMerchantDetail: function (e) {
    const { id } = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/merchant_detail/merchant_detail?id=' + id,
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
  //跳转至评价列表
  handlerNavigatorToCommentList: function (e) {
    const detail = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_comment_list/product_comment_list?product_id=' + detail.id + '&detail=' + JSON.stringify(detail),
    })
  },
  //点赞收藏
  handlerLike: function (e) {
    const { index,model } = e.currentTarget.dataset
    const _this = this
    const cur = _this.data[model][index]
    const type = cur.liked ? 'dislike' : 'like'
    comm.request({
      loadingMsg: type == 'like' ? '收藏中...' : '取消收藏中...',
      url: glbd.host + model + '/setLike/' + _this.data[model][index].id,
      method: glbd.method,
      data: comm.requestData(glbd, {
        type
      }),
      success: function (res) {
        if (res.data.data) {
          _this.data[model][index].liked = !cur.liked
          _this.setData(_this.data)
        }
      }
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
      case 4:
        this.data[index[0]][index[1]][index[2]][index[3]] = '/static/images/icon-red/nopic.png'
        break
    }
    this.setData(this.data)
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const {active,params} = this.data
    if(params[active].reach_bottom) return false
    this.getList()
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})