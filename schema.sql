CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE user
(
  id        INT AUTO_INCREMENT PRIMARY KEY,
  create_dt DATETIME DEFAULT CURRENT_TIMESTAMP,
  email     VARCHAR(128) NOT NULL UNIQUE,
  user_name VARCHAR(64)  NOT NULL COMMENT 'имя пользователя',
  password  CHAR(255)    NOT NULL,
  avatar    VARCHAR(255)
) COMMENT 'таблица зарегистрированных пользователей';

CREATE TABLE content_type
(
  id        TINYINT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(20) UNIQUE COMMENT 'название типа',
  type_val  VARCHAR(16) UNIQUE COMMENT 'класс иконки типа',
  icon_width TINYINT COMMENT 'ширина иконки',
  icon_height TINYINT COMMENT 'высота иконки'
) COMMENT 'типы постов';

CREATE TABLE hashtag
(
  id           INT AUTO_INCREMENT PRIMARY KEY,
  hashtag_name VARCHAR(20) UNIQUE
) COMMENT 'таблица хэштегов';

CREATE TABLE post
(
  id              INT AUTO_INCREMENT PRIMARY KEY,
  create_dt       DATETIME DEFAULT CURRENT_TIMESTAMP,
  header          VARCHAR(128) COMMENT 'заголовок поста',
  text_content    TEXT,
  quote_origin    VARCHAR(128) COMMENT 'автор/источник цитаты',
  photo_content         VARCHAR(255),
  video_content           VARCHAR(255) COMMENT 'ссылка на видео на YouTube',
  link_text_content            VARCHAR(255) COMMENT 'ссылка на сторонний ресурс',
  view_count      INT      DEFAULT 0 COMMENT 'кол-во просмотров',
  user_id         INT,
  is_repost       TINYINT  DEFAULT 0 COMMENT 'является ли репостом',
  origin_post_id  INT COMMENT 'ссылка на оригинальный пост при репосте',
  content_type_id TINYINT,
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (content_type_id) REFERENCES content_type (id),
  INDEX (user_id) COMMENT 'индекс для поиска по пользователям',
  INDEX (content_type_id) COMMENT 'индекс для поиска по типу',
  INDEX (view_count) COMMENT 'индекс для поиска по просмотрам',
  INDEX (is_repost) COMMENT 'индекс для поиска по репостам'
) COMMENT 'таблица постов';

CREATE TABLE post_hashtag_link
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  post_id    INT,
  hashtag_id INT,
  FOREIGN KEY (post_id) REFERENCES post (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtag (id),
  UNIQUE KEY (post_id, hashtag_id),
  INDEX (post_id) COMMENT 'индекс для поиска по постам',
  INDEX (hashtag_id) COMMENT 'индекс для поиска по хэштегам'
) COMMENT 'связка хэштегов и постов';

CREATE TABLE comment
(
  id              INT AUTO_INCREMENT PRIMARY KEY,
  create_dt       DATETIME DEFAULT CURRENT_TIMESTAMP,
  comment_content TEXT,
  user_id         INT,
  post_id         INT,
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (post_id) REFERENCES post (id),
  INDEX (user_id) COMMENT 'индекс для поиска по пользователям',
  INDEX (post_id) COMMENT 'индекс для поиска по постам'
) COMMENT 'таблица комментариев к постам';

CREATE TABLE fav_list
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT COMMENT 'кто лайкает',
  post_id INT COMMENT 'что лайкают',
  FOREIGN KEY (user_id) REFERENCES user (id),
  FOREIGN KEY (post_id) REFERENCES post (id),
  UNIQUE KEY (user_id, post_id),
  INDEX (user_id) COMMENT 'индекс для поиска по постам',
  INDEX (post_id) COMMENT 'индекс для поиска по лайкам'
) COMMENT 'таблица лайков';

CREATE TABLE follower_list
(
  id                INT AUTO_INCREMENT PRIMARY KEY,
  following_user_id INT COMMENT 'кто подписывается',
  followed_user_id  INT COMMENT 'на кого подписывается',
  FOREIGN KEY (following_user_id) REFERENCES user (id),
  FOREIGN KEY (followed_user_id) REFERENCES user (id),
  UNIQUE KEY (followed_user_id, following_user_id) COMMENT 'уникальные пары пользователь-подписчик',
  INDEX (following_user_id) COMMENT 'индекс для поиска по подписчикам',
  INDEX (followed_user_id) COMMENT 'индекс для поиска по подпискам'
) COMMENT 'таблица подписок на пользователей';

CREATE TABLE message
(
  id              INT AUTO_INCREMENT PRIMARY KEY,
  create_dt       DATETIME DEFAULT CURRENT_TIMESTAMP,
  message_content TEXT,
  sender_id       INT COMMENT 'отправитель',
  receiver_id     INT COMMENT 'получатель',
  FOREIGN KEY (sender_id) REFERENCES user (id),
  FOREIGN KEY (receiver_id) REFERENCES user (id),
  INDEX (sender_id) COMMENT 'индекс для поиска по отправителю',
  INDEX (receiver_id) COMMENT 'индекс для поиска по получателю'
) COMMENT 'таблица личных сообщений';
