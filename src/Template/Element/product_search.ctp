
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
          <input type="text" name="search[name]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">品牌</label>
        <div class="layui-input-block">
          <input type="text" name="search[brand]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">行货价格</label>
        <div class="layui-input-inline" style="width: 100px;">
          <input type="text" name="search[price_hong_min]" placeholder="$" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
          <input type="text" name="search[price_hong_max]" placeholder="$" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">水货价格</label>
        <div class="layui-input-inline" style="width: 100px;">
          <input type="text" name="search[price_water_min]" placeholder="$" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid">-</div>
        <div class="layui-input-inline" style="width: 100px;">
          <input type="text" name="search[price_water_max]" placeholder="$" autocomplete="off" class="layui-input">
        </div>
      </div>

    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">分类</label>      
      <?= $this->element('casecade_select',$category_select)?>
    </div>
    <div class="layui-form-item">
    <label class="layui-form-label">新品</label>
      <div class="layui-input-block">
        <input type="radio" name="search[is_new]" value="1" title="是">
        <input type="radio" name="search[is_new]" value="0" title="否">
        <input type="radio" name="search[is_new]" value="2" title="不限">
      </div>
    </div>
    <div class="layui-form-item">
    <label class="layui-form-label">热门</label>
      <div class="layui-input-block">
        <input type="radio" name="search[is_hot]" value="1" title="是">
        <input type="radio" name="search[is_hot]" value="0" title="否">
        <input type="radio" name="search[is_hot]" value="2" title="不限">
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
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-products">搜索</button>
      </div>
    </div>
  </form>
</div>