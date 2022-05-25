<?php require_once VIEW_ROOT . 'partials/header.php' ?>

<div>
    <h1>
<!--        --><?php //var_dump($user); die(); ?>
        Hello, <?php if ($user) {echo $user->name;} else {echo 'Guest';} ?>
    </h1>
</div>
<div>
<?php if (!$user): ?>
    <div>
        <a href="/register"> Register</a>
    </div>
    <div>
        <a href="/login"> Login</a>
    </div>
<?php else: ?>
<div>
<!--    @todo CSRF problem. Should add token and use POST method-->
    <div>
        <a href="/edit">Redaguoti profili</a>
    </div>
    <div>
        <a href="/logout">Logout</a>
    </div>
</div>
<?php endif; ?>
</div>
<?php require_once VIEW_ROOT . 'partials/footer.php' ?>

