<?php
function generateCellValue()
{
    if (empty($_POST)) {
        return;
    }

    $address = $_POST["address"];

    // Split the address into letters and numbers
    preg_match('/([A-Z]+)([0-9]+)/', strtoupper($address), $matches);

    $columnLetters = $matches[1];
    $rowNumber = $matches[2];

    // Convert column letters into a number
    $columnNumber = 0;
    for ($i = 0; $i < strlen($columnLetters); $i++) {
        // Because a letter in the alphabet can be treated as a base26 number,
        // we can convert it into a base10 number.
        $columnNumber = $columnNumber * 26 + (ord($columnLetters[$i]) - ord('A') + 1);
    }

    echo "$columnNumber.$rowNumber";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komórki</title>
</head>

<body>
    <form method="post">
        <input type="text" name="address" id="address">
        <button type="submit">Wyświetl wartość komórki</button>
    </form>

    <?php
    generateCellValue();
    ?>
</body>

</html>