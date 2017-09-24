USE yeticave_181305;

<<<<<<< HEAD
/* Данные пользвоателей */
INSERT INTO users (email, passwordHash, name, avatarUrl, contacts, createdTime) VALUES
=======
/* Данные пользователей */
INSERT INTO users (email, passwordHash, name, url, contacts, createdTime) VALUES
>>>>>>> c1b6565aa1b836599979238e8365b7485a259690
    ('ignat.v@gmail.com','$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka','Игнат','img/user.jpg', '+7 924 568 75 45','2016-04-18 20:54:50'),
    ('kitty_93@li.ru','$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa','Леночка','img/user.jpg', '','2017-05-19 12:42:01'),
    ('warrior07@mail.ru','$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW','Руслан','img/user.jpg', 'i@mail.ru','2017-09-22 01:56:04');

/* Категории*/
INSERT INTO categories (name) VALUES 
    ('Все категории'),
    ('Доски и лыжи'),
    ('Крепления'),
    ('Ботинки'),
    ('Одежда'),
    ('Инструменты'),
    ('Разное');

/* Лоты */
<<<<<<< HEAD
INSERT INTO lots (name, cost, imgUrl, description, endTime, step, quantityBets, categoryId, authorId, winnerId, createdTime) VALUES
    ('2014 Rossignol District Snowboard', 10999, 'img/lot-1.jpg', 'Лучшая доска для сноуборда', '2017-09-21 12:00:00', 2000, 2, 2, 1, 2, '2017-09-02 15:42:01'),
    ('DC Ply Mens 2016/2017 Snowboard', 159999, 'img/lot-2.jpg', 'Эта доска - ваш билет в спорт', '2017-10-15 15:00:00', 5000, 5, 2, 1, NULL, '2017-09-01 11:28:12'),
    ('Крепления Union Contact Pro 2015 года размер L/XL', 8000, 'img/lot-3.jpg', 'Эти крепления обеспечат вашу безопасность всегда и везде', '2017-09-18 01:00:00', 1000, 5, 3, 1, 3, '2017-09-05 16:01:55'),
    ('Ботинки для сноуборда DC Mutiny Charocal', 10999, 'img/lot-4.jpg', 'Ботинки не только крепкие, но и теплые', '2017-09-16 13:30:00', 500, 2, 4, 2, 1, '2017-09-01 18:06:54'),
    ('Куртка для сноуборда DC Mutiny Charocal', 8000, 'img/lot-5.jpg', 'Стильная курта всегда подчеркнет ваши навыки сноубординга', '2017-10-12 03:30:00', 300, 4, 5, 3, NULL, '2017-09-21 23:01:35'),
    ('Маска Oakley Canopy', 5400, 'img/lot-6.jpg', 'Не только защищает от света и снега, но и нравятся девушкам', '2017-10-13 04:15:00', 200, 3, 7, 3, NULL, '2017-09-19 12:15:55');
=======
INSERT INTO lots (name, cost, url, description, endTime, step, quantityBets, categoryId, authorId, winnerId, createdTime) VALUES
    ('2014 Rossignol District Snowboard', 10999, 'img/lot-1.jpg', 'Лучшая доска для сноуборда', '2017-10-21 12:00:00', 2000, 2, 2, 1, 2, '2017-09-02 15:42:01'),
    ('DC Ply Mens 2016/2017 Snowboard', 159999, 'img/lot-2.jpg', 'Эта доска - ваш билет в спорт', '2017-10-15 15:00:00', 5000, 1, 2, 1, NULL, '2017-09-01 11:28:12'),
    ('Крепления Union Contact Pro 2015 года размер L/XL', 8000, 'img/lot-3.jpg', 'Эти крепления обеспечат вашу безопасность всегда и везде', '2017-10-18 01:00:00', 1000, 0, 3, 1, 3, '2017-09-05 16:01:55'),
    ('Ботинки для сноуборда DC Mutiny Charocal', 10999, 'img/lot-4.jpg', 'Ботинки не только крепкие, но и теплые', '2017-10-16 13:30:00', 500, 0, 4, 2, 1, '2017-09-01 18:06:54'),
    ('Куртка для сноуборда DC Mutiny Charocal', 8000, 'img/lot-5.jpg', 'Стильная курта всегда подчеркнет ваши навыки сноубординга', '2017-10-12 03:30:00', 300, 0, 5, 3, NULL, '2017-09-21 23:01:35'),
    ('Маска Oakley Canopy', 5400, 'img/lot-6.jpg', 'Не только защищает от света и снега, но и нравятся девушкам', '2017-10-13 04:15:00', 200, 0, 7, 3, NULL, '2017-09-19 12:15:55');
>>>>>>> c1b6565aa1b836599979238e8365b7485a259690

/* Ставки */
INSERT INTO bets (createdTime, endTime, cost, userId, lotId) VALUES
    ('2017-09-06 16:42:01', '2017-10-01 12:00:00', 14999, 2, 1),
    ('2017-09-07 19:52:41', '2017-10-01 12:00:00', 16999, 3, 1),
    ('2017-09-06 16:42:01', '2017-10-15 15:00:00', 164999, 1, 2);

/* Список категорий */
SELECT * FROM categories ORDER BY id;

/* Самые новые открытые лоты */
SELECT lots.name, lots.cost, lots.url, lots.step, lots.quantityBets, categories.name  FROM lots , categories 
WHERE lots.endTime > (SELECT now()) AND lots.categoryId = categories.id;

/* Лот по названию и описанию*/
SELECT * FROM lots WHERE lots.name LIKE 'DC Ply Mens 2016/2017 Snowboard' or lots.description LIKE 'Эта доска - ваш билет в спорт';

/* Обновляет название лота по его идентификатору */
UPDATE lots SET lots.name = 'Маска Oakley Canopy Canada' WHERE lots.id = 6;

/* Получает список самых свежих ставок для лота по его идентификатору */
SELECT lots.name, bets.createdTime, bets.cost FROM bets, lots WHERE bets.createdTime > '2017-09-01 00:00:00' AND bets.lotId = lots.id ORDER BY bets.createdTime DESC;