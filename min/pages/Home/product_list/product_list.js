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
      sort: 'default',
      page:1
    },
    filter_selected: {},
    brand: '',
    price:'',
    reach_bottom:false
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

  //筛选项也跳转回时,重新加载页面
  onShow: function () {
    if (glbd.product_list_page) {
      glbd.product_list_page.reach_bottom=0
      glbd.product_list_page.options.page=1
      this.setData({
        ...glbd.product_list_page
      })
      delete (glbd.product_list_page)
      let filter = []
      Object.values(this.data.filter_selected).map((filter_item) => {
        filter = Object.keys(filter_item).concat(filter)
      })
      this.data.options.filter = filter
      this.data.options.brand = this.data.brand
      this.data.options.price = this.data.price
      this.data.options.page = 1
      this.getProductList(this.data.options)
    }

  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const _this = this
    _this.getProductList(_this.data.options)
  },
  //页面相关事件处理函数--监听用户下拉动作
  onPullDownRefresh: function () {
    const _this = this
    _this.getProductList(Object.assign({},_this.data.options,{page:1}))
  },
  //初始化页面
  initPage: function () {
    const _this = this
    if (_this.data.options.category_id ){
      _this.getCategoryRelated(_this.data.options.category_id)
    }       
    _this.getProductList(_this.data.options,_this.data.page)
  },
  //获取分类信息及筛选项
  getCategoryRelated: function (category_id) {
    const _this = this
    comm.request({
      url: glbd.host + 'categories/get-category-related/' + category_id,
      method: glbd.method,
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
    if(_this.data.reach_bottom) return false
    const time = Date.now()
    comm.request({      
      url: glbd.host + 'products/lists?t=' + time,
      method: glbd.method,
      data: options,
      success: function (res) {        
        res.data.data.map((item) => {
          item.album = item.cover ? glbd.hosts + item.cover + '?t=' + time : '/static/image/icon/red/nopic.png'
          delete (item.albums)
          if (item.price_hong_max && item.price_hong_max == item.price_hong_min) {
            item.price_hong_max = 0
            item.price_hong_min = comm.formatPrice(item.price_hong_min)
          } else {
            if (item.price_hong_max) item.price_hong_max = comm.formatPrice(item.price_hong_max)
            if (item.price_hong_min) item.price_hong_min = comm.formatPrice(item.price_hong_min)
          }
          if (item.price_water_max && item.price_water_max == item.price_water_min) {
            item.price_water_max = 0
            item.price_water_min = comm.formatPrice(item.price_water_min)
          } else {
            if (item.price_water_max) item.price_water_max = comm.formatPrice(item.price_water_max)
            if (item.price_water_min) item.price_water_min = comm.formatPrice(item.price_water_min)
          }
        })
        const page = _this.data.options.page
        _this.setData({
          products: page === 1 ? res.data.data:_this.data.products.concat(res.data.data),
          'options.page': page+1,
          reach_bottom:!res.data.data.length
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
  //跳转至分类列表
  handlerNavigatorToCateList: function (e) {
    const {id,type} = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/category_list/category_list?type='+type+'&id=' + id })
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
  //跳转至产品详情
  handlerNavigatorToProductDetail: function (e) {
    const { id } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/product_detail/product_detail?id=' + id })
  },
  //产品图片不存在
  handlerImageError: function (e) {
    this.data.products[e.currentTarget.dataset.id].album = '/static/images/icon-red/nopic.png';
    this.setData({
      products: this.data.products
    })
  },
  //跳转至产品详情
  handlerNavigatorToSearch: function (e) {
    const { keyword } = e.currentTarget.dataset
    wx.navigateTo({ url: '/pages/Home/search/search?keyword=' + keyword })
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})