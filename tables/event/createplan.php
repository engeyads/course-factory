<?php 
function generate_schedule($course, $city, $repeat, $weekStart, $endWeeks) {
    // Generate all Sundays or Mondays in the next specified weeks
    $start = new DateTime('next ' . $weekStart);  // Next Sunday or Monday
    $end = new DateTime('+' . $endWeeks . ' weeks');  // specified weeks from now
    $interval = new DateInterval('P1W');  // 1 week interval
    $period = new DatePeriod($start, $interval, $end);
    $weekDays = iterator_to_array($period);
    // Split the weekdays equally based on repeat times
    $chunks = array_chunk($weekDays, ceil(count($weekDays) / $repeat));
    // Generate the schedule
    $schedule = [];
    foreach ($chunks as $i => $chunk) {
        shuffle($chunk); // randomize weekdays within chunk
        foreach ($chunk as $j => $weekDay) {
            $schedule[] = [$weekDay->format('Y-m-d'), $city, $course];
        }
    }
    return $schedule;
}
$course = "Course 1";
$city = "City 1";
$repeat = 4;
$weekStart = 'monday';  // Change this value to 'sunday' or 'monday' as per your requirement
$endWeeks = 52;  // Change this to the number of weeks you want the schedule to last
$schedule = generate_schedule($course, $city, $repeat, $weekStart, $endWeeks);
foreach ($schedule as $event) {
    list($date, $city, $course) = $event;
    echo $date . " " . $city . " " . $course . "\n";
}
?>