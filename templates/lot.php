<section class="lot-item container">
    <h2><?= $lot['name'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="../<?= $lot['img'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category'] ?></span></p>
            <p class="lot-item__description"><?= $lot['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php $expiration_date = get_dt_range($value['date_end']); ?>
                <div class="lot-item__timer timer <?= $expiration_date[0] === '00' ? 'timer—finishing' : '' ?>">
                    <?= implode(':', $expiration_date) ?>
                </div>

                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= to_format_currency($wagers ? max(array_column($wagers, 'price')) : $lot['start_price']) ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?= to_format_currency($lot['start_price']) ?> р</span>
                    </div>
                </div>
                <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                    <p class="lot-item__form-item form__item form__item--invalid">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="12 000">
                        <span class="form__error">Введите наименование лота</span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <div class="history">
                <h3>История ставок (<span><?= count($wagers) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach($wagers as $wager): ?>
                        <?php
                            $date = date('y.m.d', strtotime($wager['date']));
                            $time = date('h:m', strtotime($wager['date']));
                        ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $wager['author'] ?></td>
                            <td class="history__price"><?= to_format_currency($wager['price']) ?> р</td>
                            <td class="history__time"><?= $date . ' в ' . $time ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
