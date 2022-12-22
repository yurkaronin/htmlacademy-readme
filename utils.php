<?php

date_default_timezone_set('Europe/Moscow');

/**
 * Обрезает текст до заданного предела
 *
 * @param string $string Исходный текст в виде строки
 * @param number $max_post_length Максимальное количество символов
 * @return string Возвращает строку, обрезанную до $max_post_length, либо исходную строку без изменений,
 * если лимит символов не превышен
 */

function slice_string($string, $max_post_length = 300)
{
    $result_string = trim($string);

    if (mb_strlen($result_string) > $max_post_length) {
        $words = explode(' ', $result_string);
        $i = 0;
        $result_string = '';

        while (mb_strlen($result_string . ' ' . $words[$i]) < $max_post_length) {
            $result_string .= ' ' . $words[$i];
            $i++;
        }

        $result_string = '<p>' . trim(
                $result_string,
                '/ :–-,;'
            ) . '...</p><a class="post-text__more-link" href="#">Читать далее</a>';
        // trim нужен здесь, потому что пробел в начале параграфа добавляется на этапе цикла и trim в начале (равно как и в конце) не поможет;
        // знаки препинания я всё-таки убираю, потому что в задании как бы требуется, чтобы строка обрезалась именно по слову;
    }

    return $result_string;
}

//  Второй вариант функции

function slice_string_2($string, $max_post_length = 300)
{
    $result_string = trim($string);

    if (mb_strlen($result_string) > $max_post_length) {
        $temp_string = mb_substr($string, 0, $max_post_length);
        $result_string = '<p>' . mb_substr(
                $temp_string,
                0,
                mb_strripos($temp_string, ' ')
            ) . '...</p><a class="post-text__more-link" href="#">Читать далее</a>';
    }

    return $result_string;
}

/**
 * Заменяет потенциально опасные символы в являющемся строкой элементе на HTML-мнемоники, делая текст безопасным для вывода на страницу
 * @param mixed $value Входящий элемент любого типа
 */

function secure(&$value)
{
    if (is_string($value)) {
        $value = htmlspecialchars($value);
    }
        return $value;
}

/**
 * Преобразует дату в формат «дд.мм.гггг чч:мм», необходимый для атрибута title
 *
 * @param string $date Дата в виде строки
 * @return string Строка с датой в формате «дд.мм.гггг чч:мм»
 */

function get_title_date($date)
{
    $date = strtotime($date);
    return date('d.m.Y H:i', $date);
}

/**
 * Получает интервал времени с прошедшего до текущего момента времени в формате "n минут/часов/etc. назад"
 *
 * - если до текущего времени прошло меньше 60 минут, то формат будет вида «% минут назад»;
 * - если до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида «% часов назад»;
 * - если до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида «% дней назад»;
 * - если до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида «% недель назад»;
 * - если до текущего времени прошло больше 5 недель, то формат будет вида «% месяцев назад».
 * - если до текущего времени прошло больше 1 года, то формат будет вида «% лет назад».
 *
 * @param string $date Дата, с которой начинается отсчёт
 * @return string Строка, отражающая количество времени, прошедшего с $date
 */

function format_date($date)
{
    $post_date = date_create($date);
    $current_date = date_create('now');
    $diff = date_diff($current_date, $post_date); // Разница между current_date и post_date в виде объекта

    $result = '';

    if ($diff->invert === 0) {
        $result = 'Дата ещё не наступила!';
    } else {

        $minutes_in_hour = 60; // Кол-во минут в 1 часе
        $hours_in_day = 24; // Кол-во часов в 1 сутках;

        $minutes = $diff->i;
        $hours = $diff->h;
        $days = $diff->days;

        if ($days === 0) {
            if ($minutes >= $minutes_in_hour / 2) { // часы округляются вверх
                $hours++;
            }

            $result = $hours ?
                $hours . ' час' . get_noun_plural_form($hours, '', 'а', 'ов')
                :
                $minutes . ' минут' . get_noun_plural_form($minutes, 'у', 'ы', '');
        } else {

            $days_in_week = 7; // Кол-во дней в 1 неделе;
            $days_in_month = 30; // Кол-во дней в 1 месяце;
            $days_in_year = 365; // Кол-во дней в 1 году;
            $five_weeks = 35; // 5 недель;

            $years = $diff->y;

            if ($hours >= $hours_in_day / 2) { // дни округляются вверх
                $days++;
            }

            if ($days < $days_in_week) {
                $result = $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней');
            } elseif ($days < $five_weeks) {
                $weeks = round($days / $days_in_week);
                $result = $weeks . ' недел' . get_noun_plural_form($weeks, 'ю', 'и', 'ь');
            } elseif ($days < $days_in_year) {
                $months = round($days / $days_in_month);
                $result = $months . ' месяц' . get_noun_plural_form($months, '', 'а', 'ев');
            } elseif ($years) {
                $years = ($days >= $days_in_year / 2) ? $years++ : $years;
                $result = $years . ' ' . get_noun_plural_form($years, 'год', 'года', 'лет');
            }

            $result .= ' назад';
        }
    }
    return $result;
}

/**
 * Выполняет запрос типа SELECT в базу данных
 *
 * @param mysqli $src_db Переменная подключения к БД
 * @param string $query Переменная запроса
 * @return array Полученные данные из базы данных в виде массива
 */
function get_data_from_db(mysqli $src_db, string $query) {
    $result = mysqli_query($src_db, $query);

    if (!$result) {
        echo mysqli_error($src_db);
        exit();
    }

    $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $arr;
}
