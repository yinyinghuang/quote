<form class="layui-form" action="" id="form">
  <div class="layui-form-item">
    <label class="layui-form-label">评论需审核</label>
    <div class="layui-input-block">
      <input type="checkbox" name="comment_need_check" lay-skin="switch" lay-text="是|否" <?php if ($comment_need_check): ?>checked<?php endif ?>>
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit="" lay-filter="demo1">保存</button>
    </div>
  </div>
</form>
<?php $this->start('script')?>
<script>
  layui.config({
      base: "/vendor/layui/lay/modules/"
  }).use(['form'], function() {
    var form = layui.form,
      layer = layui.layer,
      $ = layui.jquery,
      token = '<?=$token?>';
    //取消表单默认行为
    $('#form').on('submit', () => (false))
    //监听提交
    form.on('submit(demo1)', function(data){
      var comment_need_check = data.field.comment_need_check?1:0;
      $.ajax({
        headers: {
                'X-CSRF-Token': token
            },
        url:'/configs/api-save',
        type:'post',
        data:{
          comment_need_check
        },
        success:function(res){
          var msg = res.data?res.msg : '保存成功';
          layer.msg(msg)

        }
      })
      return false;
    });
  });
</script>
<?php $this->end('script')?>