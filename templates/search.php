<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу
            «<span><?= htmlspecialchars($search) ?></span>» (<?= $total ?>)</h2>

        <?php if ($total === 0): ?>
            <p>Ничего не найдено по вашему запросу</p>
        <?php else: ?>
            <ul class="lots__list">
                <? foreach ($result as $lot): ?>
                    <?php $expiration_date = get_dt_range($lot['date_end']); ?>
                    <li class="lots__item lot">

                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot['img']) ?>"
                                 width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span
                                class="lot__category"><?= htmlspecialchars($lot['category']) ?></span>
                            <h3 class="lot__title">
                                <a class="text-link"
                                   href="lot.php?id=<?= htmlspecialchars($lot['id']) ?>">
                                    <?= htmlspecialchars($lot['name']) ?>
                                </a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span
                                        class="lot__amount">Стартовая цена</span>
                                    <span
                                        class="lot__cost"><?= htmlspecialchars(to_format_currency($lot['start_price'])) ?><b
                                            class="rub">р</b></span>
                                </div>
                                <div
                                    class="lot__timer timer <?= $expiration_date[0]
                                    === '00' ? 'timer—finishing' : '' ?>">
                                    <?= $expiration_date[0].':'
                                    .$expiration_date[1] ?>
                                </div>
                            </div>
                        </div>

                    </li>
                <? endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <?php if ($count_pages > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="search.php?search=<?= $search ?>&page=<?= $current_page
                > 1 ? $current_page - 1 : 1 ?>">Назад</a>
            </li>
            <?php for ($i = 1; $i <= $count_pages; $i++): ?>
                <li class="pagination-item <?= $i === $current_page
                    ? 'pagination-item-active' : '' ?>">
                    <a href="search.php?search=<?= $search ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="pagination-item pagination-item-next">
                <a href="search.php?search=<?= $search ?>&page=<?= $current_page
                < $count_pages ? $current_page + 1 : $count_pages ?>">Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
</div>
