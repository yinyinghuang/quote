<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Area[]|\Cake\Collection\CollectionInterface $areas
 */
?>

<div class="demoTable">
  <form class="layui-form">
    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">ID</label>
        <div class="layui-input-block">
          <input type="number" name="search[id]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
          <input type="text" name="search[name]" placeholder="" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">品牌</label>
        <div class="layui-input-block">
          <input type="text" name="search[brand]" placeholder="" autocomplete="off" class="layui-input">
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
    
    <?= $this->element('category_select',['search'=>true])?>

    <div class="layui-form-item">
      <div class="layui-inline">
        <label class="layui-form-label">新品</label>
        <div class="layui-input-block">
          <input type="checkbox" name="search[is_new]" lay-skin="switch" lay-text="是|否">
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">热门</label>
        <div class="layui-input-block">
          <input type="checkbox" name="search[is_hot]" lay-skin="switch" lay-text="是|否">
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-products">搜索</button>
      </div>
    </div>
  </form>
</div>


<?= $this->element('table',$tableParams['products'])?>

