<section class="lot-item container">
    <h2><?= isset($lot['name']) ? htmlspecialchars($lot['name']) : '' ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= isset($lot['img'])
                    ? htmlspecialchars($lot['img']) : '' ?>"
                     width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория:
                <span><?= isset($lot['category'])
                        ? htmlspecialchars($lot['category'])
                        : '' ?></span></p>
            <p class="lot-item__description"><?= isset($lot['description']) ?
                    htmlspecialchars($lot['description']) : '' ?></p>
        </div>
        <div class="lot-item__right">
            <?php
            $last_wager = isset($wagers[0]) ? $wagers[0] : ['author_id' => -1];
            $last_wager_author_id = isset($last_wager['author_id'])
                ? $last_wager['author_id'] : '';

            $lot_date_end = isset($lot['date_end']) ? $lot['date_end'] : '';
            $lot_author_id = isset($lot['author_id']) ? $lot['author_id'] : '';
            $lot_max_wager = isset($lot['max_wager']) ? $lot['max_wager'] : '';
            $lot_start_price = isset($lot['start_price']) ? $lot['start_price']
                : '';

            if (
                is_user_authorization() && strtotime($lot_date_end) > time()
                && $lot_author_id !== $_SESSION['user_id']
                && $last_wager_author_id !== $_SESSION['user_id']
            ):
                ?>
                <div class="lot-item__state">
                    <?php
                    $expiration_date = get_date_range($lot_date_end);
                    $expiration_date_arr = explode(':', $expiration_date);
                    ?>
                    <div
                        class="lot-item__timer timer <?= $expiration_date_arr[0]
                        === '00' ? 'timer--finishing' : '' ?>">
                        <?= $expiration_date ?>
                    </div>

                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span
                                class="lot-item__cost"><?= htmlspecialchars(to_format_currency(
                                    !empty($lot_max_wager)
                                        ? $lot_max_wager
                                        : $lot_start_price)) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?= htmlspecialchars(to_format_currency($min_cost)) ?> р</span>
                        </div>
                    </div>
                    <form class="lot-item__form <?= isset($cost_error)
                        ? 'lot-item__form--invalid' : '' ?>"
                          action="lot.php?id=<?= htmlspecialchars($id) ?>"
                          method="post"
                          autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost"
                                   placeholder="12 000"
                                   value="<?= getPostVal('cost') ?>">
                            <span class="form__error"><?= $cost_error ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку
                        </button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($wagers) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($wagers as $wager): ?>
                        <?php
                        $wager_date = isset($wager['date']) ? $wager['date']
                            : '';
                        $wager_author = isset($wager['author'])
                            ? $wager['author'] : '';
                        $wager_price = isset($wager['price']) ? $wager['price']
                            : '';

                        $date = date('y.m.d', strtotime($wager_date));
                        $time = date('h:m', strtotime($wager_date));
                        ?>
                        <tr class="history__item">
                            <td class="history__name"><?= htmlspecialchars($wager_author) ?></td>
                            <td class="history__price"><?= htmlspecialchars(to_format_currency($wager_price)) ?>
                                р
                            </td>
                            <td class="history__time"><?= $date.' в '
                                .$time ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
