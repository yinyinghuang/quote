// pages/Home/index/index.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    category:{},
    recent:{},
    last:{}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    //每个页面必须判断用户是否授权
    app.openSetting(this.initPage)
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    const _this = this
    _this.getRecentViewList()
  },

  /**
   * 初始化页面
   */
  initPage:function(){
    const _this = this
    _this.getCategoryList()
    _this.getLastProductList()
  },
  /**
   * 获取一级分类
   */
  getCategoryList:function(){
    const _this = this
    comm.request({
      url:glbd.host + 'categories/lists',
      method:glbd.method,
      data:comm.requestData(glbd,{type:'zones'}),
      success:function(res){
        if(res.data.errCode === 0){
          _this.setData({
            category:res.data.data
          })
        }else{
          comm.showToast(res.data.errMsg ? res.data.errMsg : '分类获取失败')
        }
      },
      fail:function(){
        comm.showToast('分类获取失败')
      }
    })
  },
  /**
   * 获取最近浏览
   */
  getRecentViewList: function () {
    const _this = this
    _this.setData({
      recent:wx.getStorageSync('recent')
    })
    
  },
  /**
   * 获取最新更新
   */
  getLastProductList: function () {
    const _this = this
    comm.request({
      url: glbd.host + 'products/lists',
      method: glbd.method,
      data: comm.requestData(glbd, { type: 'last' }),
      success: function (res) {
        if (res.data.errCode === 0) {
          let time = Date.now()
          res.data.data.map((item) => {
            item.album = item.cover ? glbd.hosts+ item.cover+'?t='+time : '/static/image/icon/red/nopic.png'
            delete (item.albums)
          })
          _this.setData({
            last: res.data.data
          })
        } else {
          comm.showToast(res.data.errMsg ? res.data.errMsg : '最新更新获取失败')
        }
      },
      fail: function () {
        comm.showToast('最新更新获取失败')
      }
    })
  },
  //跳转至产品详情
  handlerNavigatorToProductDetail: function ({currentTarget:{dataset}}){
    const {id,album,name} = dataset
    let recent = wx.getStorageSync('recent')
    recent = recent ? recent :[]
    
    for (let i in recent) {
      if (recent[i].id === id) {
        recent.splice(i,1)
        break
      }
    }
    recent.unshift({ id, album, name,time: Date.now() })
    wx.setStorageSync('recent', recent.slice(0,50))

    wx.navigateTo({ url: '/pages/Home/product_detail/product_detail?product_id='+id})
  },
  //跳转至分类列表
  handlerNavigatorToCateList: function (e) {
    const id = e.currentTarget.dataset.id
    console.log(id)
    wx.navigateTo({ url: '/pages/Home/category_list/category_list?zone_id=' + id })
  },
  //产品图片不存在
  hanlderImageError:function(e){
    this.data.last[e.currentTarget.dataset.id].album = '/static/images/icon-red/nopic.png';
    this.setData({
      last:this.data.last
    })
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})