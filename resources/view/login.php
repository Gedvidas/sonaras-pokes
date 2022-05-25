<?php require_once VIEW_ROOT . 'partials/header.php' ?>
<?php $action = 'login'; ?>


<form method="post" action="/<?php echo $action; ?>">
    <?php
//    echo \App\Core\Application::$errors; die();
    $data = getData('email', 'Elektroninis pastas', 'email');
    include VIEW_ROOT . 'components/input.php';

    $data = getData('pass1', 'Slaptazodis', 'password');
    include VIEW_ROOT . 'components/input.php';


    include VIEW_ROOT . 'components/button.php';

    ?>
</form>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>

