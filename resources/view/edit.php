<?php use App\Core\Application;

require_once VIEW_ROOT . 'partials/header.php';
$action = 'edit'; ?>

<form method="post" action="/<?php echo $action; ?>">
    <?php

    $data = getData('username', 'User Name', 'text');
    include VIEW_ROOT . 'components/input.php';

    $data = getData('email', 'Email', 'email');
    include VIEW_ROOT . 'components/input.php';

    $data = getData('pass0', 'Old password', 'password');
    include VIEW_ROOT . 'components/input.php';

    $data = getData('pass1', 'New Password', 'password');
    include VIEW_ROOT . 'components/input.php';

    $data = getData('pass2', 'Confirm New Password', 'password');
    include VIEW_ROOT . 'components/input.php';

    include VIEW_ROOT . 'components/button.php';

    ?>
</form>

<?php if (isset(Application::$confirmation) && !empty(Application::$confirmation)): ?>
    <div class="alert alert-success" role="alert">
        <?php echo Application::$confirmation; ?>
    </div>
<?php endif; ?>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>
