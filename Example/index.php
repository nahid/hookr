<?php

require_once '../vendor/autoload.php';

$users = [
    [
        'id'=>1, 'username'=>'nahid', 'name'=>'Mehedi Hasan Nahid', 'workfor'=>'Pathao'
    ],
    [
        'id'=>2, 'username'=>'sumi', 'name'=>'Anjani Sumi', 'workfor'=>'Housewife'
    ],
    [
        'id'=>3, 'username'=>'nazat', 'name'=>'Meherunessa Nazat', 'workfor'=>'Baper Hotel'
    ],
];

$hookr = new \Nahid\Hookr\Hook();

$hookr->bindAction('button', function($user) {
    echo '<a href="/delete/' . $user['id'] .'">delete</a>';
    echo ' <a href="/profile/' . $user['username'] .'">profile</a>';
}, 1);

?>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Work For</th>
        <th>Action</th>
    </tr>

    <?php
        foreach ($users as $user):
    ?>
        <tr>
            <td><?= $user['id']; ?></td>
            <td><?= $user['name']; ?></td>
            <td><?= $user['workfor']; ?></td>
            <td>
                <a href="/edit/<?= $user['id']; ?>">Edit</a>
                <?php hook_action('button', ['user'=>$user]); ?>

            </td>
        </tr>

    <?php
        endforeach;
    ?>
</table>

