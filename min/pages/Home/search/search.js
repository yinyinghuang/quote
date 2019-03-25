// pages/Home/search/search.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    hots:['ewerwer','ererere'],
    history: [],
    keyword:'',
  },
  //生命周期函数--监听页面加载
  onLoad: function (options) {
    if (options.keyword) this.setData({ keyword: options.keyword })
    app.openSetting(this.initPage)    
  },
  onShow:function(){
    this.initPage()
  },
  initPage:function(){    
    const history = wx.getStorageSync('search_history')
    if (history) this.setData({ history })
    // this.getHotKeyword()
  },
  getHotKeyword:function(){
    const _this = this
    comm.request({
      url: glbd.host + 'products/hot-keyword-lists/',
      method: glbd.method,
      success: function (res) {
        _this.setData({
          hot: res.data.data
        })
      },
    })
  },
  //输入
  handlerInput:function(e){
    const keyword = e.detail.value
    this.setData({
      keyword
    })
  },
  //搜索框确认
  handlerSearch:function(){
    this._navToProductList()   
  },
  //关键词条点击
  handlerKeywordItem:function(e){
    const {keyword} = e.currentTarget.dataset
    this.setData({keyword})
    this._navToProductList()
  },
  //清空搜索历史
  handlerClearHistoryKeyword:function(){
    wx.removeStorageSync('search_history')
    this.setData({
      history:[]
    })
  },
  //跳转至产品列表
  _navToProductList: function () {
    const {keyword} = this.data
    if(!keyword){
      comm.showToast('关键词不能为空')
    }else{
      let {history}  = this.data
      for(let i=0;i<history.length;i++){
        if(history[i]===keyword){
          history.splice(i,1)          
          break
        }
      }
      history.unshift(keyword)
      wx.setStorageSync('search_history', history )
      wx.navigateTo({
        url: '/pages/Home/product_list/product_list?keyword=' + keyword,
      })
    }    
  },
})