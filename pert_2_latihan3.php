<html>
<head>
    <title>Latihan 3 - Kalkulator</title>
    <style>
        body { font-family: "Times New Roman", serif; }
        .label { color: #8B0000; font-weight: bold; margin-left: 80px; }
        .container { margin-top: 20px; text-align: center; }
        input[type="number"] { padding: 3px; }
    </style>
</head>
<body>

<div class="container">
    <table align="center">
        <tr>
            <td class="label">Nilai I</td>
            <td class="label">Nilai II</td>
        </tr>
    </table>

    <form method="post" action="">
        <input type="number" step="any" name="nilai1" required>
        <select name="operator">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value=""></option>
            <option value="/">/</option>
        </select>
        <input type="number" step="any" name="nilai2" required>
        <input type="submit" name="submit" value="submit">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $nilai1 = $_POST['nilai1'];
        $nilai2 = $_POST['nilai2'];
        $operator = $_POST['operator'];
        $hasil = 0;

        if ($operator == "+") {
            $hasil = $nilai1 + $nilai2;
        } elseif ($operator == "-") {
            $hasil = $nilai1 - $nilai2;
        } elseif ($operator == "*") {
            $hasil = $nilai1 * $nilai2;
        } elseif ($operator == "/") {
            if ($nilai2 != 0) {
                $hasil = $nilai1 / $nilai2;
            } else {
                $hasil = "Tidak bisa dibagi 0";
            }
        }

        echo "<br><br><b>Hasil: $nilai1 $operator $nilai2 = $hasil</b>";
    }
    ?>
</div>

</body>
</html>