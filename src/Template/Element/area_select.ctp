<?php ?>
<!-- $search 是否为搜索框 -->

<div class="layui-form-item">
  <label class="layui-form-label">地区</label>
  <div class="layui-input-inline">
    <select name="<?php if (isset($search) && $search): ?>search[area_id]<?php else: ?>area_id<?php endif ?>" lay-filter="area" id="area">
      <option value="">请选择区</option>
      <?php foreach ($areas as $key => $value): ?>
      <option value="<?=$key?>" <?php if (isset($merchant) && $key==$merchant->area_id): ?>
        selected
      <?php endif ?>><?=$value?></option>
      <?php endforeach?>
    </select>
  </div>
  <div class="layui-input-inline">
    <select name="<?php if (isset($search) && $search): ?>search[district_id]<?php else: ?>district_id<?php endif ?>" lay-filter="district" id="district" <?php if (!isset($search)): ?>lay-verify="required"<?php endif ?>>
      <option value="">请选择区域</option>
      <?php foreach ($districts as $key => $value): ?>
      <option value="<?=$key?>" <?php if (isset($merchant) && $key==$merchant->district_id): ?>
        selected
      <?php endif ?>><?=$value?></option>
      <?php endforeach?>
    </select>
  </div>
</div>
