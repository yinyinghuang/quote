// pages/Home/category_filter_option/category_filter_option.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    option:[],
    category_attribute_id:0,
    filter_type:1,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    const cate_filter_page = getCurrentPages()[getCurrentPages().length - 2]['__data__']
    this.setData({
      category_attribute_id: options.category_attribute_id,
      attribute_name: options.attribute_name,
      filter_type:options.filter_type,
      cate_filter_page

    })
    app.openSetting(this.initPage)
  },

  initPage:function(){
    const _this = this
    if (!_this.data.category_attribute_id){
      comm.showToast('category_attribute_id缺失')
      wx.navigateTo({
        url: '/pages/Home/index/index',
      })
    }
    _this.getCategoryFilterOption(_this.data.category_attribute_id)
  },
  getCategoryFilterOption: function (category_attribute_id){
    const _this = this 
    comm.request({
      url: glbd.host + 'categories/get-category-filter-option?category_attribute_id=' + category_attribute_id,
      method:glbd.method,
      success:function(res){
        //原始已选项与选项列表结合
        const selected = _this.data.cate_filter_page.selected[_this.data.category_attribute_id]
        if (selected){
          res.data.data.forEach((item) => {
            item.selected = selected.id ? 1 : 0
          })
        }
        _this.setData({
          option: res.data.data
        })        
      },
    })
  },
  handlerSelect:function(e){
    
    const {index} = e.currentTarget.dataset
    const {option} = this.data
    if(this.data.filter_type==1){
      if (!option[index]['selected']){
        option.some((item) => {
          if (item.selected) item.selected=!1
          return item.selected
        })
      }      
    }
    option[index]['selected'] = !option[index]['selected'] 
    this.setData({
      option
    })

  },
  //跳转回筛选页，并将筛选页参数存执globalData中
  handlerNavigatorToCateFilter:function(){
    let data = this.data.option.filter((item) => {
      return item.selected      
    })
    if (this.data.filter_type==1) data = data.slice(0,1)
    
    let selected = {}
    data.forEach((option) => {
      selected[option.id] = option.filter
    })
    this.data.cate_filter_page.selected[this.data.category_attribute_id] = selected
    glbd.cate_filter_page = this.data.cate_filter_page
    wx.navigateBack({
      delta:1
    })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})