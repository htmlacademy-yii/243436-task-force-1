<?php

function debug($data, $die = false) {
    echo "<pre>" . print_r($data, 1) . "</pre>";

    if ($die) {
        die();
    }
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел.
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case $mod100 >= 11 && $mod100 <= 20:
            return $many;

        case $mod10 > 5:
            return $many;

        case $mod10 === 1:
            return $one;

        case $mod10 >= 2 && $mod10 <= 4:
            return $two;

        default:
            return $many;
    }
}


/**
 * Время добавления задания или вреям посещения сайта.
 *
 * @param string $rate_time дата добавления задания или посещения сайта
 *
 * @return string возвращает время, которое прошло после добавления задания или посещения сайта
 */
function get_time_rate(string $rate_time)
{
    $time_public = '';
    $now_time = time();
    $past_time = strtotime($rate_time);
    $result_time_hour = floor(($now_time - $past_time) / 3600);
    $result_time_min = floor((($now_time - $past_time) % 3600) / 60);
    $result_time_sec = floor((($now_time - $past_time) % 3600) % 60);
    $result_all_second = $now_time - $past_time;

    if ((int)$result_time_hour === 0 && (int)$result_time_min === 0 && (int)$result_time_sec < 60) {
        $time_public = 'только что';
    } elseif ((int)$result_time_hour === 0 && (int)$result_time_min < 60 && (int)$result_time_min >= 1) {
        $time_public = $result_time_min . get_noun_plural_form(
            $result_time_min,
            ' минуту',
            ' минуты',
            ' минут',
            ' минута'
        ) . ' назад';
    } elseif ((int)$result_time_hour >= 1 && (int)$result_all_second < 7200) {
        $time_public = 'Час назад';
    } elseif ((int)$result_time_hour >= 2 && (int)$result_all_second < 86400) {
        $time_public = $result_time_hour . get_noun_plural_form(
            $result_time_hour,
            ' час',
            ' часа',
            ' часов'
        ) . ' назад';
    } elseif ((int)$result_all_second >= 86400) {
        $time_public = date('d.m.Y', $past_time) . ' в ' . date('H:i', $past_time);
    }

    return $time_public;
}

/**
 * Возвращает количество заданий.
 *
 * @param int $rate_task количество заданий
 */
function get_task_rate(int $rate_task)
{
        $rate_task .= get_noun_plural_form(
            $rate_task,
            ' задание',
            ' задания',
            ' заданий'
        );

    return $rate_task;
}

/**
 * Возвращает количество отзывов.
 *
 * @param int $rate_review количество отзывов
 */
function get_review_rate(int $rate_review)
{
        $rate_review .= get_noun_plural_form(
            $rate_review,
            ' отзыв',
            ' отзыва',
            ' отзывов'
        );

    return $rate_review;
}
