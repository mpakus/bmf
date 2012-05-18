<section id="post">
    <h1>Создание топика</h1>
    <?= form_open_multipart(current_url(), array('id' => 'post_form')) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <table class="fields">
        <tr>
            <td><b class="red">*</b> Заголовок топика:</td>
            <td><input name="title" type="text" class="input size90p" value="<?= form_prep($post['title']) ?>" /></td>
        </tr>
        <tr>
            <td><b class="red">*</b> Тэги через запятую:</td>
            <td><input type="text" class="input size90p"  id="tags" name="tags" value="<?= form_prep($post['tags']) ?>" /></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;"><input type="submit" class="button" value="Сохранить" /></td>
        </tr>
    </table>
    <?= form_close() ?>
</section>

<? if( !empty($post['id']) ){ ?>
    <section id="modules">
    </section>

    <section id="add_module">
        <h1>Добавить новый блок</h1>
        <?= form_open( 'post/add_module/'.$post['id'], 'id="add_module_form' ) ?>
            <select id="add_module_select" name="add_module[name]">
                                                                                                                                                                                            <? foreach( $modules as $name=>$module ){ ?>
                <option value="<?= $name ?>"><?= $module['name'] ?></option>
            <? } ?>
            </select>
            <input type="submit" value="Добавить" />
        <?= form_close() ?>
    </section>
<? } ?>