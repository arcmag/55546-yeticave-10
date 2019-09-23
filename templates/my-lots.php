<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($wagers as $wager): ?>
            <?php
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

            $data_wager_time = get_wager_status($wager, $user_id);
            $data_wager_time_is_win = isset($data_wager_time['is_win']) ?
                $data_wager_time['is_win'] : false;
            $data_wager_time_is_finishing
                = isset($data_wager_time['is_finishing']) ?
                $data_wager_time['is_finishing'] : false;
            $data_wager_time_close
                = isset($data_wager_time['is_close_to_completion']) ?
                $data_wager_time['is_close_to_completion'] : false;

            $wager_status = '';
            if ($data_wager_time_is_win) {
                $wager_status = 'rates__item--win';
            } elseif ($data_wager_time_is_finishing) {
                $wager_status = 'rates__item--end';
            }

            $wager_img = isset($wager['img']) ? $wager['img'] : '';
            $wager_name = isset($wager['name']) ? $wager['name'] : '';
            $wager_lot_id = isset($wager['lot_id']) ? $wager['lot_id'] : '';
            $wager_cat_name = isset($wager['cat_name']) ? $wager['cat_name']
                : '';
            $wager_date_end = isset($wager['date_end']) ? $wager['date_end']
                : '';
            $wager_price = isset($wager['price']) ? $wager['price'] : '';
            $wager_date = isset($wager['date']) ? (time() - strtotime($wager['date'])) : '';

            ?>
            <tr class="rates__item <?= $wager_status ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?= htmlspecialchars($wager_img) ?>"
                             width="54" height="40"
                             alt="<?= htmlspecialchars($wager_name) ?>">
                    </div>
                    <h3 class="rates__title"><a
                            href="lot.php?id=<?= htmlspecialchars($wager_lot_id) ?>"><?= htmlspecialchars($wager_name) ?></a>
                    </h3>
                </td>
                <td class="rates__category"><?= htmlspecialchars($wager_cat_name) ?></td>
                <td class="rates__timer">
                    <?php if ($data_wager_time_is_win): ?>
                        <div class="timer timer--win">Ставка выиграла</div>
                    <?php elseif ($data_wager_time_is_finishing): ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else: ?>
                        <div
                            class="timer <?= $data_wager_time_close
                                ? 'timer--finishing' : '' ?>">
                            <?= get_date_range($wager_date_end) ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="rates__price"><?= htmlspecialchars($wager_price) ?>
                    р
                </td>
                <td class="rates__time"><?= htmlspecialchars(format_date_personal_lot($wager_date)); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
