<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $user_name; ?></p>
<p>Ваша ставка для лота
    <a href="<?= (isset(MAIL_WINNER_CONFIG["ADDRESS"]) ?
        MAIL_WINNER_CONFIG["ADDRESS"] : '')."/lot.php?id=$lot_id"; ?>">
        <?= $lot_name; ?>
    </a>
    победила.</p>
<p>Перейдите по ссылке
    <a href="<?= (isset(MAIL_WINNER_CONFIG["ADDRESS"]) ?
        MAIL_WINNER_CONFIG["ADDRESS"] : '')."/my-lots.php"; ?>">мои
        ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
