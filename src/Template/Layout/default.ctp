<?php?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>527hk报价</title>
    <link rel="stylesheet" href="/vendor/layui/css/layui.css">
    <link rel="stylesheet" href="/css/common.css">
</head>

<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo">报价后台</div>
            <!-- 头部区域（可配合layui已有的水平导航） -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item"><a href="">控制台</a></li>
                <li class="layui-nav-item"><a href="">商品管理</a></li>
                <li class="layui-nav-item"><a href="">用户</a></li>
                <li class="layui-nav-item">
                    <a href="javascript:;">其它系统</a>
                    <dl class="layui-nav-child">
                        <dd><a href="">邮件管理</a></dd>
                        <dd><a href="">消息管理</a></dd>
                        <dd><a href="">授权管理</a></dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <a href="javascript:;">
          <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
          贤心
        </a>
                    <dl class="layui-nav-child">
                        <dd><a href="">基本资料</a></dd>
                        <dd><a href="">安全设置</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item"><a href="">退了</a></li>
            </ul>
        </div>
        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <?php foreach ($Navs as $Nav): ?>
                    <li class="layui-nav-item <?php if (in_array($this->request->getParam('controller'), $Nav['tabs'])): ?>layui-nav-itemed<?php endif?>">
                        <a class="" href="<?=$Nav['url']?>"><?=$Nav['name']?></a>
                        <?php if (isset($Nav['children'])): ?>
                        <dl class="layui-nav-child">
                            <?php foreach ($Nav['children'] as $ChildNav): ?>
                            <dd> <a href="<?=$ChildNav['url']?>"
              class="<?php if (in_array($this->request->getParam('controller'), $ChildNav['tabs'])): ?>side-bar__tab--current<?php endif?>"><?=$ChildNav['name']?></a></dd>
                            <?php endforeach?>
                        </dl>
                        <?php endif?>
                    </li>
                    <?php endforeach?>
                </ul>
            </div>
        </div>
        <div class="layui-body">
            <!-- 内容主体区域 -->
            <div style="padding: 15px;">
                <?=$this->fetch('content')?>
            </div>
        </div>
        <div class="layui-footer">
            <!-- 底部固定区域 -->
            © layui.com - 底部固定区域
        </div>
    </div>
    <script src="/vendor/layui/layui.js"></script>
    <script src="/js/common.js"></script>
    <script>
    //JavaScript代码区域
    layui.use(['element', 'table', 'form'], function() {
        var element = layui.element;
        var table = layui.table,
            form = layui.form,
            token = '<?=$token?>',
            order = {},
            $ = layui.$;

        //表格js
        <?php if (isset($tableParams)): ?>
            <?php foreach ($tableParams as $table): ?>
                //方法级渲染
                table.render({
                    elem: '#LAY_table-<?= $table['name']?>',
                    cellMinWidth: 80,
                    autoSort: false,
                    url: '<?=$table["renderUrl"]?>',
                    toolbar: '#toolbar-<?= $table['name']?>',
                    title: '',
                    cols: [
                        [
                            { type: 'checkbox', fixed: 'left' }
                            <?php foreach ($table["tableFields"] as $field): ?>, {
                                <?php foreach ($field as $key => $value): ?>
                                <?=$key?>: <?=$value?>,
                                <?php endforeach?>
                            }
                            <?php endforeach?>, { fixed: 'right', title: '操作', toolbar: '#bar-<?= $table['name']?>', width: 150 }
                        ]
                    ],
                    id: 'list-<?= $table['name']?>',
                    page: true
                    // ,height: 315
                });

                //头工具栏事件
                table.on('toolbar(LAY_table-<?= $table['name']?>)', function(obj) {
                    var checkStatus = table.checkStatus(obj.config.id);
                    switch (obj.event) {
                        case 'deleteData-<?= $table['name']?>':
                            var data = checkStatus.data;
                            let ids = [];
                            data.forEach((each) => ids.push(each.id))

                            if (ids.length) {
                                layer.confirm('真的删除行么', function(index) {
                                    layer.close(index);
                                    ajax($, {
                                        url: '<?=$table["deleteUrl"]?>',
                                        type: 'post',
                                        data: {
                                            ids
                                        },
                                        token,
                                        success: (res) => {
                                            if (res.data !== 2) {
                                                pageReload()
                                            }
                                        },
                                        fail: (res) => {

                                        }
                                    })
                                });
                                return false
                            } else {
                                layer.msg('未选中');
                            }
                            break;
                        <?php if (isset($table["addUrl"])): ?>
                        case 'addData-<?= $table['name']?>':
                            window.location.href = '<?=$table["addUrl"]?>';
                            break;    
                        <?php endif ?>
                        
                    };
                });

                //监听行工具事件
                table.on('tool(LAY_table-<?= $table['name']?>)', function(obj) {
                    var data = obj.data;
                    if (obj.event === 'del-<?= $table['name']?>') {
                        layer.confirm('真的删除行么', function(index) {
                            layer.close(index);
                            ajax($, {
                                url: '<?=$table["deleteUrl"]?>',
                                type: 'post',
                                data: {
                                    ids: [data.id]
                                },
                                token,
                                success: (res) => {
                                    if (res.data === 0) {
                                        obj.del();
                                    }
                                },
                                fail: (res) => {

                                }
                            })

                        });
                    }
                });

                //监听排序事件
                table.on('sort(LAY_table-<?= $table['name']?>)', function(obj) {
                    const field = obj.field
                    if (order[field]) order[field] = null
                    order[field] = obj.type
                    table.reload('list', {
                        url: '<?=$table["renderUrl"]?>',
                        initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。
                            ,
                        where: {
                            order
                        }
                    });
                });


                //监听单元格编辑
                table.on('edit(LAY_table-<?= $table['name']?>)', function(obj) {
                    var value = obj.value //得到修改后的值
                        ,
                        data = obj.data //得到所在行所有键值
                        ,
                        field = obj.field; //得到字段
                    console.log(obj)
                    ajax($, {
                        url: '<?=$table["editUrl"]?>',
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
                form.on('switch(rowSwitch-<?= $table['name']?>)', function(obj) {
                    obj.elem.disabled = true
                    ajax($, {
                        url: '<?=$table["editUrl"]?>',
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
                            }

                            obj.elem.disabled = false
                        },
                        fail: (res) => {

                        }
                    })
                });
                <?php if ($table["can_search"]): ?>
                //搜索框提交，数据重载
                form.on('submit(search-<?= $table['name']?>)', function(data) {
                    const reload = Object.values(data.field).some((item) => { return item != '' });

                    // 执行重载
                    if (reload) {
                        table.reload('list-<?= $table['name']?>', {
                            url: '<?=$table["renderUrl"]?>',
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: data.field
                        });
                    }
                    return false;
                });
                <?php endif?>
            <?php endforeach ?>
        
        <?php endif?>
        const linkSelect = [
            { 'url': '/products/apiGetCategorySelect', 'select': ['zone', 'group', 'category'], },
            { 'url': '/merchants/apiGetDistrictSelect', 'select': ['area', 'district'], },
        ];
        //联动选框
        linkSelect.forEach((selectObj) => {
            selectObj.select.forEach((type) => {
                
                form.on('select(' + type + ')', function(data) {
                    layer.msg('加载中');
                    let originIdObj = {}
                    //加载过程中，禁止点击选框
                    selectObj.select.forEach((type) => {
                        $("#" + type).prop('disabled', true)
                        originIdObj[type] = $("#" + type).val()
                    });

                    form.render('select');
                    ajax($, {
                        url: selectObj.url,
                        type: 'post',
                        data: {
                            type,
                            pid: data.value,
                            origin: originIdObj
                        },
                        token,
                        success: (res) => {

                            if (typeof(res.data) == 'number' && res.data != 0) {
                                layer.msg(res.msg);
                                pageReload()
                            } else {
                                const data = res.data
                                selectObj.select.forEach((key) => {
                                    const obj = data[key]
                                    let optionstring = "";
                                    Object.keys(obj.list).forEach((option) => {
                                        optionstring += "<option value=\"" +
                                            option +
                                            "\" " +
                                            (option == obj.selected ? "selected" : "") +
                                            ">" +
                                            obj.list[option] +
                                            "</option>";
                                    })
                                    $("#" + key).html('<option value=""></option>' + optionstring).prop('disabled', false);
                                })
                                form.render('select'); //这个很重要
                                

                            }
                        }

                    });
                });
            })
        })

    });

    </script>
    <?=$this->fetch('script')?>
</body>

</html>
