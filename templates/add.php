<form
    class="form form--add-lot container <?= count($errors) > 0 ? 'form--invalid'
        : '' ?>" action="add.php"
    method="post" enctype="multipart/form-data" novalidate>
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['lot-name'])
            ? 'form__item--invalid'
            : '' ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input value="<?= getPostVal('lot-name') ?>" id="lot-name"
                   type="text" name="lot-name"
                   placeholder="Введите наименование лота" required
                   minlength="10">
            <span class="form__error"><?= isset($errors['lot-name'])
                    ? $errors['lot-name'] : '' ?></span>
        </div>

        <div class="form__item <?= isset($errors['category'])
            ? 'form__item--invalid'
            : '' ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category" required>
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <?php $id = isset($category['id'])
                        ? htmlspecialchars($category['id'])
                        : ''; ?>
                    <option <?= (getPostVal('category') === $id)
                        ? 'selected' : ''; ?> value="<?= $id; ?>">
                        <?= isset($category['name'])
                            ? htmlspecialchars($category['name'])
                            : ''; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= isset($errors['category'])
                    ? $errors['category'] : '' ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors['message'])
        ? 'form__item--invalid' : '' ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите описание лота" required
                  minlength="10"><?= getPostVal('message') ?></textarea>
        <span class="form__error"><?= isset($errors['message'])
                ? $errors['message'] : '' ?></span>
    </div>

    <div class="form__item form__item--file <?= isset($errors['lot-img'])
        ? 'form__item--invalid' : '' ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img"
                   name="lot-img" required>
            <label for="lot-img">
                Добавить
            </label>
            <span class="form__error"><?= isset($errors['lot-img'])
                    ? $errors['lot-img'] : '' ?></span>
        </div>
    </div>

    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($errors['lot-rate'])
            ? 'form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0"
                   required min="0" value="<?= getPostVal('lot-rate') ?>">
            <span class="form__error"><?= isset($errors['lot-rate'])
                    ? $errors['lot-rate'] : '' ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($errors['lot-step'])
            ? 'form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0"
                   required min="0" value="<?= getPostVal('lot-step') ?>">
            <span class="form__error"><?= isset($errors['lot-step'])
                    ? $errors['lot-step'] : '' ?></span>
        </div>
        <div class="form__item <?= isset($errors['lot-date'])
            ? 'form__item--invalid'
            : '' ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input
                class="form__input-date"
                id="lot-date"
                type="text"
                name="lot-date"
                placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                value="<?= getPostVal('lot-date') ?>"
                required>
            <span class="form__error"><?= isset($errors['lot-date'])
                    ? $errors['lot-date'] : '' ?></span>
        </div>
    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
