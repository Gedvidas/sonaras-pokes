<?php require_once VIEW_ROOT . 'partials/header.php' ?>

<div>
    <h1 id="skalaka">
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



    <?php if (!empty($users)): ?>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Pokes</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <th scope="row"><?php echo $user['id']; ?></th>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td id="<?php echo 'pokeall-' . $user['id']; ?>"><?php echo $user['pokes']; ?></td>
                <td>
                    <button type="button"
                            class="btn btn-primary"
                            data-value="<?php echo $user['pokes']; ?>"
                            onclick="poke('<?php echo 'poke-' . $user['id']; ?>', '<?php echo $user['pokes']; ?>')"
                            id="<?php echo 'poke-' . $user['id']; ?>">
                        Poke
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>



</div>
<?php endif; ?>
</div>

<?php require_once VIEW_ROOT . 'partials/footer.php' ?>