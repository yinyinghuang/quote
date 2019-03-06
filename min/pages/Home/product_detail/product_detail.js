// pages/Home/product_detail/product_detail.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    merchants:[]
  },
  //生命周期函数--监听页面加载
  onLoad: function (options) {
    this.setData({
      ...options
    })
    app.openSetting(this.initPage)
  },
  initPage:function(){
    const _this = this 
    _this.getProductDetail()
    _this.getMerchantList()
  },
  //获取产品详情
  getProductDetail: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'products/detail/' + _this.data.id,
      method: glbd.method,
      success: function (res) {
        _this.setData({
          ...res.data.data
        })
        _this._saveTrack()
      }
    })
  },
  //获取在售商户列表
  getMerchantList: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'merchants/list?product_id=' + _this.data.id,
      method: glbd.method,
      success: function (res) {
        _this.setData({
          merchants:res.data.data
        })
      }
    })
  },
  //保存浏览记录
  _saveTrack: function (){
    const { id, album, name} = this.data
    let recent = wx.getStorageSync('recent')
    recent = recent ? recent : []

    for (let i in recent) {
      if (recent[i].id === id) {
        recent.splice(i, 1)
        break
      }
    }
    recent.unshift({ id, album, name, time: Date.now() })
    wx.setStorageSync('recent', recent.slice(0, 50))
  },
  //用户点击右上角分享
  onShareAppMessage: function () {

  }
})