<p>
    <?php
        if (!empty( $photo ))
            echo '<img src="'.$photo_path.'/'.$photo['image'].'" alt="'.$photo['alt'].'" />';
    ?>
</p>
<?= form_open_multipart( 'photo/save/'.$post_id.'/'.$module_id, 'class="well form-inline"' ) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <label>Название:</label>
    <input type="text" name="alt" value="<?= form_prep($alt) ?>" />
    <br/>
    <input type="file" name="image" class="input-xlarge" />
    <div class="controlls"><input type="submit" class="btn btn-success" value="Загрузить" /></div>
<?= form_close(); ?>