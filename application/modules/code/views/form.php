<?= form_open_multipart( 'code/save/'.$post_id.'/'.$module_id, 'class="form"' ) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <div class="control-group">
        <label>Язык:
        <?= form_dropdown( 'language', $languages, $code['language'], 'class="input-xlarge"') ?>
        </label>
    </div>
    <div class="control-group">
        <textarea class="input-textarea" id="textarea_<?= $module_id ?>" name="full"><?= form_prep($code['full']) ?></textarea>
    </div>
    <div class="controls"><input type="submit" class="btn btn-success" value="Сохранить" /></div>
<?= form_close() ?>