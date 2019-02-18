<?php?>
<form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
    <?php if ($attribute->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif ?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?= $attribute->id?>">   
    <div class="layui-step-content-item attribute_info">
        <div class="layui-form-item">
            <label class="layui-form-label">空间名称</label>
            <div class="layui-input-block">
                <?= $this->element('casecade_select',$attribute->category_select)?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">属性</label>
            <div class="layui-input-block">
                <input type="hidden" name="attribute_id" id="attribute_id">
                <input type="text" autocomplete="off" placeholder="请输入空间名称" class="layui-input" value="<?= $attribute->attribute->name?>" id="attribute_name">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">前台可见</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($attribute->is_visible===false)): ?>checked
                <?php endif?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item category_attribute_filter">
            <div class="layui-inline">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-block">
                    <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$attribute->sort?>">
                </div>
            </div>
        </div>    
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">筛选项</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_filter" value="1" lay-skin="switch" lay-text="是|否" lay-filter="is_filter"<?php if (($attribute->is_filter===true)): ?> checked<?php endif?>>
                    <button class="layui-btn layui-btn-sm layui-btn-normal category_attribute_filter" id="addFiter">增加筛选选项</button>
                </div>
            </div>
        </div>         
        <hr>
        <div class="category_attribute_filter <?php if (!($attribute->is_filter)): ?>layui-hide<?php endif ?>">
            <div class="layui-form-item">
                <label class="layui-form-label">筛选项类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="filter_type" value="1" title="单选" <?php if ($attribute->filter_type===1): ?> checked<?php endif ?>>
                    <input type="radio" name="filter_type" value="2" title="多选" <?php if ($attribute->filter_type===2): ?> checked<?php endif ?>>
                </div>
            </div>      
            <div class="layui-form-item">
                <div class="layui-block">
                    <label class="layui-form-label">筛选选项</label>
                    <div class="layui-input-block">
                        <div class="attribute_filter">
                            <?= $this->element('table',$tableParams['filters'])?>
                        </div>
                    </div>
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
        c(table) 
    //监听提交
    form.on('submit(save)', function(data) { 
        // $('#save').attr('disabled',true) 
        ajax($,{
            token,
            url: '/categories-attributes/api-save',
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
    form.on('switch(is_filter)', function(obj) {
        obj.elem.checked?$('.category_attribute_filter').removeClass('layui-hide'):$('.category_attribute_filter').addClass('layui-hide')
    });
    //头工具栏事件
    table.on('toolbar(LAY_table-category-attribute-fiters)', function(obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        switch (obj.event) {
            case 'addData-category-attribute-filters':
                layer.alert('sssssss')
                break;
        };
    });

    $('#addFiter').on('click',function(){
        table.reload('list-category-attribute-fiters', {
            url: '/category-attribute-filters/api-list',
            page: {
                curr: 1 //重新从第 1 页开始
            },
            where: data.field
        });
    })
});

</script>
<?php $this->end('script')?>