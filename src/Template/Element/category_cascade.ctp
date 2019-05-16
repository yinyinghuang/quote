<div class="layui-form-item">
    <label class="layui-form-label text-r">分类：</label>
    <div class="layui-inline">
        <select class="select" name="ZoneId" url="/zones/apiCascade" child="GroupId" childDataPath="/groups/apiCascade" selectValue="<?= $zone_id?>" promtion="请选择空间">
            <option value="">请选择空间</option>
        </select>
    </div>
    <div class="layui-inline">
        <select class="select" name="GroupId" child="CategoryId" childDataPath="/categories/apiCascade" selectValue="<?= $group_id?>" promtion="请选择分组">
            <option value="">请选择分组</option>
        </select>
    </div>
    <div class="layui-inline">
        <select class="select" name="CategoryId" selectValue="<?= $category_id?>" promtion="请选择分类">
            <option value="">请选择分类</option>
        </select>
    </div>
</div>

<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form','cascadeSelect'], function() {
    var $ = layui.jquery,
        form = layui.form,
        layer = layui.layer,
        step = layui.step,
        rate = layui.rate,
        upload = layui.upload,
        token = '<?=$token?>',
        cascadeSelect = layui.cascadeSelect
        ;
        cascadeSelect.init('ZoneId', false)
});

</script>
<?php $this->end('script')?>