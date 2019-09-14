<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое
        эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $item): ?>
            <li class="promo__item promo__item--<?= $item['code'] ?>">
                <a class="promo__link"
                   href="lots-by-category.php?category=<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Все лоты в категории: <?= $category_name ?></h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($announcement_list as $value): ?>
            <?php $expiration_date = get_dt_range($value['date_end']); ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= htmlspecialchars($value['img']) ?>"
                         width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span
                        class="lot__category"><?= htmlspecialchars($value['category']) ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?id=<?= htmlspecialchars($value['id']) ?>"><?= htmlspecialchars($value['name']) ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span
                                class="lot__cost"><?= htmlspecialchars(to_format_currency($value['start_price'])) ?><b
                                    class="rub">р</b></span>
                        </div>
                        <div class="lot__timer timer <?= $expiration_date[0]
                        === '00' ? 'timer—finishing'
                            : '' ?>"><?= $expiration_date[0].':'
                            .$expiration_date[1] ?></div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($count_pages > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="lots-by-category.php?category=<?= $category_id ?>&page=<?= $current_page
                > 1 ? $current_page - 1 : 1 ?>">Назад</a>
            </li>
            <?php for ($i = 1; $i <= $count_pages; $i++): ?>
                <li class="pagination-item <?= $i === $current_page
                    ? 'pagination-item-active' : '' ?>">
                    <a href="lots-by-category.php?category=<?= $category_id ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="pagination-item pagination-item-next">
                <a href="lots-by-category.php?category=<?= $category_id ?>&page=<?= $current_page
                < $count_pages ? $current_page + 1 : $count_pages ?>">Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
</section>