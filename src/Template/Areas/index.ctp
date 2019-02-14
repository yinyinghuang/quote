<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area[]|\Cake\Collection\CollectionInterface $areas
 */
?>
<div class="demoTable">
  搜索ID：
  <div class="layui-inline">
    <input class="layui-input" name="id" id="demoReload" autocomplete="off">
  </div>
  <button class="layui-btn" data-type="reload">搜索</button>
</div>
<script type="text/html" id="toolbarDemo">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="getCheckData">获取选中行数据</button>
    <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
    <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
  </div>
</script> 
<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> 
<?php $this->start('script') ?>
<script>
layui.use('table', function(){
  var table = layui.table
  ,form = layui.form;
  
  //方法级渲染
  table.render({
    elem: '#LAY_table_user'
    ,url: '/demo/table/user/'
    ,toolbar: '#toolbarDemo'
    ,title: '用户数据表'
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      <?php foreach ($fields as $field): ?>
          ,{<?php foreach ($field as $key => $value): ?>
              <?= $key?>: '<?= $value?>',
          <?php endforeach ?>}
      <?php endforeach ?>
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
    ]]
    ,id: 'testReload'
    ,page: true
    ,height: 315
  });


  //头工具栏事件
  table.on('toolbar(test)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id);
    switch(obj.event){
      case 'getCheckData':
        var data = checkStatus.data;
        layer.alert(JSON.stringify(data));
      break;
      case 'getCheckLength':
        var data = checkStatus.data;
        layer.msg('选中了：'+ data.length + ' 个');
      break;
      case 'isAll':
        layer.msg(checkStatus.isAll ? '全选': '未全选');
      break;
    };
  });
  
  //监听行工具事件
  table.on('tool(test)', function(obj){
    var data = obj.data;
    //console.log(obj)
    if(obj.event === 'del'){
      layer.confirm('真的删除行么', function(index){
        obj.del();
        layer.close(index);
      });
    } else if(obj.event === 'edit'){
      layer.prompt({
        formType: 2
        ,value: data.email
      }, function(value, index){
        obj.update({
          email: value
        });
        layer.close(index);
      });
    }
  });

  //监听性别操作
  form.on('switch(sexDemo)', function(obj){
    layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
  });
  
  //监听锁定操作
  form.on('checkbox(lockDemo)', function(obj){
    layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
  });

  
  var $ = layui.$, active = {
    reload: function(){
      var demoReload = $('#demoReload');
      
      //执行重载
      table.reload('testReload', {
        page: {
          curr: 1 //重新从第 1 页开始
        }
        ,where: {
          key: {
            id: demoReload.val()
          }
        }
      });
    }
  };
  
  $('.demoTable .layui-btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });
});
</script>
<?php $this->end('script') ?>