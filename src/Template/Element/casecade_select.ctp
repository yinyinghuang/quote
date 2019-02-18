<?php ?>
<!-- $search 是否为搜索框 -->

<!-- <div class="layui-form-item">
  <label class="layui-form-label">分类</label> -->
  <?php foreach ($selects as $name => $select): ?>
    <?php if ($select['show']): ?>
         <div class="layui-input-inline">
           <select 
             name="<?php if (isset($search) && $search): ?>search[<?= $name?>_id]<?php else: ?><?= $name?>_id<?php endif ?>" 
             lay-filter="<?= $name?>"
             <?php if ($select['disabled']): ?> disabled<?php endif ?> class="<?= $name?>">
             <option value="">请选择<?= $select['label']?></option>
             
             <?php foreach ($select['options'] as $key => $value): ?>
             <option 
               value="<?=$key?>" 
               <?php if ($key==$select[$name.'_id']): ?>selected<?php endif ?>>
               <?=$value?>
             </option>
             <?php endforeach?>  
             
             
           </select>
         </div> 
    <?php endif ?>
    
  <?php endforeach ?>
<!-- </div> -->

