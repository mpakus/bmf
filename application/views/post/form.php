<div class="poster">
    <h1>Создание топика</h1>

    <?=form_open_multipart( current_url() , array('id'=>'post_form') )?>
        <div class="error"><?php echo validation_errors(); ?></div>

        <table class="fields">

            <tr>
                <td><b class="red">*</b> Заголовок:</td>
                <td><input name="title" type="text" class="input size90p" value="<?=form_prep($data['title'])?>" /></td>
            </tr>

            <?= $addon_form ?>

            <tr>
                <td><b class="red">*</b> Метки:</td>
                <td><input type="text" class="input size90p"  id="tags" name="tags" value="<?=form_prep($data['tags'])?>" /></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:right;"><input type="submit" class="button" value="Опубликовать" /></td>
            </tr>

        </table>

    </form>
    
</div>