<?php
$monthNames = [
    1 => 'Styczeń',
    'Luty',
    'Marzec',
    'Kwiecień',
    'Maj',
    'Czerwiec',
    'Lipiec',
    'Sierpień',
    'Wrzesień',
    'Październik',
    'Listopad',
    'Grudzień'
];


function determineMonthAndYear()
{
    if (empty($_POST)) {
        $month = date("m");
        $year = date("Y");
    } else {
        $month = $_POST["month"];
        $year = $_POST["year"];
    }

    return [$month, $year];
}

function generateHeading()
{
    global $monthNames;

    [$month, $year] = determineMonthAndYear();

    echo "$monthNames[$month] $year";
}

function generateMonthOptions()
{
    global $monthNames;

    [$month] = determineMonthAndYear();

    for ($i = 1; $i <= 12; $i++) {
        if ($i == $month) {
            echo "<option value=$i selected>$monthNames[$i]</option>";
        } else {
            echo "<option value=$i>$monthNames[$i]</option>";
        }
    }
}

function generateYearOptions()
{
    [$_, $year] = determineMonthAndYear();

    for ($i = 1970; $i <= 2024; $i++) {
        if ($i == $year) {
            echo "<option value=$i selected>$i</option>";
        } else {
            echo "<option value=$i>$i</option>";
        }
    }
}

function generateCalendar()
{
    global $monthNames;

    [$month, $year] = determineMonthAndYear();

    $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
    $dayCount = date('t', strtotime("$year-$month-01"));


    $html = "<tr>";

    // Fill out the days before the first day of the month with blank cells
    for ($i = 1; $i < $firstDayOfMonth; $i++) {
        $html .= "<td></td>";
    }

    // Filling the calendar with the month days
    for ($day = 1; $day <= $dayCount; $day++) {
        $color = "";
        // Make the text color red if it's Sunday
        if ($firstDayOfMonth % 7 == 0) {
            $color = "red";
        }

        $html .= "<td class='$color'>$day</td>";

        // Create a new row after Sunday
        if ($firstDayOfMonth % 7 == 0) {
            $html .= "</tr><tr>";
        }
        $firstDayOfMonth++;
    }

    // Fill the rest of the last row with blank cells
    while ($firstDayOfMonth % 7 != 1) {
        $html .= "<td></td>";
        $firstDayOfMonth++;
    }

    $html .= "</tr>";

    echo $html;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendarz</title>
    <style>
        .red {
            color: red;
        }

        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>
        <?php
        generateHeading();
        ?>
    </h1>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr>
            <th>Pn</th>
            <th>Wt</th>
            <th>Śr</th>
            <th>Cz</th>
            <th>Pt</th>
            <th>Sb</th>
            <th class="red">Nd</th>
        </tr>
        <?php
        generateCalendar();
        ?>
    </table>

    <br>

    <form method="post">
        <select name="month" id="month" onchange="this.form.submit()">
            <?php
            generateMonthOptions();
            ?>
        </select>

        <select name="year" id="year" onchange="this.form.submit()">
            <?php
            generateYearOptions();
            ?>
        </select>
    </form>
</body>

</html>