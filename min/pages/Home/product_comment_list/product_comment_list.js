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
  // 评论页面跳转回
  onShow: function () {
    this.initPage()
  },
  initPage:function(){
    const _this = this
    if(!this.data.name) _this.getProductDetail()
    _this.getCommentList()
  },
  //获取产品详情
  getProductDetail: function () {
    const _this = this
    comm.request({
      loadingMsg: '获取产品详情中...',
      url: glbd.host + 'products/detail/' + _this.data.id,
      method: glbd.method,
      data: comm.requestData(glbd),
      success: function (res) {
        let detail = res.data.data
        if (detail.rating == 0 && detail.meta_data && detail.meta_data.comment_score_total && detail.meta_data.comment_count) {
          detail.rating = Math.ceil(detail.meta_data.comment_score_total / detail.meta_data.comment_count)
        }
        detail.albums.forEach((item) => {
          item.middle = glbd.hosts + item.middle
          item.full = glbd.hosts + item.full
          item.thumb = glbd.hosts + item.thumb
        })
        detail.thumb = detail.albums[0]
        if (detail.price_hong_max && detail.price_hong_max == detail.price_hong_min) {
          detail.price_hong_max = 0
          detail.price_hong_min = comm.formatPrice(detail.price_hong_min)
        } else {
          if (detail.price_hong_max) detail.price_hong_max = comm.formatPrice(detail.price_hong_max)
          if (detail.price_hong_min) detail.price_hong_min = comm.formatPrice(detail.price_hong_min)
        }
        if (detail.price_water_max && detail.price_water_max == detail.price_water_min) {
          detail.price_water_max = 0
          detail.price_water_min = comm.formatPrice(detail.price_water_min)
        } else {
          if (detail.price_water_max) detail.price_water_max = comm.formatPrice(detail.price_water_max)
          if (detail.price_water_min) detail.price_water_min = comm.formatPrice(detail.price_water_min)
        }

        let attribute_group = []
        if (detail.attributes.length) {
          const half = Math.ceil(detail.attributes.length / 2)
          attribute_group.push(detail.attributes.slice(0, half))
          attribute_group.push(detail.attributes.slice(half))
        }
        _this.setData({
          ...detail,
          attribute_group
        })
      }
    })
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
  handlerImageError: function (e) {
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