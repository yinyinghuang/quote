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
    selected:{},//选中的筛选项
  },

  //从列表页进入此页
  onLoad: function (options) {
    const product_list_page = getCurrentPages()[getCurrentPages().length - 2]['__data__']
    this.setData({
      category_id: options.category_id,
      product_list_page,
      //同步选项信息
      selected: product_list_page.filter_selected
    })
    app.openSetting(this.initPage)
  },
  //筛选项值页跳转回时
  onShow: function () {
    if (glbd.cate_filter_page) {
      this.setData({
        ...glbd.cate_filter_page
      })
      delete (glbd.cate_filter_page)
      const filter = this.matchFilterSelected(this.data.filter)
      this.setData({filter})
    }
    
  },
  //初始化页面
  initPage: function () {
    const _this = this
    _this.getCategoryRelated(_this.data.category_id)

  },
  //获取分类相关信息
  getCategoryRelated: function (category_id){
    const _this = this 
    comm.request({
      url:glbd.host+'categories/get-category-attribute-is-filter?category_id=' + category_id,
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
    const selected = _this.data.selected
    selected && data.forEach((item) => {
      item.selected = '全部'
      
      if (selected[item.id] && comm.type(selected[item.id]) === '[object Object]') {
        const selected_name_array = Object.values(selected[item.id])
        if (selected_name_array.length) item.selected = selected_name_array.join(',')
      }
    })
    return data
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
    this.data.product_list_page.filter_selected = this.data.selected
    this.data.product_list_page.is_filtered = !!Object.keys(this.data.selected).length
    glbd.product_list_page = this.data.product_list_page
    wx.navigateBack({
      delta: 1
    })
  },
  handlerReset:function(){
    this.setData({
      selected: {}
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