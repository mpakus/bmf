<?= form_open_multipart( 'text/save/'.$post_id.'/'.$module_id ) ?>
<div class="error"><?php echo validation_errors(); ?></div>
<table class="fields">
    <tr>
        <td colspan="2">
            <textarea id="textarea_<?= $module_id ?>" name="full"><?= $text['full'] ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:right;"><input type="submit" class="button" value="Сохранить" /></td>
    </tr>
</table>
<?= form_close() ?>