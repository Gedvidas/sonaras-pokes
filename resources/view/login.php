<?php require_once VIEW_ROOT . 'partials/header.php' ?>
<?php $action = 'Login'; ?>

<div >
    <form method="post" action="/login" class="px-4">
        <!--        Login form-->
        <?php require_once VIEW_ROOT . 'components/email.php' ?>
        <br>
        <?php require_once VIEW_ROOT . 'components/password.php' ?>
        <br>
        <?php require_once VIEW_ROOT . 'components/button.php' ?>

        <!--        @todo: move to component-->
        <!--        Errors and Confirmations-->
        <div id="error" class="alert alert-danger hidden d-none" role="alert">
        </div>
        <div id="confirmation" class="alert alert-success d-none" role="alert">
        </div>
    </form>

</div>
<?php require_once VIEW_ROOT . 'partials/footer.php' ?>

