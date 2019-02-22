<?php?>
<form class="layui-form" action="" id="detail" enctype="multipart/form-data">
    <?php if ($brand->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?=$brand->id?>">
    <input type="hidden" name="category_id" value="<?=$brand->category->id?>">
    <div class="layui-step-content-item brand_info">
        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-block">
                <?=$this->element('casecade_select', $brand->category_select)?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">品牌</label>
            <div class="layui-input-block">
                <input type="text" name="brand" autocomplete="off" class="layui-input" id="brand"<?php if (!$brand->isNew()): ?> value="<?=$brand->brand?>" disabled<?php endif ?> lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">前台可见</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($brand->is_visible === false)): ?>checked
                <?php endif?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$brand->sort?>">
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

<?php $this->start('script')?>
<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form', 'autocomplete', 'table'], function() {
    var $ = layui.jquery,
        form = layui.form,
        table = layui.table,
        layer = layui.layer,
        token = '<?=$token?>';
    $('#detail').on('submit',() => false)
    
    //监听提交
    form.on('submit(save)', function(data) {
        // $('#save').attr('disabled',true)
        ajax($,{
            token,
            url: '/categories-brands/api-save',
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