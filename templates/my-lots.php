<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($wagers as $wager): ?>
            <?php
            $data_wager_time = get_wager_status($wager, $_SESSION['user_id']);
            $wager_status = '';
            if ($data_wager_time['is_win']) {
                $wager_status = 'rates__item--win';
            } else {
                if ($data_wager_time['is_finishing']) {
                    $wager_status = 'rates__item--end';
                }
            }

            ?>
            <tr class="rates__item <?= $wager_status ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?= htmlspecialchars($wager['img']) ?>"
                             width="54" height="40"
                             alt="<?= htmlspecialchars($wager['name']) ?>">
                    </div>
                    <h3 class="rates__title"><a
                            href="lot.php?id=<?= $wager['lot_id'] ?>"><?= htmlspecialchars($wager['name']) ?></a>
                    </h3>
                </td>
                <td class="rates__category"><?= $wager['cat_name'] ?></td>
                <td class="rates__timer">
                    <?php if ($data_wager_time['is_win']): ?>
                        <div class="timer timer--win">Ставка выиграла</div>
                    <?php elseif ($data_wager_time['is_finishing']): ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else: ?>
                        <div
                            class="timer <?= $data_wager_time['is_close_to_completion']
                                ? 'timer--finishing' : '' ?>">
                            <?= date('H:i:s', strtotime($wager['date_end'])) ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="rates__price"><?= htmlspecialchars($wager['price']) ?>
                    р
                </td>
                <td class="rates__time"><?= format_date_personal_lot($wager['date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
