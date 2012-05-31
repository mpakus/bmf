<div class="row">
    <div class="span12">        
    <?= form_open_multipart(current_url(), array('id' => 'post_form', 'class'=>'well form-horizontal')) ?>
    <fieldset>
        <legend>Свойства топика</legend>
        <hr/>
        <div class="error"><?php echo validation_errors(); ?></div>
        <div class="control-group">
            <label class="control-label">Заголовок топика:</label>
            <div class="controls">
                <input name="title" type="text" class="input-xlarge" value="<?= form_prep($post['title']) ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Тэги через запятую:</label>
            <div class="controls">
                <input type="text" class="input-xlarge"  id="tags" name="tags" value="<?= form_prep($post['tags']) ?>" />
            </div>
        </div>
        <div class="controls">
            <input type="submit" class="btn btn-primary" value="Сохранить" />
        </div>
    </fieldset>
    <?= form_close() ?>
    </div>
</div>

<? if( !empty($post['id']) ){ ?>

    <div class="row">
        <div class="span12">        
        <ul>
        <? foreach( $modules as $module ){ ?>
            <li>
                <div id="title_<?= $module['id']?>">
                    <strong><?= $module['name'] ?></strong>
                    <? if( $module['id'] != $module_id ){ ?>
                        <?= anchor('post/form/'.$post['id'].'/'.$module['id'], 'Редактировать') ?>
                    <? } ?>
                </div>
                <div id="module_<?= $module['id']?>">
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
<? } ?>