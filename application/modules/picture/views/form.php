<?php
    if (!empty( $picture )) echo '<div class="picture"><img src="'.$picture_path.'/'.$picture['image'].'" alt="'.$picture['alt'].'" /></div>';
?>
<?= form_open_multipart( 'picture/save/'.$post_id.'/'.$module_id, 'class="form form-horizontal"' ) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <label class="control-label">Название:</label>
    <div class="controls">
      <input type="text" name="alt" value="<?= form_prep($alt) ?>" class="input-xxlarge" />
    </div>
    <label class="control-label">Файл (*.jpg,.png,gif):</label>
    <div class="controls">
      <input type="file" name="image" class="input-xxlarge" />
    </div>
    <div class="controls">
      <button class="btn btn-success"><span class="icon icon-white icon-upload"></span>Загрузить</button>
    </div>
<?= form_close(); ?>