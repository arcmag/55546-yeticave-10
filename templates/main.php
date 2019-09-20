<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое
        эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $item): ?>
            <li class="promo__item promo__item--<?= isset($item['code'])
                ? $item['code'] : '' ?>">
                <a class="promo__link"
                   href="lots-by-category.php?category=<?= isset($item['id'])
                       ? $item['id'] : '' ?>">
                    <?= isset($item['name']) ? htmlspecialchars($item['name'])
                        : '' ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($announcement_list as $value): ?>
            <?php
            $date_end = isset($value['date_end']) ? $value['date_end'] : '';
            $img = isset($value['img']) ? $value['img'] : '';
            $category = isset($value['category']) ? $value['category'] : '';
            $id = isset($value['id']) ? $value['id'] : '';
            $name = isset($value['name']) ? $value['name'] : '';
            $start_price = isset($value['start_price']) ? $value['start_price']
                : '';
            $expiration_date = get_date_range($date_end);
            $expiration_date_arr = explode(':', $expiration_date);
            ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($img) ?>"
                         width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span
                        class="lot__category"><?= htmlspecialchars($category) ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?id=<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($name) ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span
                                class="lot__cost"><?= htmlspecialchars(to_format_currency($start_price)) ?><b
                                    class="rub">р</b></span>
                        </div>

                        <div
                            class="lot__timer timer <?= (string)$expiration_date_arr[0]
                            === '00' ? 'timer--finishing'
                                : '' ?>"><?= $expiration_date ?></div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
