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
    selected:[],
    category_attribute_id:0,
    filter_type:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      selected: options.selected?JSON.parse(options.selected):[],
      category_attribute_id: options.category_attribute_id,
      filter_type:options.filter_type
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
        _this.data.selected.forEach((selected_item) => {
          res.data.data.forEach((item) => {
            item.selected = selected_item.id === item.id ? 1:0
          })
        })
        _this.setData({
          option: res.data.data
        })        
      },
    })
  },
  handlerSelect:function(e){
    const {index} = e.currentTarget.dataset
    const {option} = this.data
    const has_be_selected_count = option.some((item) => item.selected)
    option[index]['selected'] = !option[index]['selected'] 

  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})