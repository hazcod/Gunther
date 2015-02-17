<div class="row">
    <div class="col-md-4 col-md-offset-4">
    <?php $this->renderPartial('flashmessage'); ?>
      <form class="form-signin" method="post" action="/home/login">
        <h2 class="">Sign in to continue.</h2>  
        <hr>
        
        <label for="username" class="sr-only">Email address</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus value="<? if ($this->formdata){ echo $this->formdata->username; } ?>">

        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

        <br>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
    </div>
</div>