//app.js
const comm = require('./common/common.js')
App({
  // 判断用户是否授权
  openSetting:function(_fn = function(){}){
    const _this = this;
    // _this.initPage(_fn)
    console.log('是否已经取消', _this.globalData.cancel)
    wx.getSetting({
      success:(res) =>{
        const userInfo = res.authSetting['scope.userInfo']
        if (!_this.globalData.cancel){
          if (userInfo) {
            _this.initPage(_fn)

          } else {
            const page = getCurrentPages()[0];
            wx.redirectTo({
              url: '../../login/login?pb=/' + page.route + '&op=' + JSON.stringify(page.options),
            })
          }
        }else{
          _this.initPage(_fn)
        }
        
      }
    })
  },
  //已授权，是否已存在登陆信息
  initPage:function(_fn = function(){}){
    const _this= this
    if(_this.globalData.pkey || wx.getStorageSync('local_pkey')){
      if (!_this.globalData.pkey) _this.globalData.pkey = wx.getStorageSync('local_pkey')
      typeof(_fn) === 'function' && _fn()
    }else{
      _this.login(_fn)
    }
  },
  //登陆后台获取用户信息
  login:function(_fn = function(){},needInfo=false){
    const _this = this
    wx.login({
      success:(loginRes) =>{
        if(loginRes.code){
          _this.globalData.code = loginRes.code
          if(needInfo || _this.globalData.firstCome){
            _this.globalData.firstCome = false
            _this.getUInfo(_fn)
          }else{
            _this.getNoStr(_fn)
          }
        }else{
          comm.showToast('获取用户信息失败')
        }
      },
      fail:(res) => {
        comm.showToast('获取用户信息失败')
      }
    })
  },
  //刷新用户微信后台信息
  getUInfo:function(_fn = function(){}){
    const _this = this
    wx.getUserInfo({
      success:(res) => {
        _this.globalData.islogin = true
        _this.globalData.userInfo = res.userInfo
        wx.setStorageSync('user_info', res.userInfo)
        _this.getNoStr(_fn)
      },
      fail:(res) => {
        _this.globalData.islogin = false
        comm.showToast('获取用户信息失败')
        //???????
      }
    })
  },
  //获取后台用户信息
  getNoStr:function(_fn = function(){}){
    const _this = this
    comm.request({
      loadingMsg:'登录中...',
      url:_this.globalData.host + 'fans/login',
      data: comm.requestData(_this.globalData, {
        user_msg_str: JSON.stringify(_this.globalData.userInfo),
        code: _this.globalData.code
      }),
      method:_this.globalData.method,
      success:function(res){
        if(res.data.errCode === 0){          
          _this.globalData.hasKey = true
          comm.refreshPkey(_this.globalData, res.data.data.pkey)
          _this.saveFormIds()
          typeof(_fn) === 'function' && _fn()
        }else{
          comm.showToast(res.data.errMsg ? res.data.errMsg : '登陆失败')
        }
      },
      fail:function(){
        comm.showToast('登陆失败')
      }
    })
  },
  saveFormIds:function(){

  },
  dealFormIds:function(){

  },
  globalData: {
    host: 'https://equote.527hk.cn/api/',
    hosts:'https://equote.527hk.cn/',
    method:'post',
    islogin:false,
    hasPkey:false,
    firstCome:true,
    pkey:null,
    code:null,
    userInfo:null,
    formIds:''
  }
})