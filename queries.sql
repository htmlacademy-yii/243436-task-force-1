USE taskforce;

INSERT INTO categories
  (name, icon)
VALUES
  ('Переводы', 'translation'),
  ('Уборка', 'clean'),
  ('Переезды', 'cargo'),
  ('Компьютерная помощь', 'neo'),
  ('Ремонт квартирный', 'flat'),
  ('Ремонт техники', 'repair'),
  ('Красота', 'beauty'),
  ('Фото', 'photo');

INSERT INTO cities
  (city, lat, lon)
VALUES
  ('Абаза', 52.6517296, 90.0885929),
  ('Абакан', 53.7223661, 91.4437792);

INSERT INTO opinions
  (date_add, rate, description)
VALUES
  ('2019-08-19', 3, 'Cras non velit nec nisi vulputate nonummy. Maecenas tincidunt lacus at velit. Vivamus vel nulla
  eget eros elementum pellentesque.

  Quisque porta volutpat erat. Quisque erat eros, viverra eget, congue eget, semper rutrum, nulla. Nunc purus.

  Phasellus in felis. Donec semper sapien a libero. Nam dui.'),
  ('2019-02-22', 2, 'Fusce posuere felis sed lacus. Morbi sem mauris, laoreet ut, rhoncus aliquet, pulvinar sed, nisl.
  Nunc rhoncus dui vel sem.

  Sed sagittis. Nam congue, risus semper porta volutpat, quam pede lobortis ligula, sit amet eleifend pede libero quis
  orci. Nullam molestie nibh in lectus.

  Pellentesque at nulla. Suspendisse potenti. Cras in purus eu magna vulputate luctus.');

INSERT INTO profiles
  (city_id, birthday, about, phone, skype)
VALUES
  (1, '1989-11-11', 'In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat.',
  64574473047, 'high-level'),
  (2, '1989-03-05', '738 Hagan Lane', 75531015353, 'mobile');

INSERT INTO replies
  (date_add, rate, description)
VALUES
  ('2019-05-09', 1, 'Curabitur gravida nisi at nibh. In hac habitasse platea dictumst. Aliquam augue quam, sollicitudin
  vitae, consectetuer eget, rutrum at, lorem.

  Integer tincidunt ante vel ipsum. Praesent blandit lacinia erat. Vestibulum sed magna at nunc commodo placerat.

  Praesent blandit. Nam nulla. Integer pede justo, lacinia eget, tincidunt eget, tempus vel, pede.'),
  ('2018-10-27', 4, 'Fusce consequat. Nulla nisl. Nunc nisl.

  Duis bibendum, felis sed interdum venenatis, turpis enim blandit mi, in porttitor pede justo eu massa. Donec dapibus.
  Duis at velit eu est congue elementum.

  In hac habitasse platea dictumst. Morbi vestibulum, velit id pretium iaculis, diam erat fermentum justo, nec
  condimentum neque sapien placerat ante. Nulla justo.');

INSERT INTO users
  (date_add, email, name, password)
VALUES
  ('2019-08-10', 'kbuttress0@1und1.de', 'Karrie Buttress', 'JcfoKBYAB4k'),
  ('2018-12-21', 'baymer1@hp.com', 'Bob Aymer', 'ZEE54kg');

INSERT INTO tasks
  (date_add, category_id, path, description, expire, name, address, budget, city_id, lat, lon, user_id_create,
  status_id, user_id_executor, status)
VALUES
  ('2019-03-09', 2, 'img/cargo.png', 'Suspendisse potenti. In eleifend quam a odio. In hac habitasse platea dictumst.',
  '2019-11-15', 'enable impactful technologies', '1 Eagan Crossing', 6587, 1, 6.9641667, 158.2083333, 1, 2, 1,
  'В работе');

INSERT INTO users_and_categories
  (user_id, category_id)
VALUES
  (1, 1), (2, 2);

INSERT INTO messages
  (message, user_id_create, user_id_executor)
VALUES
  ('Тестовое сообщение', 1, 2), ('Hellow world', 2, 1);


