<?php?>
<form class="layui-form" action="" id="detail" enctype="multipart/form-data">
    <?php if ($attribute->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?=$attribute->id?>">
    <div class="layui-step-content-item attribute_info">
        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-block">
                <?=$this->element('casecade_select', $attribute->category_select)?>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">属性</label>
            <div class="layui-input-block">
                <input type="hidden" name="attribute_id" id="attribute_id" <?php if (!$attribute->isNew()): ?> value="<?=$attribute->attribute_id?>"disabled<?php endif ?>>
                <input type="text" autocomplete="off" class="layui-input" id="attribute_name"<?php if (!$attribute->isNew()): ?> value="<?=$attribute->attribute->name?>" disabled<?php endif ?>>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">前台可见</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($attribute->is_visible === false)): ?>checked
                <?php endif?>>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
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
                    <input type="checkbox" name="is_filter" value="1" lay-skin="switch" lay-text="是|否" lay-filter="is_filter"<?php if (($attribute->is_filter === true)): ?> checked<?php endif?>>
                </div>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title category_attribute_filter <?php if (!($attribute->is_filter)): ?>layui-hide<?php endif?>" style="margin-top: 50px;">
            <legend>筛选项详情</legend>
        </fieldset>
        <div class="category_attribute_filter <?php if (!($attribute->is_filter)): ?>layui-hide<?php endif?>">
            <div class="layui-form-item">
                <label class="layui-form-label">筛选项类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="filter_type" value="1" title="单选" <?php if ($attribute->filter_type === 1): ?> checked<?php endif?>>
                    <input type="radio" name="filter_type" value="2" title="多选" <?php if ($attribute->filter_type === 2): ?> checked<?php endif?>>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-block">
                    <label class="layui-form-label">筛选选项</label>
                    <div class="layui-input-block">
                        <div class="attribute_filter">
                            <?=$this->element('table', $filterTableParams)?>
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
    $('#detail').on('submit',() => false)
    
    //监听提交
    form.on('submit(save)', function(data) {
        // $('#save').attr('disabled',true)
        filterData = table.cache['list-category-attribute-filters'].filter((row) => row.is_new)
        var postData = Object.assign({},data.field,{'filters':filterData})

        ajax($,{
            token,
            url: '/categories-attributes/api-save',
            type: 'post',
            data: postData,
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
    //方法级渲染
    table.render({
        elem: '#LAY_table-category-attribute-filters',
        cellMinWidth: 80,
        autoSort: false,
        url: '<?=$filterTableParams["renderUrl"]?>',
        toolbar: '#toolbar-category-attribute-filters',
        title: '',
        cols: [
            [
                { type: 'checkbox', fixed: 'left' }
                <?php foreach ($filterTableParams['tableFields'] as $field): ?>, {
                    <?php foreach ($field as $key => $value): ?>
                    <?=$key?>: <?=$value?>,
                    <?php endforeach?>
                }
                <?php endforeach?>, { fixed: 'right', title: '操作', toolbar: '#bar-category-attribute-filters', width: 150 }
            ]
        ],
        id: 'list-category-attribute-filters',
        page: false,
        limit:100,
        initSort:{
            field:'sort',
            type:'desc'
        }
        // ,height: 315
    });

    //头工具栏事件
    table.on('toolbar(LAY_table-category-attribute-filters)', function(obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        switch (obj.event) {
            case 'deleteData-category-attribute-filters':
                var data = checkStatus.data;
                let origin_ids =[]; 
                let new_ids =[]; 
                let ids = [];
                data.forEach((each) => {
                    each.is_new ? new_ids.push(each.id):origin_ids.push(each.id);
                    ids.push(each.id)

                })
                if (ids.length) {
                    layer.confirm('真的删除行么', function(index) {
                        layer.close(index);
                        var key = 'list-category-attribute-filters'
                        var tableData = table.cache[key]
                        origin_ids.length && ajax($, {
                            url: '<?=$filterTableParams["deleteUrl"]?>',
                            type: 'post',
                            data: {
                                ids
                            },
                            token,
                            success:(res) => {
                                if(res.code){
                                    pageReload()
                                }else{
                                    if(res.data.code===0){
                                        tableData = tableData.filter((row) => {
                                            return !res.data.ids.some((id) => id==row.id)
                                        })
                                        table.reload(key,{
                                            data:tableData,
                                            url:null
                                        })
                                    }
                                    layer.msg(res.msg)
                                }
                            }
                        })
                        tableData = tableData.filter((row) => {
                            return !new_ids.some((id) => id==row.id)
                        })
                        table.reload(key,{
                            data:tableData,
                            url:null
                        })                       
                        
                    });
                    return false
                }
                break;
            case 'addData-category-attribute-filters':
                layer.open({
                    type: 1
                    ,offset: 'auto' //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
                    ,id: 'layerDemoauto' //防止重复弹出
                    ,content: '<form id="category_attribute_filter" style="padding:20px 10px;">'
                        +'<div class="layui-form-item">'
                            +'<label class="layui-form-label">筛选项值</label>'
                            +'<div class="layui-input-block">'
                                +'<input type="text" name="filter" autocomplete="off" placeholder="请输入筛选项值" class="layui-input" value="" id="filter">'
                            +'</div>'
                       +' </div>'
                        +'<div class="layui-form-item">'
                            +'<div class="layui-inline">'
                                +'<label class="layui-form-label">前台可见</label>'
                                +'<div class="layui-input-block">'
                                    +'<input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" checked>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                        +'<div class="layui-form-item category_attribute_filter">'
                            +'<div class="layui-inline">'
                                +'<label class="layui-form-label">排序</label>'
                                +'<div class="layui-input-block">'
                                    +'<input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$attribute->sort?>">'
                                +'</div>'
                            +'</div>'
                        +'</div>  '
                    +'</form>'
                    ,btn: ['保存', '取消'] //只是为了演示
                    ,yes: function(){
                       var form = $('#category_attribute_filter')[0];
                       var key = 'list-category-attribute-filters'
                       var tableData = table.cache[key]
                       if(form.filter.value.trim('')){
                        var data = {
                             id:tableData.length,
                             filter:form.filter.value,
                             is_visible:form.is_visible.checked,
                             sort:form.sort.value,
                             LAY_TABLE_INDEX: tableData.length,
                             is_new:true
                        }
                        tableData.unshift(data);

                        table.reload(key,{
                             data:tableData,
                             url:null
                        })
                        layer.closeAll();
                       }else{
                        layer.msg('请填写筛选项')
                       }
                    }
                    ,btn2: function(){
                      layer.closeAll();
                    }
                    ,btnAlign: 'c' //按钮居中
                    ,shade: 0 //不显示遮罩
                  });
                break;

        };
    });

    //监听行工具事件
    table.on('tool(LAY_table-category-attribute-filters)', function(obj) {
        var data = obj.data;
        if (obj.event === 'del-category-attribute-filters') {
            layer.confirm('真的删除行么', function(index) {
                layer.close(index);
                if (data.is_new) {
                    obj.del();
                }else{
                    ajax($, {
                        url: '<?=$filterTableParams["deleteUrl"]?>',
                        type: 'post',
                        data: {
                            ids: [data.id]
                        },
                        token,
                        success: (res) => {
                            if (res.data.code === 0) {
                                obj.del();
                            }
                        },
                        fail: (res) => {

                        }
                    })
                }

            });
        }
    });

    //监听排序事件
    table.on('sort(LAY_table-category-attribute-filters)', function(obj) {
        table.reload('list-category-attribute-filters', {
            url: null,
            initSort: obj ,//记录初始排序，如果不设的话，将无法标记表头的排序状态。
        });
    });


    //监听单元格编辑
    table.on('edit(LAY_table-category-attribute-filters)', function(obj) {
        var value = obj.value, //得到修改后的值
            data = obj.data, //得到所在行所有键值
            field = obj.field; //得到字段

        !data.is_new && ajax($, {
            url: '<?=$filterTableParams["editUrl"]?>',
            type: 'post',
            data: {
                id: data.id,
                [obj.field]: obj.value
            },
            token,
            success: (res) => {
                //不成功，重载页面
                if (res.data !== 0) {
                    pageReload()
                }
            },
            fail: (res) => {

            }
        })
    });

    //监听switch操作
    form.on('switch(rowSwitch-category-attribute-filters)', function(obj) {
        var row = table.cache['list-category-attribute-filters'].filter((row) => row.id==this.value)[0]
        !row.is_new ? ajax($, {
            url: '<?=$filterTableParams["editUrl"]?>',
            type: 'post',
            data: {
                id: this.value,
                [this.name]: obj.elem.checked ? 1 : 0
            },
            token,
            success: (res) => {
                //不成功，将状态重置
                if (res.data !== 0) {
                    obj.elem.checked = !obj.elem.checked
                    $(obj.othis)[obj.elem.checked ? 'addClass' : 'removeClass']('layui-form-onswitch')
                        .html(obj.elem.checked ? '<em>是</em><i></i>' : '<em>否</em><i></i>')
                }else{
                    row.is_visible = !row.is_visible
                }
                obj.elem.disabled = false
            },
            fail: (res) => {

            }
        }):row.is_visible = !row.is_visible
    });
});

</script>
<?php $this->end('script')?>