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
          <input type="text" name="search[name]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">空间</label>
        <div class="layui-input-block">
          <select name="search[zone_id]">
            <option value="">请选择空间</option>
            <?php foreach ($zones as $key => $value): ?>
            <option value="<?=$key?>"><?=$value?></option>
            <?php endforeach?>
          </select>
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">可见</label>
        <div class="layui-input-block">
          <input type="checkbox" name="search[is_visible]" lay-skin="switch" lay-text="是|否">
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-groups">搜索</button>
      </div>
    </div>
  </form>
</div>
<?= $this->element('table',$tableParams['groups'])?>

