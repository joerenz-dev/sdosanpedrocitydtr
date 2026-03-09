<?php

$query = "
SELECT e.employee_name, a.date_record,
TIME(a.time_in) as time_in,
TIME(a.lunch_out) as lunch_out,
TIME(a.lunch_in) as lunch_in,
TIME(a.time_out) as time_out,
TIME(a.undertime) as undertime,
a.status,
GREATEST(
IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.time_in)), 0),
IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.lunch_out)), 0),
IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.lunch_in)), 0),
IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.time_out)), 0),
IFNULL(UNIX_TIMESTAMP(CONCAT(a.date_record, ' ', a.undertime)), 0)
) as latest_punch_time
FROM time_logs a
JOIN employees e ON a.employee_number = e.employee_number
WHERE a.date_record >= CURDATE() - INTERVAL 1 DAY
ORDER BY latest_punch_time DESC
LIMIT 25
";

$result = $conn->query($query);