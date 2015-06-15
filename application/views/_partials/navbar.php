<?php
    function getLangName($arr, $code){
        $result = '';
        for ($i=0; $arr != false and $result == '' and $i < sizeof($arr); $i++){
            if ($arr[$i]->flag == $code){
                $result = $arr[$i]->name;
		            break;
            }
        }
        return $result;
    }
?>
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <a href="<?= URL::base_uri();?>home" class="navbar-brand"><?= $this->lang['title']; ?></a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
          <?php foreach ($this->menuitems as $menuitem): ?>
            <?php if (!is_array($menuitem['link'])): ?>
            <li class="<?= ($menuitem['link'] == URL::getCurrentPath()) ? 'active' : ''; ?>">
              <a href="<?= URL::base_uri(); ?><?= $menuitem['link']; ?>"><?= $menuitem['description']; ?></a>
            </li>
            <?php else: ?>
            <li class="dropdown">
                <a href="" class="dropdown-toggle" data-toggle="dropdown"><?= $menuitem['description']; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                <?php foreach ($menuitem['link'] as $item): ?>
                    <li class="<?= ($item['link'] == URL::getCurrentPath()) ? 'active' : ''; ?>">
                      <a href="<?= URL::base_uri(); ?><?= $item['link']; ?>"><?= $item['description']; ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </li>
            <?php endif; ?>
          <?php endforeach; ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="" class="dropdown-toggle" data-toggle="dropdown"><img alt="<?= $_SESSION['lang']; ?>" src="<?= URL::base_uri(); ?>img/flags/<?= $_SESSION['lang']; ?>.png" />&nbsp;<?= getLangName($this->langs, $_SESSION['lang']); ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php foreach ($this->langs as $key => $lang): ?>
            <li><a href="<?= URL::base_uri() .'home/index?lang=' . $lang->flag; ?>"><img alt="<?= $lang->name; ?>" src="<?= URL::base_uri(); ?>img/flags/<?= $lang->flag; ?>.png" />&nbsp;<?= $lang->name; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <?php if (isset($_SESSION['user'])): ?>
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $_SESSION['user']; ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="/home/logout"><i class="fa fa-close"></i> <?= $this->lang['logout']; ?></a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
