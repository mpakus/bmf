<? if( is_current_user($user) OR user_is('admin') ){ ?>    
    <?=form_open_multipart( current_url() , array('id'=>'profile_form') )?>
    <div class="error"><?php echo validation_errors(); ?></div>
<? } ?>
    
<table class="table">
    <tr>
        <td rowspan="9"><?= avatar( $user ) ?></td>
    </tr>
    <tr>
        <td>Логин:</td><td><strong><?= form_prep($user['login']) ?></strong> <? if($user['banned']==1){ ?><strong class="red">(ЗАБАНЕН)</strong><? } ?></td>
    </tr>    
    <tr>
        <td>Зарегистрирован:</td><td><?= human_read_date( $user['registered_at'] ) ?></td>
    </tr>    
    <?
    if(user_signed_in() ){ 
        if( is_current_user($user) OR user_is('admin') ){
        ?>
        <tr>
            <td>E-mail:</td><td><input type="text" name="email" class="string size90p" value="<?= form_prep( $user['email'] ) ?>"/></td>
        </tr>
        <tr>
            <td>Сменить пароль:</td>
            <td>
                <input type="password" class="string" style="width:120px" id="new_password" name="new_password" /> 
                <label><input id="showcharacters" name="showcharacters" type="checkbox" /> показать</label>
            </td>
        </tr>
      
        <tr>
            <td>Сменить аватар:</td>
           <td><input name="avatar" type="file" class="size90p" /></td>
        </tr>
        <?
        }else{
        ?>
        <tr>
            <td>E-mail:</td><td><?= safe_mailto($user['email'], 'написать письмо') ?></td>
        </tr>    
        <?
        }
    }
    ?>
    <tr>
        <td>Материалов:</td><td><strong><?= form_prep($user['posts_count']) ?></strong></td>
    </tr>    
    <tr>
        <td>Комментариев:</td><td><strong><?= form_prep($user['comments_count']) ?></strong></td>
    </tr>    
</table>
<?
    if( is_current_user($user) OR user_is('admin') ){        
    ?>
    <input type="submit" value="Сохранить" class="button" />
</form>
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
    $('#showcharacters').click(function() {
        if ($(this).attr('checked')) {
            $('#new_password').replaceWith('<input id="new_password" name="new_password" class="input" style="width:120px" type="text" value="' + $('#new_password').attr('value') + '" />');
        } else {
            $('#new_password').replaceWith('<input id="new_password" name="new_password" class="input" style="width:120px" type="password" value="' + $('#new_password').attr('value') + '" />');
        }
    });
});
</script>
    <?
     }
     
    if( user_is('admin') AND !is_current_user($user) ){
        if( $user['banned'] == 0 ){
            ?>
            <br/><form method="POST" action="<?= site_url('user/ban') ?>">
                <input type="hidden" name="user_id" value="<?= form_prep($user['id']) ?>" />
                <input type="submit" value="Забанить пользователя" class="button danger" />
            </form>
            <?
        }elseif( $user['banned'] == 1 ){
            ?>
            <br/><form method="POST" action="<?= site_url('user/unban') ?>">
                <input type="hidden" name="user_id" value="<?= form_prep($user['id']) ?>" />
                <input type="submit" value="Разбанить пользователя" class="button allow" />
            </form>
            <?
        }
    }
?>