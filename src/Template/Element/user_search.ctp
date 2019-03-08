<?php ?>
<div class="demoTable">
  <form class="layui-form">
    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">ID</label>
        <div class="layui-input-block">
          <input type="number" name="search[id]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="search[username]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
    </div>    
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-users">搜索</button>
      </div>
    </div>
  </form>
</div>