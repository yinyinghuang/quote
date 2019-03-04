// pages/Home/category_filter_option/category_filter_option.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    option:{},
    options:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      options: options.selected?JSON.parse(options.selected):[]
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
    comm.request({
      url: glbd.host + 'category/get-category-filter-option?category_attribute_id=' + category_attribute_id,
      method:glbd.method,
      success:function(res){
        _this.data.options.map((selected) => {

        })
        res.data.data.forEach((item) => {

        })
      },
    })
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})