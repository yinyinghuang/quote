// pages/Home/category_filter_option/category_filter_option.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    option: [],
    category_id: 0,
    filter_type: 1,
    selected:'',
    scrollTop: 0,
    currentAlpha:''
  },

  //从筛选页跳转至此页
  onLoad: function (options) {
    const cate_filter_page = getCurrentPages()[getCurrentPages().length - 2]['__data__']
    this.setData({
      ...options,
      cate_filter_page,
      selected:cate_filter_page.brand
    })
    app.openSetting(this.initPage)
  },

  initPage: function () {
    const _this = this
    if (!_this.data.category_id) {
      comm.showToast('category_id缺失')
      wx.navigateTo({
        url: '/pages/Home/index/index',
      })
    }
    _this.getCategoryBrand(_this.data.category_id)
  },
  //获取筛选项选项值
  getCategoryBrand: function (category_id) {
    const _this = this
    comm.request({
      url: glbd.host + 'categories/get-category-brand/' + category_id,
      method: glbd.method,
      success: function (res) {
        //提取出首字符列表
        const alphas = Object.keys(res.data.data);
        //原始已选项与选项列表结合
        const option = _this.matchOptionSelected(res.data.data)
        _this.setData({
          option,
          alphas
        })
      },
    })
  },
  //匹配筛选项是否已有选项值
  matchOptionSelected: function (data) {
    const {selected} = this.data

    if (selected) {
      for(let alpha in data){
        data[alpha].some((item) => {
          const flag = selected === item.name
          item.selected = flag ? 1 : 0
          return flag
        })
      }
    }
    return data
  },
  //滚动至相应首字母品牌处
  handleScrollTo:function(e){
    const _this =this
    const alphaIndex = e.currentTarget.dataset.index
    
    const query = wx.createSelectorQuery()
    query.selectAll('.option-title-alpha').boundingClientRect()
    query.exec(function (res) {
      if (!res.length) return
      let scrollTop = 0
      const margin_bottom = 0
      for (let i = 0; i < alphaIndex; i++) {
        const cur = res[0][i]
        scrollTop += cur.height + margin_bottom
      }
      _this.setData({
        scrollTop,
        currentAlpha:_this.data.alphas[alphaIndex]
      })
    })
  },
  handlerSelect: function (e) {

    const { alpha,index } = e.currentTarget.dataset
    let { option,selected } = this.data
    //当前选项为已选中，取消选中，清除seleted
    if (option[alpha][index]['selected']){
      selected =''
      option[alpha][index]['selected'] = false
    }else{
      if (this.data.filter_type == 1) {
        if (!option[alpha][index]['selected']) {
          option[alpha].some((item) => {
            if (item.selected) {
              item.selected = !1
              return 1
            }
          })
        }
      }
      option[alpha][index]['selected'] = !option[alpha][index]['selected']
      selected = option[alpha][index].name
    }
    this.setData({
      option,
      selected,
    })

  },
  //跳转回筛选页，并将已选筛选项值存入globalData中
  handlerNavigatorToCateFilter: function () {
    const {selected} = this.data
    this.data.cate_filter_page.brand = this.data.selected
    
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