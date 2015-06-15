<div class="row">
    <div class="col-md-4 col-md-offset-4">
    <?php $this->renderPartial('flashmessage'); ?>
      <form class="form-signin" method="post" action="/home/login">
        <h2 class=""><?= $this->lang['loginto']; ?></h2>  
        <hr>
        
        <label for="username" class="sr-only"><?= $this->lang['email']; ?></label>
        <input type="text" id="username" name="username" class="form-control" placeholder="<?= $this->lang['username']; ?>" required autofocus value="<?php if ($this->formdata){ echo $this->formdata->username; } ?>">

        <label for="password" class="sr-only"><?= $this->lang['password']; ?></label>
        <input type="password" id="password" name="password" class="form-control" placeholder="<?= $this->lang['password']; ?>" required>

        <br>

        <button class="btn btn-lg btn-primary btn-block" type="submit"><?= $this->lang['signin']; ?></button>
      </form>
    </div>
</div>