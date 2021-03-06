// pages/Home/category_filter/category_filter.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    filter:{},//筛选项列表
    category_id:0,
    filter_selected:{},//选中的筛选项
    category:{},
    brand:'',
    price:''
  },

  //从列表页进入此页,将列表页中已有参数加入data中
  onLoad: function (options) {
    const product_list_page = getCurrentPages()[getCurrentPages().length - 2]['__data__']
    this.setData({
      category_id: options.category_id,
      product_list_page,
      category: product_list_page.category,
      //同步选项信息
      filter_selected: product_list_page.filter_selected,
      brand: product_list_page.brand,
      price: product_list_page.price,
    })
    app.openSetting(this.initPage)
  },
  //筛选项值页跳转回时，同步已选参数信息，同时需要更新product_list_page
  onShow: function () {
    if (glbd.cate_filter_page) {
      let { product_list_page } = this.data
      product_list_page.brand = glbd.cate_filter_page.brand
      product_list_page.price = glbd.cate_filter_page.price
      product_list_page.filter_selected = glbd.cate_filter_page.filter_selected

      this.setData({
        ...glbd.cate_filter_page,
        product_list_page
      })
      delete (glbd.cate_filter_page)
      const filter = this.matchFilterSelected(this.data.filter)
      this.setData({filter})
    }
    
  },
  //初始化页面
  initPage: function () {
    const _this = this
    _this.getCategoryAttributeIsFilter(_this.data.category_id)

  },
  //获取分类相关信息
  getCategoryAttributeIsFilter: function (category_id){
    const _this = this 
    comm.request({
      url:glbd.host+'categories/get-category-attribute-is-filter/' + category_id,
      method:glbd.method,
      success:function(res){
        const filter = _this.matchFilterSelected(res.data.data)
        _this.setData({
          filter,
        }) 
      }
    })
  },
  //匹配筛选项是否已有选项值
  matchFilterSelected:function(data){
    const _this = this
    const {filter_selected} = _this.data
    filter_selected && data.forEach((item) => {
      item.selected = '全部'      
      if (filter_selected[item.id] && comm.type(filter_selected[item.id]) === '[object Object]') {
        const selected_name_array = Object.values(filter_selected[item.id])
        if (selected_name_array.length) item.selected = selected_name_array.join(',')
      }
    })
    return data
  },
  handlerNavigatorToPrice:function(e){
    const { price_max, price_min} = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/category_price_range/category_price_range?price_max=' + price_max+'&price_min='+price_min,
    })
  },
  //选择品牌筛选项，品牌列表
  handlerNavigatorToBrand: function (e) {
    const { category_id } = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/category_brand_list/category_brand_list?category_id=' + category_id,
    })
  },
  //选择筛选项，进入筛选项选项页
  handlerNavigatorToFilterOption:function(e){
    const { id, filter_type,attribute_name} = e.currentTarget.dataset
    
    wx.navigateTo({
      url: '/pages/Home/category_filter_option/category_filter_option?category_attribute_id=' + id + '&filter_type=' + filter_type + '&attribute_name=' + attribute_name,
    })
  },
  //确认后跳转至列表页
  handlerNavigatorToProductList:function(){
    //将选择结果同步至列表页data
    this.data.product_list_page.filter_selected = this.data.filter_selected
    this.data.product_list_page.brand = this.data.brand
    this.data.product_list_page.price = this.data.price
    this.data.product_list_page.is_filtered = Object.keys(this.data.filter_selected).length || this.data.brand || this.data.price
    glbd.product_list_page = this.data.product_list_page
    wx.navigateBack({
      delta: 1
    })
  },
  handlerReset:function(){
    this.setData({
      filter_selected: {},
      brand: '',
      price: ''
    })
    const filter = this.matchFilterSelected(this.data.filter)
    this.setData({
      filter
    })
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})