<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <?php if (isset($error)) : ?>
        <p><?= $error ?></p>
    <?php endif ?>

    <h1>Home</h1>

    <h3>User Form</h3>

    <h3><?= $name ?? "Your Name" ?> | <?= $email ?? "Your Email" ?></h3>

    <form method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="email" name="email" placeholder="Email">

        <input type="submit">
    </form>
</body>
</html>