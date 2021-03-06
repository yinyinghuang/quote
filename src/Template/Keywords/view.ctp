
<form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
    <?php if ($keyword->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif ?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?= $keyword->id?>">   
    <div class="layui-step-content-item keyword_info">
        <div class="layui-form-item">
            <label class="layui-form-label">关键词</label>
            <div class="layui-input-block">
                <input type="text" name="name" autocomplete="off" class="layui-input" value="<?= $keyword->name?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">次数</label>
            <div class="layui-input-block">
                <input type="number" name="count" autocomplete="off" class="layui-input" value="<?= $keyword->count?:0?>">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">前台可见</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($keyword->is_visible===false)): ?>checked
                <?php endif?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="number" name="sort" autocomplete="off" class="layui-input" value="<?=$keyword->sort?:0?>">
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
            url: '/keywords/api-save',
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