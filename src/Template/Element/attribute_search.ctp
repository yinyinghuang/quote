<?php ?>
<div class="demoTable">
  <form class="layui-form">
    <input type="hidden" name="category_id" value="<?= $category_id?>" id="category_id">    
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
          <input type="text" name="search[name]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <?php if (!$category_id): ?>        
        <div class="layui-inline">
          <label class="layui-form-label">分类</label>
          <div class="layui-input-block">
              <input type="text" id="category_name" autocomplete="off" class="layui-input">
          </div>
        </div>
      <?php endif ?>
    </div>    
    <div class="layui-form-item">
        <label class="layui-form-label">筛选项</label>
        <div class="layui-input-block">
            <input type="radio" name="search[is_filter]" value="1" title="是">
            <input type="radio" name="search[is_filter]" value="0" title="否">
            <input type="radio" name="search[is_filter]" value="2" title="不限">
        </div>
    </div>    
    <div class="layui-form-item">
        <label class="layui-form-label">筛选项类型</label>
        <div class="layui-input-block">
            <input type="radio" name="search[filter_type]" value="1" title="单选">
            <input type="radio" name="search[filter_type]" value="2" title="多选">
            <input type="radio" name="search[filter_type]" value="0" title="不限">
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
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-attributes">搜索</button>
      </div>
    </div>
  </form>
</div>