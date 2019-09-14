<section class="lot-item container">
    <h2><?= $lot['name'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= htmlspecialchars($lot['img']) ?>" width="730"
                     height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория:
                <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot['description']) ?></p>
        </div>
        <div class="lot-item__right">
            <?php
            $last_wager = $wagers[0] ?? ['author_id' => -1];

            if (
                is_user_authorization() && strtotime($lot['date_end']) > time()
                && $lot['author_id'] !== (int)$_SESSION['user_id']
                && $last_wager['author_id'] !== $_SESSION['user_id']
            ):
                ?>
                <div class="lot-item__state">
                    <?php $expiration_date = get_dt_range($lot['date_end']); ?>
                    <div class="lot-item__timer timer <?= $expiration_date[0]
                    === '00' ? 'timer—finishing' : '' ?>">
                        <?= implode(':', $expiration_date) ?>
                    </div>

                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span
                                class="lot-item__cost"><?= to_format_currency($lot['max_wager']
                                    ?? $lot['start_price']) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?= to_format_currency($min_cost) ?> р</span>
                        </div>
                    </div>
                    <form class="lot-item__form <?= isset($cost_error)
                        ? 'lot-item__form--invalid' : '' ?>"
                          action="lot.php?id=<?= $id ?>" method="post"
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
                        $date = date('y.m.d', strtotime($wager['date']));
                        $time = date('h:m', strtotime($wager['date']));
                        ?>
                        <tr class="history__item">
                            <td class="history__name"><?= htmlspecialchars($wager['author']) ?></td>
                            <td class="history__price"><?= to_format_currency($wager['price']) ?>
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
