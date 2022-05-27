<?php require_once VIEW_ROOT . 'partials/header.php'; ?>
<div>
    <h3>
        My pokes
    </h3>
</div>
<?php if (!empty($users)): ?>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($users as $key=>$user): ?>
                <tr>
                    <td><?php echo $key+1; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require_once VIEW_ROOT . 'partials/footer.php' ?>
