<?php?>
<div class="layui-tab">
  <ul class="layui-tab-title">
    <li<?php if (!isset($active) || !$active): ?> class="layui-this"<?php endif ?>>分组详情</li>
    <?php if (!$group->isNew()): ?> 
    <li<?php if (isset($active) && $active=='categories'): ?> class="layui-this"<?php endif ?>>分组分类<span class="layui-badge"><?=$group->categoryCount?></span></li>
    <li<?php if (isset($active) && $active=='products'): ?> class="layui-this"<?php endif ?>>分组产品<span class="layui-badge"><?=$group->productCount?></span></li>    
    <?php endif ?>
  </ul>
  <div class="layui-tab-content">
    <!-- 分组详情 -->
    <div class="layui-tab-item group_info<?php if (!isset($active) || !$active): ?> layui-show<?php endif ?>">
        <form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
            <?php if ($group->isNew()): ?>
            <input type="hidden" name="type" value="add">
            <?php endif ?>
            <input type="hidden" name="detail" value="1">
            <input type="hidden" name="id" value="<?= $group->id?>">   
            <div class="layui-step-content-item group_info">
                
                <div class="layui-form-item">
                    <label class="layui-form-label">空间名称</label>

                    <div class="layui-input-block">
                        <?= $this->element('casecade_select',$group->category_select)?>                        
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">分组名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" autocomplete="off" placeholder="请输入空间名称" class="layui-input" value="<?= $group->name?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">前台可见</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($group->is_visible===false)): ?>checked
                        <?php endif?>>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$group->sort?>">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                      <button class="layui-btn" lay-submit="" lay-filter="save" id="save">保存</button>
                    </div>
                  </div>
                
            </div>
        </form>
    </div>
<?php if (!$group->isNew()): ?>
    <!-- 分组分类 -->
    <div class="layui-tab-item group_category<?php if (isset($active) && $active=='categories'): ?> layui-show<?php endif ?>"">
        <?= $this->element('category_search',['category_select' => $searchTpl['category']['category_select']])?>
        <?= $this->element('table',$tableParams['categories'])?>
    </div>
    <!-- 分组产品 -->
    <div class="layui-tab-item group_product<?php if (isset($active) && $active=='products'): ?> layui-show<?php endif ?>"">
        <?= $this->element('product_search',['category_select' => $searchTpl['product']['category_select']])?>
        <?= $this->element('table',$tableParams['products'])?>
    </div>
<?php endif ?>
  </div>
</div>

<?php $this->start('script')?>
<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form', 'autocomplete'], function() {
    var $ = layui.jquery,
        form = layui.form,
        layer = layui.layer,
        token = '<?=$token?>';    
    //监听提交
    form.on('submit(save)', function(data) { 
        $('#save').attr('disabled',true) 
        ajax($,{
            token,
            url: '/groups/api-save',
            type: 'post',
            data: data.field,
            success: (res) => { 
                //若出现错误或者保存完成，重载页面
                if(res.code || res.data === 0){                    
                    pageReload()
                }else{
                    $('#save').attr('disabled',false)
                }
            },
            fail:() =>{
            }

        })

        return false
    });
    
    
    
});

</script>
<?php $this->end('script')?>