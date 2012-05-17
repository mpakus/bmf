
<tr>
    <td colspan="2">
        <textarea id="original" name="original" class="size90p" style="width:565px;height:200px;"><?= form_prep($data['original']) ?></textarea>
    </td>
</tr>

<script type="text/javascript" src="<?= static_url('markitup/jquery.markitup.js') ?>"></script>
<script type="text/javascript" src="<?= static_url('markitup/sets/html/set.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= static_url('markitup/skins/simple/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= static_url('markitup/sets/html/style.css') ?>" />

<script language="javascript">
$(document).ready(function()	{
   $('#original').markItUp(mySettings);
});
</script>

<tr>
    <td>Картинка:</td>
   <td><input name="preview" type="file" class="size90p" /></td>
</tr>
            