
<form class="layui-form" action="" id="quoteDetail" enctype="multipart/form-data">
    <?php if ($quote->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif ?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?= $quote->id?>">    
    <input type="hidden" name="product_id" value="<?= $quote->product_id?>" id="product_id">    
    <input type="hidden" name="merchant_id" value="<?= $quote->merchant_id?>" id="merchant_id">    
    <div class="layui-step-content-item quote_info">
        <div class="layui-form-item">
            <label class="layui-form-label">产品名称</label>
            <div class="layui-input-block">
                <input type="text" id="product_name" autocomplete="off" placeholder="请输入产品名称" class="layui-input" value="<?=$quote->product_name?>" <?php if ($quote->product_id): ?>disabled<?php endif ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商户名称</label>
            <div class="layui-input-block">
                <input type="text" id="merchant_name" autocomplete="off" placeholder="请输入商户名称" class="layui-input" value="<?=$quote->merchant_name?>" <?php if ($quote->merchant_id): ?>disabled<?php endif ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">前台可见</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($quote->is_visible===false)): ?>checked
                <?php endif?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$quote->sort?>">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">行货价格</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="price_hong" placeholder="$" autocomplete="off" class="layui-input" value="<?=$quote->price_hong?>">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">水货价格</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="price_water" placeholder="$" autocomplete="off" class="layui-input" value="<?=$quote->price_water?>">
                </div>
            </div>
        </div>
        <div class="layui-form-item">            
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" id="remark" name="remark">  
                  <?=$quote->remark?>
                </textarea>
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
}).use(['form', 'layedit','autocomplete'], function() {
    var $ = layui.jquery,
        form = layui.form,
        layer = layui.layer,
        autocomplete = layui.autocomplete,
        layedit = layui.layedit,
        token = '<?=$token?>';

    var remark = layedit.build('remark');
    //监听提交
    form.on('submit(save)', function(data) { 
        $('#save').attr('disabled',true) 
        ajax($,{
            token,
            url: '/quotes/api-save',
            type: 'post',
            data: Object.assign(data.field,{remark:layedit.getContent(remark)}),
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