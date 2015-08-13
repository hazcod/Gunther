<div class="col-sm-8 col-sm-offset-2">
	<ul class="breadcrumb">
	  <li><?= $this->lang['title']; ?></li>
	  <li><?= $this->lang['admin']; ?></li>
	  <li class="active"><?= $this->lang['adduser']; ?></li>
	</ul>

	<?php $this->renderPartial('flashmessage'); ?>

	<br>

	<div class="row">
		<h3><?= $this->lang['adduser']; ?></h3>
		<hr>
		<form class="form-horizontal" method="POST" action="/admin/adduser">
		  <div class="form-group">
		    <label for="inputName" class="col-sm-2 control-label"><?= $this->lang['name']; ?></label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="inputName" name="name" required autofocus placeholder="<? if($this->formdata){ echo $this->formdata->name; } else { echo $this->lang['name']; } ?>">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="inputLogin" class="col-sm-2 control-label"><?= $this->lang['login']; ?></label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="inputLogin" name="username" required placeholder="<? if($this->formdata){ echo $this->formdata->username; } else { echo $this->lang['login']; } ?>">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="inputEmail" class="col-sm-2 control-label"><?= $this->lang['email']; ?></label>
		    <div class="col-sm-10">
		      <input type="email" class="form-control" id="inputEmail" name="email" required placeholder="<? if($this->formdata){ echo $this->formdata->email; } else { echo $this->lang['email']; } ?>">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="inputPassword" class="col-sm-2 control-label"><?= $this->lang['password']; ?></label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="inputPassword" name="password" required value="<? if($this->formdata){ echo $this->formdata->password; } else { echo $this->password; } ?>">
		    </div>
		  </div>
		  <div class="form-group">
		  	<label for="sellectRole" class="col-sm-2 control-label"><?= $this->lang['role']; ?></label>
		    <div class="col-sm-10">
		    	<select class="form-control" id="sellectRole" name="role">
		    		<? foreach ($this->roles as $role): ?>
		    			<? if (($this->formdata && $role->id == $this->formdata->role) or (!$this->formdata && ($role->id == 1))): ?>
		    			<option value='<?= $role->id; ?>' selected='selected'><?= $role->name; ?></option>
		    			<? else: ?>
		    			<option value='<?= $role->id; ?>'><?= $role->name; ?></option>
		    			<? endif; ?>
		    		<? endforeach; ?>
		    	</select>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button class="btn btn-primary" type="submit"><?= $this->lang['adduser']; ?></button>
		      <a class="btn btn-danger" href="/admin/index"><?= $this->lang['cancel']; ?></a>
		    </div>
		  </div>
		</form>
	</div>

	<br>
</div>
