<?php?>
<div class="layui-tab">
  <ul class="layui-tab-title">
    <li<?php if (!isset($active) || !$active): ?> class="layui-this"<?php endif ?>>地区详情</li>
  </ul>
  <div class="layui-tab-content">
    <!-- 地区详情 -->
    <div class="layui-tab-item district_info<?php if (!isset($active) || !$active): ?> layui-show<?php endif ?>">
        <form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
            <?php if ($district->isNew()): ?>
            <input type="hidden" name="type" value="add">
            <?php endif ?>
            <input type="hidden" name="detail" value="1">
            <input type="hidden" name="id" value="<?= $district->id?>">   
            <div class="layui-step-content-item district_info">

                <div class="layui-form-item">
                    <label class="layui-form-label">地区</label>
                    <div class="layui-input-block">
                        <?= $this->element('casecade_select',$district->district_select)?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">地区名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" autocomplete="off" placeholder="请输入空间名称" class="layui-input" value="<?= $district->name?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">前台可见</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($district->is_visible===false)): ?>checked
                        <?php endif?>>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$district->sort?>">
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
            url: '/districts/api-save',
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