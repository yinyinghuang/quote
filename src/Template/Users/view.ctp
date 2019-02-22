<?php?>
<form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
    <?php if ($user->isNew()): ?>
    <input type="hidden" name="type" value="add">
    <?php endif ?>
    <input type="hidden" name="detail" value="1">
    <input type="hidden" name="id" value="<?= $user->id?>">   
    <div class="layui-step-content-item user_info">
        <div class="layui-form-item">
            <label class="layui-form-label">管理员名称</label>
            <div class="layui-input-block">
                <input type="text" name="username" autocomplete="off" class="layui-input" value="<?= $user->username?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="text" name="password" autocomplete="off" class="layui-input" value="<?= $user->password?>">
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
        // $('#save').attr('disabled',true) 
        ajax($,{
            token,
            url: '/users/api-save',
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