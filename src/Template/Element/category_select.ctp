<?php ?>
<!-- $search 是否为搜索框 -->

<div class="layui-form-item">
  <label class="layui-form-label">分类</label>
  <div class="layui-input-inline">
    <select name="<?php if (isset($search) && $search): ?>search[zone_id]<?php else: ?>zone_id<?php endif ?>" lay-filter="zone" id="zone">
      <option value="">请选择空间</option>
      <?php foreach ($zones as $key => $value): ?>
      <option value="<?=$key?>" <?php if (isset($product) && $key==$product->zone_id): ?>
        selected
      <?php endif ?>><?=$value?></option>
      <?php endforeach?>
    </select>
  </div>
  <div class="layui-input-inline">
    <select name="<?php if (isset($search) && $search): ?>search[group_id]<?php else: ?>group_id<?php endif ?>" lay-filter="group" id="group">
      <option value="">请选择分组</option>
      <?php foreach ($groups as $key => $value): ?>
      <option value="<?=$key?>" <?php if (isset($product) && $key==$product->group_id): ?>
        selected
      <?php endif ?>><?=$value?></option>
      <?php endforeach?>
    </select>
  </div>
  <div class="layui-input-inline">
    <select name="<?php if (isset($search) && $search): ?>search[category_id]<?php else: ?>category_id<?php endif ?>" lay-filter="category" id="category" <?php if (!isset($search)): ?>lay-verify="required"<?php endif ?>>
      <option value="">请选择分类</option>
      <?php foreach ($categories as $key => $value): ?>
      <option value="<?=$key?>" <?php if (isset($product) && $key==$product->category_id): ?>
        selected
      <?php endif ?>><?=$value?></option>
      <?php endforeach?>
    </select>
  </div>
</div>
<?php ?>
