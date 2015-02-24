<?php if ($this->flashmessage): ?>
    <div class="alert alert-dismissable alert-<?= $this->flashmessage->status; ?>">
	<button type="button" class="close" data-dismiss="alert">×</button>
        <?= $this->flashmessage->message; ?>
    </div>
<?php endif; ?>
