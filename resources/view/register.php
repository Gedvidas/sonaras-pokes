<?php require_once VIEW_ROOT . 'partials/header.php';
$action = 'register'; ?>

    <form method="post" action="/<?php echo $action; ?>">
        <?php

        $data = getData('username', 'Vartotojo vardas', 'text');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('email', 'Elektroninis pastas', 'email');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('pass1', 'Slaptazodis', 'password');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('pass2', 'Pakartokite slaptazodi', 'password');
        include VIEW_ROOT . 'components/input.php';

        include VIEW_ROOT . 'components/button.php';

        ?>
    </form>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>
