<div class="row">
    <div class="span12">        
    <?= form_open_multipart(current_url(), array('id' => 'post_form', 'class'=>'well form-horizontal')) ?>
    <fieldset>
        <legend>Свойства топика</legend>
        <div class="error"><?php echo validation_errors(); ?></div>
        <div class="control-group">
            <label class="control-label">Заголовок топика:</label>
            <div class="controls">
                <input name="title" type="text" class="input-xlarge" value="<?= form_prep($post['title']) ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Описание (meta):</label>
            <div class="controls">
                <input type="text" class="input-xlarge"  id="description" name="description" value="<?= form_prep($post['description']) ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Тэги через запятую:</label>
            <div class="controls">
                <input type="text" class="input-xlarge"  id="tags" name="tags" value="<?= form_prep($post['tags']) ?>" /> <button class="btn btn-primary"><span class="icon icon-white icon-hdd"></span> Сохранить</button>
            </div>
        </div>
    </fieldset>
    <?= form_close() ?>
    </div>
</div>

<? if( !empty($post['id']) ){ ?>

    <div class="row">
        <div class="span12">
        <ul class="modules-list" id="post_blocks_list">
        <? foreach( $modules as $module ){ ?>
            <li class="module-li" id="post_modules_<?= $module['id']?>" data-id="<?= $module['id']?>">
                <div id="title_<?= $module['id']?>" class="module-title">
                    <a name="mod-<?= $module['id'] ?>"></a>
                    <span class="icon icon-th-large sortcontrol"></span>
                    <? if( $module['id'] != $module_id ){ ?>
                        <span class="module-control">
                        <a href="<?= site_url('post/form/'.$post['id'].'/'.$module['id'].'#mod-'.$module['id'] ) ?>" class="btn btn-info"><i class="icon-pencil icon-white"></i> Редактировать</a>
                        <a href="<?= site_url( 'post/module_delete/'.$post['id'].'/'.$module['id']) ?>" class="btn btn-danger confirm"><i class="icon-trash icon-white"></i> Удалить</a>
                        </span>
                    <? } ?>
                </div>
                <br class="clear"/>
                <div id="module_<?= $module['id']?>" class="module-content">
                    <?= $module['output'] ?>
                </div>
            </li>
        <? } ?>
        </ul>
        </div>
    </div>    

    <div class="row">
        <div class="span12">        
        <?= form_open( 'post/add_module/'.$post['id'], 'id="add_module_form" class="well form-inline"' ) ?>
        <fieldset>
            <label>Добавить новый блок:</label>
            <select id="add_module_select" name="add_module[name]">                                                                                                                                                                                            
            <? foreach( $modules_for_add as $name=>$module ){ ?>
                <option value="<?= $name ?>"><?= $module['name'] ?></option>
            <? } ?>
            </select>
            <input type="submit" value="Добавить" class="btn"/>
        </fieldset>
        <?= form_close() ?>
        </div>
    </div>

    <div class="row">
        <div class="span12">
            <div class="form-actions">
                <a href="<?= site_url('post/publish/'.$post['id']) ?>" class="btn btn-primary"><span class="icon icon-ok-circle icon-white"></span> Опубликовать</a>
                <!--a href="<?= site_url('post/preview/'.$post['id']) ?>" class="btn" target="_blank">Предпросмотр</a>
                <a href="<?= site_url('post/draft/'.$post['id']) ?>" class="btn btn-inverse">Сохранить в черновиках</a-->
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(function(){        
      $("#post_blocks_list").sortable({
            handle: '.sortcontrol',
            axis: 'y',
            update: function(event, ui) {
                var post_modules = $(this).sortable('serialize');
                $.post(
                    "<?= site_url('post/sort_modules/'.$post['id']) ?>",
                    post_modules,
                    function(data){ BMF.message_ok( data ); }
                );
                
            }
        });
    });
</script>

<? } ?>