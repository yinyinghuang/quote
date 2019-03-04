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

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      category_id: options.category_id,
      filter_selected: options.filter_selected?JSON.parse(options.filter_selected):{},
    })
    app.openSetting(this.initPage)
  },
  //初始化页面
  initPage: function () {
    const _this = this
    _this.getCategoryRelated(_this.data.category_id)
  },
  getCategoryRelated: function (category_id){
    const _this = this 
    comm.request({
      url:glbd.host+'categories/get-category-is-filter?category_id=' + category_id,
      method:glbd.method,
      success:function(res){
        res.data.data.forEach((item) => {

        })
       _this.setData({
         filter:res.data.data
       }) 
      }
    })
  },
  handlerNavigatorToFilterOption:function(e){
    const {id,name} = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/category_filter_option/category_filter_option?category_attribute_id='+id+'&category_attribute_name='+name,
    })
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})