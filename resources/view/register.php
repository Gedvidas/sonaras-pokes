<?php use App\Core\Application;

require_once VIEW_ROOT . 'partials/header.php';
$action = 'Register';

function getData(string $name, string $text, string $type) : array {
    $data = [];
    $data['name'] = $name;
    $data['error'] = (isset(Application::$errors[$name])) ? Application::$errors[$name] : false;
    $data['old'] =  (isset(Application::$old[$name])) ? Application::$old[$name] : false;
    $data['conf'] =  !$data['error'] && $data['old'];
    $data['text'] =  $text;
    $data['type'] =  $type;

    return $data;
} ?>

    <form method="post" action="/register">
        <?php

        $data = getData('username', 'Vartotojo vardas', 'text');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('email', 'Elektroninis pastas', 'email');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('pass1', 'Slaptazodis', 'password');
        include VIEW_ROOT . 'components/input.php';

        $data = getData('pass2', 'Pakartokite slaptazodi', 'password');
        include VIEW_ROOT . 'components/input.php';

        ?>

        <div class="form-group row formele">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary btn-lg active" id="processRegister">Registruotis</button>
            </div>
        </div>

<!--        Errors and Confirmations-->
        <div id="error" class="alert alert-danger hidden d-none" role="alert">
        </div>
        <div id="confirmation" class="alert alert-success d-none" role="alert">
        </div>
    </form>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>
