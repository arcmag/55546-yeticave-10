<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу
            «<span><?= htmlspecialchars($search) ?></span>» (<?= $total ?>)</h2>

        <?php if ($total === 0): ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php else: ?>
            <ul class="lots__list">
                <?php foreach ($result as $lot): ?>
                    <?php
                    $lot_date_end = isset($lot['date_end']) ? $lot['date_end']
                        : '';
                    $lot_img = isset($lot['img']) ? $lot['img'] : '';
                    $lot_category = isset($lot['category']) ? $lot['category']
                        : '';
                    $lot_id = isset($lot['id']) ? $lot['id'] : '';
                    $lot_name = isset($lot['name']) ? $lot['name'] : '';
                    $lot_start_price = isset($lot['start_price'])
                        ? $lot['start_price'] : '';

                    $expiration_date = get_date_range($lot_date_end);
                    $expiration_date_arr = explode(':', $expiration_date);
                    ?>
                    <li class="lots__item lot">

                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot_img) ?>"
                                 width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span
                                class="lot__category"><?= htmlspecialchars($lot_category) ?></span>
                            <h3 class="lot__title">
                                <a class="text-link"
                                   href="lot.php?id=<?= htmlspecialchars($lot_id) ?>">
                                    <?= htmlspecialchars($lot_name) ?>
                                </a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span
                                        class="lot__amount">Стартовая цена</span>
                                    <span
                                        class="lot__cost"><?= htmlspecialchars(to_format_currency($lot_start_price)) ?><b
                                            class="rub">р</b></span>
                                </div>
                                <div
                                    class="lot__timer timer <?= $expiration_date_arr[0]
                                    === '00' ? 'timer--finishing' : '' ?>">
                                    <?= $expiration_date ?>
                                </div>
                            </div>
                        </div>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <?php if ($count_pages > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="search.php?search=<?= $search ?>&page=<?= $current_page
                > 1 ? htmlspecialchars($current_page - 1) : 1 ?>">Назад</a>
            </li>
            <?php for ($i = 1; $i <= $count_pages; $i++): ?>
                <li class="pagination-item <?= $i === $current_page
                    ? 'pagination-item-active' : '' ?>">
                    <a href="search.php?search=<?= htmlspecialchars($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="pagination-item pagination-item-next">
                <a href="search.php?search=<?= htmlspecialchars($search) ?>&page=<?= $current_page
                < $count_pages ? htmlspecialchars($current_page + 1)
                    : $count_pages ?>">Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
</div>
