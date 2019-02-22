<?php ?>
<div class="demoTable">
  <form class="layui-form">
    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="search[brand]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
    </div>    
    <div class="layui-form-item">
        <label class="layui-form-label">可见</label>
        <div class="layui-input-block">
            <input type="radio" name="search[is_visible]" value="1" title="是">
            <input type="radio" name="search[is_visible]" value="0" title="否">
            <input type="radio" name="search[is_visible]" value="2" title="不限">
        </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-brands">搜索</button>
      </div>
    </div>
  </form>
</div>