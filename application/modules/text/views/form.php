<?= form_open_multipart( 'text/save/'.$post_id.'/'.$module_id, 'class="form"' ) ?>
    <div class="error"><?php echo validation_errors(); ?></div>
    <textarea class="input-textarea" id="textarea_<?= $module_id ?>" name="original"><?= form_prep($text['original']) ?></textarea>
    <div class="controlls"><button class="btn btn-success"><span class="icon icon-white icon-hdd"></span> Сохранить</button></div>
<?= form_close() ?>

<link rel="stylesheet" type="text/css" href="<?= site_url('static/markitup/skins/simple/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= site_url('static/markitup/sets/html/style.css') ?>" />
<script type="text/javascript" src="<?= site_url('static/markitup/jquery.markitup.js') ?>"></script>
<script type="text/javascript" src="<?= site_url('static/markitup/sets/html/set.js') ?>"></script>

<script language="javascript" type="text/javascript">
$(function() {
   $('#textarea_<?= $module_id ?>').markItUp(mySettings);
});
</script>