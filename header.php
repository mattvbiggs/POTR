<header>
    <div class="header-logo">
        POTR
    </div>
    <div class="header-home-left">
        <?php echo $UserName; ?>	(<?php echo htmlentities($login); ?>)<br />
	    <?php echo htmlentities($title); ?> at <?php echo htmlentities($Location); ?>
    </div>
    <div class="header-home">
	    <label for="logout" class="logout-click"><i class="fa fa-sign-out"> Logout</i></label>
    </div>
</header>