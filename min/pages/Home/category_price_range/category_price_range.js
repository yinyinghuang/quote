// pages/Home/category_price_range/category_price_range.js
// pages/Home/category_filter_option/category_filter_option.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    price_max: 0,
    price_min: 0,
    step:1,
  },

  //从筛选页跳转至此页
  onLoad: function (options) {
    const cate_filter_page = getCurrentPages()[getCurrentPages().length - 2]['__data__']
    
    const { price_max, price_min} = options
    let step = 100
    const active_range = cate_filter_page.price.split('-')
    
    this.setData({
      ...options,
      min: active_range.length === 2 && active_range[0]?active_range[0]:price_min,
      max: active_range.length === 2 && active_range[1]? active_range[1] :price_max,
      cate_filter_page,
      step
    })
    app.openSetting(this.initPage)
  },

  initPage: function () {
    const _this = this
  },
  handlerSliderChange: function (e) {
    const {type} = e.currentTarget.dataset
    const {value} = e.detail
    this.setData({
      [type]: value
    })
  },
  //跳转回筛选页，并将已选筛选项值存入globalData中
  handlerNavigatorToCateFilter: function () {    
    
    if (this.data.min !== this.data.price_min || this.data.max !== this.data.price_max){
      let max, min;
      [min, max] = [Math.min(this.data.min, this.data.max), Math.max(this.data.min, this.data.max)]      
      if (min < max){
        if (min == this.data.price_min) { min = '' }
        if (max == this.data.price_max) { max=''}
        if(max==='' && min===''){
          this.data.cate_filter_page.price = '';
        }else{
          this.data.cate_filter_page.price = [min,max].join('-')
        }
      }
    }    
    glbd.cate_filter_page = this.data.cate_filter_page
    wx.navigateBack({
      delta: 1
    })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})