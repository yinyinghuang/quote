<?php ?>


<script type="text/html" id="toolbar-<?= $name?>">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="deleteData-<?= $name?>" lay-filter="deleteBtn-<?= $name?>">删除</button>
    <?php if (isset($addUrl) ||isset($add)): ?>
    <button class="layui-btn layui-btn-sm" lay-event="addData-<?= $name?>" lay-filter="addBtn-<?= $name?>">新增</button>	
    <?php endif ?>
    
  </div>
</script>
<table class="layui-hide" id="LAY_table-<?= $name?>" lay-filter="LAY_table-<?= $name?>"></table>
<?php foreach ($switchTpls as $switchTpl): ?>
<script type="text/html" id="<?=$switchTpl['id']?>">
  <!-- 这里的 checked 的状态只是演示 -->
  <input type="checkbox" name="<?=$switchTpl['name']?>" value="{{d.id}}" lay-skin="switch" lay-text="<?=$switchTpl['text']?>" lay-filter="rowSwitch-<?= $name?>" {{ d.<?=$switchTpl['name']?> == true ? 'checked' : '' }}>
</script>
<?php endforeach?>
<script type="text/html" id="bar-<?= $name?>">
  <?php if (isset($viewUrl)): ?>
    <a class="layui-btn layui-btn-xs" href="<?= $viewUrl?>/{{d.id}}" >查看/编辑</a>
  <?php endif ?>  
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del-<?= $name?>">删除</a>
</script>