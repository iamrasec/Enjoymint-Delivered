<html>

<head>
    <title>Reset Password</title>
</head>

<body>
    <h3>Hi <?= $first_name; ?>, </h3>
    <p>Your reset password request has been recieved. Please click the link below to reset your password.</p>
    <p><a href="<?= $reset_url; ?>" target="_blank"><?= $reset_url; ?></a></p>
    <p>
        Thanks,<br />Enjoymint Delivered
    </p>
</body>

</html>