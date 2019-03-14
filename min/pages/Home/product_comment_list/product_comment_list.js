// pages/Home/product_comment_list/product_comment_list.js
let app = getApp()
let glbd = app.globalData
const comm = require('../../../common/common.js')
Page({

  /**
   * 页面的初始数据
   */
  data: {
    comment_list:[],
    page:1,
    comment_reach_bottom:false,
    commented:1,
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
    const detail = JSON.parse(options.detail)
    const product_id = options.product_id
    this.setData({
      ...detail,
      product_id
    })
    app.openSetting(this.initPage)
  },
  initPage:function(){
    const _this = this
    _this.getCommentList()
  },
  getCommentList:function(){
    const { product_id, page, comment_reach_bottom} = this.data    
    if (comment_reach_bottom) return false
    const _this = this
    comm.request({
      loadingMsg: '获取评论中...',
      url: glbd.host + 'products/comment-lists/' + product_id,
      method: glbd.method,
      data:{page},
      success: function (res) {
        const comment_list = res.data.data
        _this.setData({
          comment_reach_bottom:!comment_list.length,
          comment_list:page==1?comment_list:_this.data.comment_list.concat(comment_list),
          page:page+1
        })
      }
    })
  },
  //页面上拉触底事件的处理函数
  onReachBottom: function () {
    const _this = this
    _this.getCommentList()
  },
  //产品图片不存在
  hanlderImageError: function (e) {
    this.setData({
      'thumb.middle': '/static/images/icon-red/nopic.png'
    })
  },
  //跳转至产品详情
  handlerNavigatorToProductDetail:function(e){
    const {id} = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_detail/product_detail?id='+id,
    })
  },
  //跳转至评价填写
  handlerNavigatorToCommentAdd: function (e) {
    const { id } = e.currentTarget.dataset
    wx.navigateTo({
      url: '/pages/Home/product_comment_add/product_comment_add?product_id=' + id,
    })
  },
  //用户点击右上角分享
  onShareAppMessage: function () {
    if (this.data.albums.length){
      return {
        title: this.data.name,
        imageUrl: this.data.albums[0].middle
      }
    }else{
      return {
        title: this.data.name
      }
    }
    
  }
})