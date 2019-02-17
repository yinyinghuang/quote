<?php?>
<div class="search_box">
  <form class="layui-form">
<?php foreach ($search['form_item'] as $formItem): ?>
  	<div class="layui-form-item">
  	<?php //行内不止一个字段，内联表单 ?>
	<?php if (count($formItem)>1): ?>
		<div class="layui-inline">
	<?php endif ?>
		<?php //遍历行中字段 ?>
		<?php foreach ($formItem as $field): ?>
			<label class="layui-form-label"><?= $field['label']?></label>
			<div class="layui-input-block">
		    <?php if (in_array($field['type']==='base')): ?>
			  	<?php foreach ($field['option'] as $option): ?>
			  	<input type="<?= $option['type']?>" name="search[<?= $option['name']?>]" class="layui-input" autocomplete="off"
			  	 <?php if (isset($option['value'])): ?>
			  		value="<?= $option['value']?>"
			  	<?php endif ?> <?php if ($option['placeholder']): ?>
			  		placeholder="<?= $option['placeholder']?>"
			  	<?php endif ?> <?php if (isset($option['title'])): ?>
			  		title="<?= $option['title']?>"
			  	<?php endif ?>>	
			  	<?php endforeach ?>
			<?php elseif($field['type'] === 'range'): ?>
				<?php foreach ($field['option'] as $key => $option): ?>
				<div class="layui-input-inline" style="width: 100px;">
				  	<input type="<?= $option['type']?>" name="search[<?= $option['name']?>]" placeholder="<?= isset($option['placeholder'] ? $option['placeholder']:'')?>" autocomplete="off" class="layui-input">
				</div>
					<?php if (count($field['option'])-1!==$key): ?>
				<div class="layui-form-mid"><?= $field['mid']?></div>	
					<?php endif ?>
				<?php endforeach ?>
			<?php elseif($field['type'] === 'casecade'): ?>
				<?= $this->element('casecade_select',$field['casecade_select'])?>
		    <?php endif ?>
			</div>  	  	
		<?php endforeach ?>
  	<?php if (count($formItem)>1): ?>
  		</div>
  	<?php endif ?>
<?php endforeach ?>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-<?= $search['filter']?>">搜索</button>
      </div>
    </div>
  </form>
</div>