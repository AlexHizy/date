<?php

function generate_schedule($year, $month) {
    // Массив для дней недели
    $weekdays = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];

    // Получаем количество дней в месяце
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
    $first_day_of_month = strtotime("$year-$month-01");
    $first_day_weekday = date('w', $first_day_of_month); // 0 - воскресенье, 6 - суббота

    // Массив для расписания
    $schedule = [];
    // Переменные для рабочего дня
    $is_working_day = true;  // Первый день рабочий, если не выходной
    $day = 1;

    // Проходим по дням месяца
    while ($day <= $days_in_month) {
        // Проверяем, если день суббота или воскресенье, то он всегда выходной
        if ($first_day_weekday == 0 || $first_day_weekday == 6) {
            $schedule[] = ['day' => $day, 'weekday' => $weekdays[$first_day_weekday], 'working_day' => false];
            $day++;
            $first_day_weekday = ($first_day_weekday + 1) % 7;
            continue;
        }

        // Если текущий день рабочий
        if ($is_working_day) {
            $schedule[] = ['day' => $day, 'weekday' => $weekdays[$first_day_weekday], 'working_day' => true];
            $day++;
            $first_day_weekday = ($first_day_weekday + 1) % 7;

            // Если рабочий день был в четверг, то следующие три дня выходные
            if ($first_day_weekday == 4) { // Четверг
                for ($i = 0; $i < 3; $i++) {
                    if ($day > $days_in_month) break;
                    $schedule[] = ['day' => $day, 'weekday' => $weekdays[$first_day_weekday], 'working_day' => false];
                    $day++;
                    $first_day_weekday = ($first_day_weekday + 1) % 7;
                }
            } else {
                // Иначе два выходных дня
                for ($i = 0; $i < 2; $i++) {
                    if ($day > $days_in_month) break;
                    $schedule[] = ['day' => $day, 'weekday' => $weekdays[$first_day_weekday], 'working_day' => false];
                    $day++;
                    $first_day_weekday = ($first_day_weekday + 1) % 7;
                }
            }

            $is_working_day = false; // Следующий день не рабочий
        } else {
            // Если текущий день не рабочий, пропускаем его
            $is_working_day = true;
        }
    }

    // Выводим расписание
    echo "Расписание на $year-$month:\n";
    foreach ($schedule as $day_schedule) {
        $status = $day_schedule['working_day'] ? "\033[32m+ \033[0m" : "\033[31m- \033[0m";
        echo $status . " День: " . $day_schedule['day'] . " " . $day_schedule['weekday'] . "\n";
    }
}

// Задаем расписание
$year = 2025;
$month = 2;
generate_schedule($year, $month);

?>
