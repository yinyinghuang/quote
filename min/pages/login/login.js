// pages/login/login.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    pb:'../Home/index/index',
    op:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    const pb = options.pb
    let op = ''
    if (options.op!=='{}'){
      let option = []
      let bp_options = JSON.parse(options.op)
      for (const key in bp_options){
        option.push(`${key}=${bp_options[key]}`)
      }
      op = '?' + option.join('&')
    }
    this.setData({
      pb,
      op
    })    
  },
  /**
   * 用户点击授权后
   */
  redirectPb:function(res){
    const _this = this
    if (res.detail.errMsg === "getUserInfo:fail auth deny"){
      getApp().globalData.cancel = true
    }
    _this.redirect()
  },
  cancel:function(){
    const _this = this
    getApp().globalData.cancel = true
    _this.redirect()
  },

  redirect:function(){
    const _this = this
    const fn = (_this.data.pb === '/pages/Home/index/index' || _this.data.pb === '/pages/My/index/index') ? wx.switchTab : wx.redirectTo
    fn({
      url: _this.data.pb + _this.data.op,
    })
  },
})