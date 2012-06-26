	<h1>LJ posts import</h1>
	<div class="row">

<?php if(!$lj_authorized) { ?>

    	<form method="POST" action="<?=site_url('livejournal/index')?>">

    		<div class="error"><?php echo $error; ?></div>

    		<table class="fields">
        		<tr><td>Логин:</td><td><input name="login" type="text" class="string size90p" /></td></tr>
        		<tr><td>Пароль:</td><td><input type="password" name="password" class="string size90p"/></td></tr>
        		<td><td colspan="2"><input type="submit" value="Go!" class="button" /></td></tr>
    		</table>
    
		</form>
	</div>

<?php } else { ?>

	Thanks for entering your credentials! Your posts import will begin immediately! 

<?php } ?>