/* Запрос на создание категорий */
INSERT INTO `category` (`name`, `code`) VALUES
    ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

/* Запрос на создание лотов */
INSERT INTO `lot` (`date_create`, `name`, `category_id`, `start_price`, `img`, `date_end`, `author_id`) VALUES
    (NOW(), '2014 Rossignol District Snowboard', 1, 10999, 'img/lot-1.jpg', '2019-10-01 17:00:00', 1),
    (NOW(), 'DC Ply Mens 2016/2017 Snowboard', 1, 159999, 'img/lot-2.jpg', '2019-10-01 17:00:00', 2),
    (NOW(), 'Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 'img/lot-3.jpg', '2019-10-01 17:00:00', 3),
    (NOW(), 'Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 'img/lot-4.jpg', '2019-10-01 17:00:00', 1),
    (NOW(), 'Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 'img/lot-5.jpg', '2019-10-01 17:00:00', 2),
    (NOW(), 'Маска Oakley Canopy', 6, 5400, 'img/lot-6.jpg', '2019-10-01 17:00:00', 3);

/* Запрос на создание пользователей */
INSERT INTO `user` (`date_registration`, `email`, `name`, `password`) VALUES
    (NOW(), 'oleg@mail.ru', 'Oleg', '123456'),
    (NOW(), 'ivan@mail.ru', 'Ivan', '123456'),
    (NOW(), 'sergey@mail.ru', 'Sergey', '123456');

/* Запрос на создание ставок */
INSERT INTO `wager` (`date`, `price`, `author_id`, `lot_id`) VALUES
    (NOW(), 20000, 1, 1),
    (NOW(), 21000, 2, 1),
    (NOW(), 22000, 1, 1),
    (NOW(), 23000, 3, 3);

/* Запрос на получение всех категорий */
SELECT * FROM `category`;

/* Запрос на получение новых, открытых лотов */
SELECT l.name, l.start_price, l.img, c.name, w.price
    FROM `lot` `l`
    LEFT JOIN `category` `c` ON c.id = l.category_id
    LEFT JOIN `wager` `w` ON w.lot_id = l.id
    WHERE l.date_end > NOW()
    ORDER BY l.date_create DESC;

/* Запрос на получение лота по id */
SELECT * FROM `lot` WHERE id = 1;

/* Запрос на обновление названия лота по id  */
UPDATE `lot` SET `name` = 'new lot name' WHERE id = 1;

/* Запрос на получение ставок для лота по его id с сортировкой по дате */
SELECT * FROM `wager` WHERE `lot_id` = 1 ORDER BY `date` DESC;
