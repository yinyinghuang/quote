<?php?>
<div class="layui-tab">
  <ul class="layui-tab-title">
    <li<?php if (!isset($active) || !$active): ?> class="layui-this"<?php endif ?>>空间详情</li>
    <?php if (!$zone->isNew()): ?>
    <li<?php if (isset($active) && $active=='groups'): ?> class="layui-this"<?php endif ?>>空间分组<span class="layui-badge"><?=$zone->groupCount?></span></li>    
    <li<?php if (isset($active) && $active=='categories'): ?> class="layui-this"<?php endif ?>>空间分类<span class="layui-badge"><?=$zone->categoryCount?></span></li>
    <li<?php if (isset($active) && $active=='products'): ?> class="layui-this"<?php endif ?>>空间产品<span class="layui-badge"><?=$zone->productCount?></span></li>    
    <?php endif ?>
  </ul>
  <div class="layui-tab-content">
    <!-- 空间详情 -->
    <div class="layui-tab-item zone_info<?php if (!isset($active) || !$active): ?> layui-show<?php endif ?>">
        <form class="layui-form" action="" id="zoneDetail" enctype="multipart/form-data">
            <?php if ($zone->isNew()): ?>
            <input type="hidden" name="type" value="add">
            <?php endif ?>
            <input type="hidden" name="detail" value="1">
            <input type="hidden" name="id" value="<?= $zone->id?>">   
            <div class="layui-step-content-item zone_info">
                <div class="layui-form-item">
                    <label class="layui-form-label">空间名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" autocomplete="off" placeholder="请输入空间名称" class="layui-input" value="<?= $zone->name?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">前台可见</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_visible" value="1" lay-skin="switch" lay-text="是|否" <?php if (!($zone->is_visible===false)): ?>checked
                        <?php endif?>>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-block">
                            <input type="text" name="sort" autocomplete="off" class="layui-input" value="<?=$zone->sort?>">
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
<?php if (!$zone->isNew()): ?>
    <!-- 空间分组 -->
    <div class="layui-tab-item zone_group<?php if (isset($active) && $active=='groups'): ?> layui-show<?php endif ?>">
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
            </div>
            <div class="layui-form-item">
              <div class="layui-inline">
                <label class="layui-form-label">可见</label>
                <div class="layui-input-block">
                  <input type="checkbox" name="search[is_visible]" lay-skin="switch" lay-text="是|否">
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-groups">搜索</button>
              </div>
            </div>
          </form>
        </div>
        <?= $this->element('table',$tableParams['groups'])?>
    </div>
    <!-- 空间分类 -->
    <div class="layui-tab-item zone_category<?php if (isset($active) && $active=='categories'): ?> layui-show<?php endif ?>"">
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
                <label class="layui-form-label">分组</label>
                <div class="layui-input-block">
                  <select name="search[group_id]">
                    <option value="">请选择分组</option>
                    <?php foreach ($groups as $key => $value): ?>
                    <option value="<?=$key?>"><?=$value?></option>
                    <?php endforeach?>
                  </select>
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <div class="layui-inline">
                <label class="layui-form-label">可见</label>
                <div class="layui-input-block">
                  <input type="checkbox" name="search[is_visible]" lay-skin="switch" lay-text="是|否">
                </div>
              </div>
            </div>
            <div class="layui-form-item">
              <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-submit lay-filter="search-categories">搜索</button>
              </div>
            </div>
          </form>
        </div>
        <?= $this->element('table',$tableParams['categories'])?>
    </div>
    <!-- 空间产品 -->
    <div class="layui-tab-item zone_product<?php if (isset($active) && $active=='products'): ?> layui-show<?php endif ?>"">
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
            <div class="layui-form-item">       
                <div class="layui-inline">
                  <label class="layui-form-label">分类</label>
                  <div class="layui-input-block">
                    <select name="search[category_id]">
                      <option value="">请选择分类</option>
                      <?php foreach ($categories as $key => $value): ?>
                      <option value="<?=$key?>"><?=$value?></option>
                      <?php endforeach?>
                    </select>
                  </div>
                </div>
            </div>
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
              <div class="layui-inline">
                <label class="layui-form-label">可见</label>
                <div class="layui-input-block">
                  <input type="checkbox" name="search[is_visible]" lay-skin="switch" lay-text="是|否">
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
    </div>
<?php endif ?>
  </div>
</div>

<?php $this->start('script')?>
<script>
layui.config({
    base: "/vendor/layui/lay/modules/"
}).use(['form'], function() {
    var $ = layui.jquery,
        form = layui.form,
        layer = layui.layer,
        token = '<?=$token?>';    
    //监听提交
    form.on('submit(save)', function(data) { 
        $('#save').attr('disabled',true) 
        ajax($,{
            token,
            url: '/zones/api-save',
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