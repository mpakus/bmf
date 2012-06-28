<?= form_open_multipart( 'photo/save/'.$post_id.'/'.$module_id, 'class="well form-inline"' ) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <label>Название:</label>
    <input type="text" name="alt" value="<?= form_prep($data['alt']) ?>" />
    <br/>
    <input type="file" name="image" class="input-xlarge" />
    <div class="controlls"><input type="submit" class="btn btn-success" value="Загрузить" /></div>
<?= form_close() ?>