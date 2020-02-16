<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            height: 100%;
        }

        body {
            position: relative;
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 80%;
            height: 80%;

            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            margin: auto;
        }

        input {
            display: block;
        }

        .pass, .btn {
            padding: 10px;
            margin: 0 auto;
        }

        .pass {
            width: 90%;
        }

        .btn {
            border-radius: 5px;
            cursor: pointer;
            height: 50px;
            width: 50%;
        }

        h3 {
            display: block;
            text-align: center;
            margin: 30px;
        }

        table, th, td {
            padding: 10px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        table {
            margin: 0 auto;
            width: 80%;
        }

        td {
            text-align: center;
        }

    </style>
</head>
<body>

<?php

echo "
     <h3>Отчет по количеству голосов</h3>
";

?>

<?php

if (!isset($res)) {
    echo "
        <form method='POST'>
            <input name='password' type='password' class='pass' placeholder='Введите пароль' required>
            <br>
            <input type='submit' class='btn' value='Войти'>
        </form>
    ";

    exit;
}

echo "
    <table>
        <tr>
            <td>Название</td>
            <td>Количество голосов</td>
        </tr>
";


foreach ($res as $item) {
    echo "
        <tr>
            <td>{$item['name']}</td>
            <td>{$item['VoteCount']}</td>
         </tr>
    ";
}

echo "
    </table>
";
?>

</body>
</html>