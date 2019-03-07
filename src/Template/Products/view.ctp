

<div class="layui-tab">
    <ul class="layui-tab-title">
        <li class="layui-this">产品详情</li>
        <?php if (!$product->isNew()): ?>
        <li>产品报价<span class="layui-badge"><?=$product->quoteCount?></span></li>    
        <li>产品评论列表<span class="layui-badge"><?=$product->commentCount?></span></li>    
        <?php endif ?>
        
    </ul>
    <div class="layui-tab-content">
        <!-- 产品信息 -->
        <div class="layui-tab-item layui-show product_info">
            <form class="layui-form" action="" id="productDetail" enctype="multipart/form-data">
                <?php if ($product->isNew()): ?>
                <input type="hidden" name="type" value="add">
                <?php endif ?>
                <input type="hidden" name="id" value="<?= $product->id?>">
                <input type="hidden" name="detail" value="1">
                <div class="layui-step" id="step">
                    <div class="layui-step-content layui-clear">
                        <!-- 产品信息 -->
                        <div class="layui-step-content-item product_info">
                            <div class="layui-form-item">
                                <label class="layui-form-label">产品名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required|title" autocomplete="off" placeholder="请输入产品名称" class="layui-input" value="<?=$product->name?>">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">品牌</label>
                                <div class="layui-input-block">
                                    <input type="text" name="brand" autocomplete="off" placeholder="请输入品牌名称" class="layui-input" value="<?=$product->brand?>">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">排序</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$product->sort?>">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">前台可见</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if ($product->is_visible): ?>checked
                                    <?php endif?>>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">评分</label>
                                    <div class="layui-input-block">
                                        <input type="hidden" name="rating" value="<?=$product->rating?>">
                                        <div id="rate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                              <label class="layui-form-label">分类</label>      
                              <?= $this->element('casecade_select',$product->category_select)?>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">产品描述</label>
                                <div class="layui-input-block">
                                    <textarea placeholder="请输入内容" class="layui-textarea" name="caption"><?=$product->caption?></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">新品</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="is_new" value="1" lay-skin="switch" lay-text="是|否" <?php if ($product->is_new): ?>checked
                                    <?php endif?>>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">热门</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="is_hot" value="1" lay-skin="switch" lay-text="是|否" <?php if ($product->is_hot): ?>checked
                                    <?php endif?>>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">行货价格</label>
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <input type="text" name="price_hong_min" placeholder="$" autocomplete="off" class="layui-input" value="<?=$product->price_hong_min?>">
                                    </div>
                                    <div class="layui-form-mid">-</div>
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <input type="text" name="price_hong_max" placeholder="$" autocomplete="off" class="layui-input" value="<?=$product->price_hong_max?>">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">水货价格</label>
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <input type="text" name="price_water_min" placeholder="$" autocomplete="off" class="layui-input" value="<?=$product->price_water_min?>">
                                    </div>
                                    <div class="layui-form-mid">-</div>
                                    <div class="layui-input-inline" style="width: 100px;">
                                        <input type="text" name="price_water_max" placeholder="$" autocomplete="off" class="layui-input" value="<?=$product->price_water_max?>">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">相册</label>
                                <div class="layui-input-block">
                                    <div class="layui-upload">
                                        <button type="button" class="layui-btn" id="uploadImages">多图片上传</button>
                                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                                            预览图：
                                            <div class="layui-upload-list" id="imagePrevList">
                                                <?php foreach ($product->albums as $key => $album): ?>
                                                <div class="imagePrev layui-inline originImage">
                                                    <img src="<?=$album['thumb']?>" alt="<?=$product->name?>">
                                                    <button class="layui-btn layui-btn-xs layui-btn-danger image-delete" data-name="<?= $product->album[$key][0]?>">删除</button>
                                                </div>
                                                <?php endforeach?>
                                            </div>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 产品属性 -->
                        <div class="layui-step-content-item product_attrs" id="CateAttrs">
                            <?php if (isset($cateAttrs)): ?>
                            <?php foreach ($cateAttrs as $cateAttrId => $attrName): ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">
                                    <?=$attrName?></label>
                                <div class="layui-input-block">
                                    <input type="text" name="categories_attributes[<?=$cateAttrId?>]" autocomplete="off" class="layui-input" <?php if (isset($product->attributes[$cateAttrId])): ?>value="<?=$product->attributes[$cateAttrId]?>"<?php endif?>>
                                </div>
                            </div>
                            <?php endforeach?>    
                            <?php endif ?>
                        </div>
                        <!-- 产品筛选项 -->
                        <div class="layui-step-content-item product_attr_filters">
                            <div id="CateFilters">
                                <?php if (isset($cateAttrFilterOptions)): ?>
                                <?php foreach ($cateAttrFilterOptions as $cateAttrId => $cateAttrFilter): ?>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">
                                        <?=$cateAttrs[$cateAttrId]?></label>
                                    <div class="layui-input-block">
                                        <?php foreach ($cateAttrFilter as $option): ?>
                                        <input type="checkbox" name="filter[<?=$option->option_id?>]" lay-skin="primary" title="<?=$option->option?>" value="<?=$option->option_id?>" <?php if (in_array($option->option_id, $product->filter)): ?>
                                        checked
                                        <?php endif?>>
                                        <?php endforeach?>
                                    </div>
                                </div>
                                <?php endforeach?>  
                                <?php endif ?>
                                
                            </div>
                        </div>
                    </div>
                    <div class="layui-step-btn">
                        <div class="layui-btn-group">
                            <button class="layui-btn prev">上一步</button>
                            <button class="layui-btn next">下一步</button>
                            <button class="layui-btn" lay-submit="" lay-filter="save" id="save">保存</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php if (!$product->isNew()): ?>
        <!-- 产品报价 -->
        <div class="layui-tab-item product_quotes">
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
        <!-- 产品评论列表 -->
        <div class="layui-tab-item product_comments">
            <?=$this->element('table', $tableParams['comments'])?>
        </div>     
        <?php endif ?>
        
    </div>
</div>
<?php $this->start('script')?>
<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form', 'upload', 'step', 'rate'], function() {
    var $ = layui.jquery,
        form = layui.form,
        layer = layui.layer,
        step = layui.step,
        rate = layui.rate,
        upload = layui.upload,
        token = '<?=$token?>',
        lastCategoryId = $('[name="category_id"]').val();

    //自定义验证规则
    form.verify({
        title: function(value) {
            if (value.length < 2) {
                return '标题至少得2个字符啊';
            }
        }
    });

    //取消表单默认行为
    $('#productDetail').on('submit', () => (false))

    //监听提交
    form.on('submit(save)', function(data) {
        $('#save').attr('disabled',true)
        var formData = new FormData($('#productDetail')[0]);
        var count =0
        typeof(uploadListIns.config.files)=='object' && layui.each(uploadListIns.config.files,(index,image) => {
            formData.set('albums['+count+']',image)
            count++
        })

        $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: '/products/api-save',
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
            multiple: true,
            field: 'albums',
            accept: 'images',
            acceptMime: 'image/*',
            auto: false,
            size:2048,
            choose: function(obj) {
                //将每次选择的文件追加到文件队列
                var files = this.files = obj.pushFile();
                //读取本地文件
                obj.preview(function(index, file, result) {
                    var imagePrev = $(['<div class="imagePrev layui-inline" >', '<img src="' + result + '" alt="' + file.name + '" class="layui-upload-img" width="100">', '<button class="layui-btn layui-btn-xs layui-btn-danger image-delete">删除</button>', '</div>'].join(''));

                    //删除
                    imagePrev.find('.image-delete').on('click', function() {
                        delete files[index]; //删除对应的文件
                        imagePrev.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                       
                    });

                    imagePrevList.append(imagePrev);

                });
            }
        });
    <?php if (!$product->isNew()): ?>
    //删除原始图片
    $('.image-delete').on('click', function() {
        if ($(this).data('name')) {//原始文件
            const that = this
            ajax($, {
                url: '/products/apiDeleteAlbum?pid=' + <?= $product->id?>,
                type: 'post',
                data:{
                    img:$(this).data('name')
                },
                token,
                success: (res) => {
                    if (typeof(res.data) == 'number' && res.data !== 0) {
                        layer.alert(res.msg);
                    } else {
                        c(that)
                        $(that).parent().remove();
                    }
                }
            })
        }
    });
    <?php endif ?>
    


    //步骤面板
    var step = layui.step;
    step.render({
        elem: '.layui-step',
        title: ["产品信息", "产品属性", "产品筛选项"],
        isOpenStepLevel: true
    });
    $(".prev").on("click", function() {
        step.prev();
    })
    $(".next").on("click", function() {
        if (step.currentStep == 1) { //检查产品信息是否填完

            if ($('[name="name"]').val().length < 2) {
                layer.alert('标题至少得2个字符');
                return false;
            }
            const category_id = $('[name="category_id"]').val()

            if (!category_id) {
                layer.alert('分类未选');
                return false;
            } else if (!lastCategoryId || lastCategoryId !== category_id) {
                // 获取分类属性
                ajax($, {
                    url: '/products/apiGetCategoryAttr?cid=' + category_id,
                    type: 'get',
                    token,
                    success: (res) => {
                        if (typeof(res.data) == 'number' && res.data !== 0) {
                            layer.alert(res.msg);
                            pageReload()
                        } else {

                            let cateAttrStr = cateFilterStr = ''
                            // 属性值
                            const cateAttrs = res.data.cateAttrs
                            layui.each(cateAttrs, (cate_attr_id, cate_attr_name) => {
                                cateAttrStr +=
                                    '<div class="layui-form-item">' +
                                    '<label class="layui-form-label">' + cate_attr_name + '</label>' +
                                    '<div class="layui-input-block">' +
                                    '<input type="text" autocomplete="off" class="layui-input" name="categories_attributes[' + cate_attr_id + ']">' +
                                    '</div>' +
                                    '</div>'
                            });

                            $('#CateAttrs').html(cateAttrStr)
                            //筛选项
                            const cateAttrFilterOptions = res.data.cateAttrFilterOptions
                            layui.each(cateAttrFilterOptions, (cate_attr_id, filterOptions) => {
                                // 筛选复选框
                                let optionCheckboxStr = '';
                                layui.each(filterOptions, (key, option) => {

                                    optionCheckboxStr += '<input type="checkbox" name="filter[' + option.option_id + ']" lay-skin="primary" title="' + option.option + '" value="' + option.option_id + '">'
                                })
                                // 筛选项
                                cateFilterStr +=
                                    '<div class="layui-form-item">' +
                                    '<label class="layui-form-label">' + cateAttrs[cate_attr_id] + '</label>' +
                                    '<div class="layui-input-block">' +
                                    optionCheckboxStr +

                                    '</div>' +
                                    '</div>'
                            })
                            $('#CateFilters').html(cateFilterStr)
                            form.render('checkbox');
                            lastCategoryId = category_id

                        }
                    }
                })
            }
        }
        step.next();
    })
    //评分
    rate.render({
        elem: '#rate',
        half: true, //开启半星
        setText: function(value) { //自定义文本的回调
            $('[name="rating"]').val(value)
        }
    })
});

</script>
<?php $this->end('script')?>
