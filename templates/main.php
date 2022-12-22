<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all filters__button--active" href="#">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php if ($content_types ?? false):
                              foreach ($content_types as $type): ?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $type['type_val']; ?> button" href="#">
                            <span class="visually-hidden"><?= $type['type_name']; ?></span>
                            <svg class="filters__icon" width="<?= $type['icon_width']; ?>" height="<?= $type['icon_height']; ?>">
                                <use xlink:href="#icon-filter-<?= $type['type_val']; ?>"></use>
                            </svg>
                        </a>
                    </li>
                    <?php
                              endforeach;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
        <?php if (isset($posts) && count($posts)): ?>
            <?php foreach ($posts as $post): ?>
            <article class="popular__post post post-<?= $post['type_val']; ?>">
                <header class="post__header">
                    <h2>
                        <!--здесь заголовок-->
                        <?= $post['header']; ?>
                    </h2>
                </header>
                <div class="post__main">
                <?php switch ($post['type_val']):
                        case 'quote': ?>
                <!--содержимое для поста-цитаты-->
                <blockquote>
                    <p>
                        <!--здесь текст-->
                        <?= slice_string($post['text_content']); ?>
                    </p>
                    <cite><?= $post['quote_origin'] ?></cite>
                </blockquote>
                        <?php break; ?>

                        <?php case 'text': ?>
                <!--содержимое для поста-текста-->
                <p>
                    <!--здесь текст-->
                    <?= slice_string($post['text_content']); ?>
                </p>
                        <?php break; ?>

                        <?php case 'photo': ?>
                <!--содержимое для поста-фото-->
                <div class="post-photo__image-wrapper">
                    <img src="img/<?= $post['photo_content']; ?>" alt="Фото от пользователя" width="360" height="240">
                </div>
                        <?php break; ?>

                        <?php case 'link': ?>
                <!--содержимое для поста-ссылки-->
                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://<?= $post['link_text_content']; ?>" title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                                <img src="https://www.google.com/s2/favicons?domain=<?= $post['link_text_content']; ?>" alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3>
                                    <!--здесь заголовок-->
                                    <?= $post['header']; ?>
                                </h3>
                            </div>
                        </div>
                        <span>
                            <!--здесь ссылка-->
                            <?= $post['link_text_content']; ?>
                        </span>
                    </a>
                </div>
                        <?php break; ?>

                        <?php case 'video': ?>
                <!--содержимое для поста-видео-->
                <div class="post-video__block">
                    <div class="post-video__preview">
                        <?= embed_youtube_cover(
                            /* вставьте ссылку на видео */
                             $post['video_content']
                        ); ?>
                        <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
                    </div>
                    <a href="post-details.html" class="post-video__play-big button">
                        <svg class="post-video__play-big-icon" width="14" height="14">
                            <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                    </a>
                </div>
                <?php
                        break;
                endswitch;
                ?>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                            <div class="post__avatar-wrapper">
                                <!--укажите путь к файлу аватара-->
                                <img class="post__author-avatar" src="img/<?= $post['avatar']; ?>" alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name">
                                    <!--здесь имя пользоателя-->
                                    <?= $post['user_name']; ?>
                                </b>
                                <?php $pd = $post['create_dt']; // alias для $post['date'] ?>
                                <time class="post__time" title="<?= get_title_date($pd); ?>" datetime="<?= $pd; ?>"><?= format_date($pd); ?></time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span>0</span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div>
                <p>Записей нет!</p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</section>
