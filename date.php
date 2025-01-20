<?php
function generate_schedule($year, $month, $num_months = 1) {
    // Название месяца
    $month_name = date('F', mktime(0, 0, 0, $month, 1, $year));

    echo "Расписание на $month_name $year:\n\n";

    // Массив для дней недели
    $weekdays = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];

    // Проходим по каждому месяцу
    for ($i = 0; $i < $num_months; $i++) {
        // Получаем количество дней в текущем месяце
        $days_in_month = date('t', mktime(0, 0, 0, $month + $i, 1, $year));
        // Дата первого дня месяца
        $first_day_of_month = strtotime("$year-" . ($month + $i) . "-01");
        // Получаем день недели первого числа месяца (0 - воскресенье, 1 - понедельник и т.д.)
        $first_day_weekday = date('w', $first_day_of_month);
        // Массив для хранения расписания
        $schedule = [];
        // Счётчик для работы по графику "сутки через двое"
        $work_day_counter = 0;

        // Расписание для каждого дня месяца
        for ($day = 1; $day <= $days_in_month; $day++) {
            // Проверяем, является ли день выходным
            $is_weekend = ($first_day_weekday == 0 || $first_day_weekday == 6);
            // Если день выходной, суббота или воскресенье, то это не рабочий день
            if ($is_weekend) {
                $working_day = false;
            } else {
                // Применяем к этому правило «сутки через двое»
                if ($work_day_counter % 3 == 0) {
                    $working_day = true;
                } else {
                    $working_day = false;
                }
                $work_day_counter++;  // Увеличиваем счётчик рабочих дней
            }

            // Если рабочий день выпадает на выходные, переносим его на понедельник
            if ($working_day && $is_weekend) {
                $working_day = false; // Выходной день
                // Переносим рабочий день на понедельник
            }

            // Тут добавлеяем информацию о текущем дне в расписание
            $schedule[] = [
                'day' => $day,
                'weekday' => $weekdays[$first_day_weekday],
                'working_day' => $working_day
            ];

            // Следующий день недели
            $first_day_weekday = ($first_day_weekday + 1) % 7;
        }

        // Вывод
        foreach ($schedule as $day_schedule) {
            $status = $day_schedule['working_day'] ? "\033[32m+ \033[0m" : "\033[31m- \033[0m";
            echo $status . " День: " . $day_schedule['day'] . " " . $day_schedule['weekday'] . "\n";
        }

        echo "\n";
    }
}

$year = 2025;
$month = 1;
generate_schedule($year, $month); 