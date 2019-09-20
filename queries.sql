/* Запрос на создание категорий */
INSERT INTO `category` (`name`, `code`) VALUES
    ('Доски и лыжи', 'boards'),
    ('Крепления', 'attachment'),
    ('Ботинки', 'boots'),
    ('Одежда', 'clothing'),
    ('Инструменты', 'tools'),
    ('Разное', 'other');

/* Запрос на создание лотов */
INSERT INTO `lot` (`date_create`, `name`, `category_id`, `start_price`, `bid_step`, `img`, `date_end`, `author_id`) VALUES
    (NOW(), '2014 Rossignol District Snowboard', 1, 10999, 200, 'img/lot-1.jpg', '2019-10-01 17:00:00', 1),
    (NOW(), 'DC Ply Mens 2016/2017 Snowboard', 1, 159999, 200, 'img/lot-2.jpg', '2019-10-01 17:00:00', 2),
    (NOW(), 'Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, 200, 'img/lot-3.jpg', '2019-10-01 17:00:00', 3),
    (NOW(), 'Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, 200, 'img/lot-4.jpg', '2019-10-01 17:00:00', 1),
    (NOW(), 'Куртка для сноуборда DC Mutiny Charocal', 4, 7500, 200, 'img/lot-5.jpg', '2019-10-01 17:00:00', 2),
    (NOW(), 'Маска Oakley Canopy', 6, 5400, 200, 'img/lot-6.jpg', '2019-10-01 17:00:00', 3);

/* Запрос на создание пользователей */
INSERT INTO `user` (`date_registration`, `email`, `name`, `password`) VALUES
    (NOW(), 'oleg@mail.ru', 'Oleg', '$2y$10$ifyRut8H/8T9hxwRKiA6H.VrErNyc2TGRuFqef6cTbjPgKVguAkNG'),
    (NOW(), 'ivan@mail.ru', 'Ivan', '$2y$10$ifyRut8H/8T9hxwRKiA6H.VrErNyc2TGRuFqef6cTbjPgKVguAkNG'),
    (NOW(), 'sergey@mail.ru', 'Sergey', '$2y$10$ifyRut8H/8T9hxwRKiA6H.VrErNyc2TGRuFqef6cTbjPgKVguAkNG');

/* Запрос на создание ставок */
INSERT INTO `wager` (`date`, `price`, `author_id`, `lot_id`) VALUES
    ('2019-09-20 01:00:00', 20000, 1, 1),
    ('2019-09-20 02:00:00', 21000, 2, 1),
    ('2019-09-20 03:00:00', 22000, 1, 1),
    ('2019-09-20 04:00:00', 23000, 3, 3);
