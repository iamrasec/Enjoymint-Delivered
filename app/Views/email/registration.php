<html>

<head>
    <title>Confirm Your Registration</title>
</head>

<body>
    <h3>Hi <?= $first_name; ?>, </h3>
    <p>Thank you for creating an account with us.</p>
    <p>Please Confirm and Activate your account by clicking on the link below or pasting it to your browser's address bar.</p>
    <p><a href="<?= $confirm_url; ?>" target="_blank"><?= $confirm_url; ?></a></p>
    <p>
        Thanks,<br />Enjoymint Delivered
    </p>
</body>

</html>