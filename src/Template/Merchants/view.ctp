

<div class="layui-tab">
    <ul class="layui-tab-title">
        <li<?php if (!isset($active) || !$active): ?> class="layui-this"<?php endif ?>>商户详情</li>
        <?php if (!$merchant->isNew()): ?>
        <li<?php if (isset($active) && $active=='quotes'): ?> class="layui-this"<?php endif ?>>商户报价<span class="layui-badge"><?=$merchant->quoteCount?></span></li>   
        <?php endif ?>
        
    </ul>
    <div class="layui-tab-content">
        <!-- 商户信息 -->
        <div class="layui-tab-item merchant_info<?php if (!isset($active) || !$active): ?> layui-show<?php endif ?>">
            <form class="layui-form" action="" id="merchantDetail" enctype="multipart/form-data">
                <?php if ($merchant->isNew()): ?>
                <input type="hidden" name="type" value="add">
                <?php endif ?>
                <input type="hidden" name="id" value="<?= $merchant->id?>">
                <input type="hidden" name="detail" value="1">
                <div class="merchant_info">
                    <div class="layui-form-item">
                        <label class="layui-form-label">商户名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required|title" autocomplete="off" placeholder="请输入商户名称" class="layui-input" value="<?=$merchant->name?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">网址</label>
                        <div class="layui-input-block">
                            <input type="text" name="website" autocomplete="off" placeholder="请输入网址" class="layui-input" value="<?=$merchant->website?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">简介</label>
                        <div class="layui-input-block">
                            <input type="text" name="intro" autocomplete="off" placeholder="请输入简介" class="layui-input" value="<?=$merchant->intro?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input type="email" name="email" autocomplete="off" placeholder="请输入邮箱" class="layui-input" value="<?=$merchant->email?>" lay-verify="email">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">微信</label>
                        <div class="layui-input-block">
                            <input type="text" name="wechat" autocomplete="off" placeholder="请输入微信" class="layui-input" value="<?=$merchant->wechat?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-block">
                                <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$merchant->sort?>">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">前台可见</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if ($merchant->is_visible): ?>checked
                            <?php endif?>>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">图标</label>
                        <div class="layui-input-block">
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="uploadImages">上传图片</button>
                                <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="imagePrev" src="<?=$merchant->logos['thumb']?>" alt="<?=$merchant->name?>" style="width: 100px;">                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
                        <legend>商户位置</legend>
                    </fieldset>
                    <div class="">
                        <div class="layui-form-item">
                            <div class="layui-block">
                                <label class="layui-form-label">商户位置</label>
                                <div class="layui-input-block">
                                    <div class="attribute_filter">
                                        <?=$this->element('table', $locationTableParams)?>
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
        </div>
        <?php if (!$merchant->isNew()): ?>
        <!-- 商户报价 -->
        <div class="layui-tab-item merchant_quotes<?php if (isset($active) && $active=='quotes'): ?> layui-show<?php endif ?>">
            <form class="search-box layui-form">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">商户ID</label>
                        <div class="layui-input-block">
                            <input type="number" name="search[merchant_id]" placeholder="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">商户名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="search[merchant_name]" placeholder="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">行货价格</label>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" name="search[price_hong_min]" placeholder="$" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" name="search[price_hong_max]" placeholder="$" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">水货价格</label>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" name="search[price_water_min]" placeholder="$" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" name="search[price_water_max]" placeholder="$" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                  <label class="layui-form-label">地区</label>      
                  <?= $this->element('casecade_select',$district_select)?>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">可见</label>
                    <div class="layui-input-block">
                        <input type="radio" name="search[is_visible]" value="1" title="是">
                        <input type="radio" name="search[is_visible]" value="0" title="否">
                        <input type="radio" name="search[is_visible]" value="2" title="不限">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-quotes">搜索</button>
                    </div>
                </div>
            </form>
            <?=$this->element('table', $tableParams['quotes'])?>
        </div> 
        <?php endif ?>
        
    </div>
</div>
<?php $this->start('script')?>
<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form', 'upload', 'table'], function() {
    var $ = layui.jquery,
        form = layui.form,
        table = layui.table,
        layer = layui.layer,
        upload = layui.upload,
        token = '<?=$token?>';
    merchantLocationFormHtml = function (data){
        html = '<form id="merchant_location" style="padding:20px 10px;" class="layui-form">'
                    +'<div class="layui-form-item">'
                        +'<label class="layui-form-label">地区</label>'
                        +'<div class="layui-input-block">'
                            +'<select class="area" lay-filter="area" name="area_id" lay-verify="reuqired">'
                            +'<option value="">请选择区</option>';
                layui.each({<?php foreach ($areas as $area_id => $area_name): ?>
                    "<?= $area_id?>":"<?= $area_name?>",
                <?php endforeach ?>},function(area_id,area_name) {
                    html += '<option value="'+area_id+'"'+(area_id===data.area_id?" selected":"")+'>'+area_name+'</option>';
                })
                html +='</select>'
                      +'<select class="district" lay-filter="district" name="district_id" lay-verify="reuqired">' 
                        +'<option value="">请选择区</option>';
                layui.each({<?php foreach ($districts as $district_id => $district_name): ?>
                    "<?= $district_id?>":"<?= $district_name?>",
                <?php endforeach ?>},function(district_id,district_name) {
                        html += '<option value="'+district_id+'"'+(district_id===data.district_id?" selected":"")+'>'+district_name+'</option>';
                })
                    html +='</select>'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item">'
                        +'<label class="layui-form-label">地址</label>'
                        +'<div class="layui-input-block">'
                            +'<input type="text" name="address" autocomplete="off" placeholder="" class="layui-input" value="'+(data.address? data.address :'')+'" id="address" lay-verify="title">'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item">'
                        +'<label class="layui-form-label">经纬度(英文逗号隔开，纬度在前http://www.gpsspg.com/maps.htm)</label>'
                        +'<div class="layui-input-block">'
                            +'<input type="text" name="location" autocomplete="off" placeholder="" class="layui-input" value="'+(data.location? data.location :'')+'" id="location" lay-verify="location">'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item">'
                        +'<label class="layui-form-label">营业时间</label>'
                        +'<div class="layui-input-block">'
                            +'<input type="text" name="openhour" autocomplete="off" placeholder="" class="layui-input" value="'+(data.openhour? data.openhour :'')+'" id="openhour">'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item">'
                        +'<label class="layui-form-label">联系电话</label>'
                        +'<div class="layui-input-block">'
                            +'<input type="text" name="contact" autocomplete="off" placeholder="" class="layui-input" value="'+(data.contact? data.contact :'')+'" id="contact">'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item">'
                        +'<div class="layui-inline">'
                            +'<label class="layui-form-label">前台可见</label>'
                            +'<div class="layui-input-block">'
                                +'<input type="checkbox" name="is_visible" value="'+(data.is_filter? 1 :0)+'" lay-skin="switch" lay-text="是|否" checked>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                    +'<div class="layui-form-item merchant_location">'
                        +'<div class="layui-inline">'
                            +'<label class="layui-form-label">排序</label>'
                            +'<div class="layui-input-block">'
                                +'<input type="text" name="sort" autocomplete="off" class="layui-input" value="'+(data.sort? data.sort :0)+'">'
                            +'</div>'
                        +'</div>'
                    +'</div>  '
                +'</form>'
            return html;
    } 
    merchantLocationView = function(data){
        layer.open({
            type: 1,
            area: '640px'
            ,offset: 'auto' //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
            ,id: 'layerDemoauto' //防止重复弹出
            ,content: merchantLocationFormHtml(data),
            success:function(){
                form.render('select');
                form.render('checkbox');
            }
            ,btn: ['保存', '取消'] //只是为了演示
            ,yes: function(){
               var form = $('#merchant_location')[0];
               var key = 'list-merchant-locations'
               var tableData = table.cache[key]
               if(form.address.value.trim('') && form.location.value.split(',').length>1 && form.area_id.value && form.district_id.value){
                var areaInputs = $('#merchant_location .layui-select-title .layui-unselect');
                var data = {
                     id:tableData.length,
                     address:form.address.value,
                     area_id:form.area_id.value,
                     area_name:areaInputs[0].value,
                     district_id:form.district_id.value,
                     district_name:areaInputs[1].value,
                     location:form.location.value,
                     openhour:form.openhour.value,
                     contact:form.contact.value,
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
                layer.msg('内容填写有误')
               }
            }
            ,btn2: function(){
              layer.closeAll();
            }
            ,btnAlign: 'c' //按钮居中
            ,shade: 0 //不显示遮罩
        });
    }
    //自定义验证规则
    form.verify({
        title: function(value) {
            if (value.length < 2) {
                return '标题至少得2个字符啊';
            }
        },
        location: function(value) {
            return /[0-9\.]+,[0-9\.]+/i.test(value)
        },
    });

    //取消表单默认行为
    $('#merchantDetail').on('submit', () => (false))

    //监听提交
    form.on('submit(save)', function(data) {
        // $('#save').attr('disabled',true)
        var formData = new FormData($('#merchantDetail')[0]);
        var count =0
        typeof(uploadListIns.config.files)=='object' && formData.set('logoImage',uploadListIns.config.files)
        locationData = table.cache['list-merchant-locations'].filter((row) => row.is_new)
        locationData.length && formData.set('location',locationData)

        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: '/merchants/api-save',
            type: 'post',
            data: formData,
            contentType: false,
            mimeType: 'multipart/form-data',
            processData: false,
            success: (res) => {
                
                res = JSON.parse(res)
                layer.msg(res.msg);
                //若出现错误或者保存完成，重载页面
                if(res.code || res.data === 0){                    
                    pageReload()
                }else{
                    $('#save').attr('disabled',false)
                }
                
            },
            fail:() =>{
                layer.closeAll()
                layer.msg('ajaxfail系统出错，页面重载');
                pageReload()
            }

        })

        return false
    });

    var imagePrevList = $('#imagePrevList'),
        uploadListIns = upload.render({
            elem: '#uploadImages',
            field: 'logoImage',
            auto: false,
            accept: 'images',
            acceptMime: 'image/*',
            size:2048,
            choose: function(obj){
              //预读本地文件示例，不支持ie8
              obj.preview(function(index, file, result){
                $('#imagePrev').attr('src', result); 
                uploadListIns.config.files = file;
              });
            },
        });
    //方法级渲染
    table.render({
        elem: '#LAY_table-merchant-locations',
        cellMinWidth: 80,
        autoSort: false,
        url: '<?=$locationTableParams["renderUrl"]?>',
        toolbar: '#toolbar-merchant-locations',
        title: '',
        cols: [
            [
                { type: 'checkbox', fixed: 'left' }
                <?php foreach ($locationTableParams['tableFields'] as $field): ?>, {
                    <?php foreach ($field as $key => $value): ?>
                    <?=$key?>: <?=$value?>,
                    <?php endforeach?>
                }
                <?php endforeach?>, { fixed: 'right', title: '操作', toolbar: '#bar-merchant-locations', width: 150 }
            ]
        ],
        id: 'list-merchant-locations',
        page: false,
        limit:100,
        initSort:{
            field:'sort',
            type:'desc'
        }
        // ,height: 315
    });

    //头工具栏事件
    table.on('toolbar(LAY_table-merchant-locations)', function(obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        switch (obj.event) {
            case 'deleteData-merchant-locations':
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
                        var key = 'list-merchant-locations'
                        var tableData = table.cache[key]
                        origin_ids.length && ajax($, {
                            url: '<?=$locationTableParams["deleteUrl"]?>',
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
            case 'addData-merchant-locations':
                merchantLocationView({})
                break;

        };
    });

    //监听行工具事件
    table.on('tool(LAY_table-merchant-locations)', function(obj) {
        var data = obj.data;
        if (obj.event === 'del-merchant-locations') {
            layer.confirm('真的删除行么', function(index) {
                layer.close(index);
                if (data.is_new) {
                    obj.del();
                }else{
                    ajax($, {
                        url: '<?=$locationTableParams["deleteUrl"]?>',
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
        }else if(obj.event === 'view-merchant-locations'){
            merchantLocationView(data)
        }
    });

    //监听排序事件
    table.on('sort(LAY_table-merchant-locations)', function(obj) {
        table.reload('list-merchant-locations', {
            url: null,
            initSort: obj ,//记录初始排序，如果不设的话，将无法标记表头的排序状态。
        });
    });

    //监听单元格编辑
    table.on('edit(LAY_table-merchant-locations)', function(obj) {
        var value = obj.value, //得到修改后的值
            data = obj.data, //得到所在行所有键值
            field = obj.field; //得到字段

        !data.is_new && ajax($, {
            url: '<?=$locationTableParams["editUrl"]?>',
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
    form.on('switch(rowSwitch-merchant-locations)', function(obj) {
        var row = table.cache['list-merchant-locations'].filter((row) => row.id==this.value)[0]
        !row.is_new ? ajax($, {
            url: '<?=$locationTableParams["editUrl"]?>',
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
