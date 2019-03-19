<?php
session_start();
$volr1["a"] = round(38.39, 2);
$volr1["b"] = round(11.88, 2);
$volr1["c"] = round(1.84, 2);
$volr1["d"] = round(4.91, 2);
$volr1["e"] = round(123.54, 2);
$volr1["f"] = round(0.97, 2);
$volr1["g"] = round(34.60, 2);
$volr1["h"] = round(21.33, 2);
$volr1["i"] = round(28.50, 2);
$volr1["j"] = round(12.97, 2);

$volr2["a"] = round(1.23, 2);
$volr2["b"] = round(43.23, 2);
$volr2["c"] = round(1.50, 2);
$volr2["d"] = round(0.35, 2);
$volr2["e"] = round(0.20, 2);
$volr2["f"] = round(42.32, 2);

$volr3["a"] = round(13.60, 2);
$volr3["b"] = round(51.00, 2);
$volr3["c"] = round(24.39, 2);
$volr3["d"] = round(2.91, 2);
$volr3["e"] = round(44.45, 2);

$volr4["a"] = round(53.00, 2);
$volr4["b"] = round(45.00, 2);
$volr4["c"] = round(23.00, 2);
$volr4["d"] = round(85.00, 2);
$volr4["e"] = round(11.00, 2);
$volr4["f"] = round(4.00, 2);
$volr4["g"] = round(34.00, 2);

$volr5["a"] = round(32.00, 2);
$volr5["b"] = round(55.00, 2);
$volr5["c"] = round(87.00, 2);
$volr5["d"] = round(23.00, 2);
$volr5["e"] = round(20.00, 2);
$volr5["f"] = round(26.00, 2);
$volr5["g"] = round(87.00, 2);

$hasil["Rute A"] = jumlah_volum($volr1, 28, 90);
$hasil["Rute B"] = jumlah_volum($volr2, 28, 90);
$hasil["Rute C"] = jumlah_volum($volr3, 28, 90);
$hasil["Rute D"] = jumlah_volum($volr4, 28, 90);
$hasil["Rute E"] = jumlah_volum($volr5, 28, 90);

//echo json_encode($hasil);

function jumlah_volum($vol, $truk_size, $efsensi) {
    $jum_volum = array_sum($vol);
    $efisiensi = ($efsensi / 100);
    $jum_trip = ceil(round(round($jum_volum / $efisiensi, 2) / $truk_size, 2));
    $hasil = pembagian_volum($vol, $jum_trip);
    $html_hasil["html"] = output_table($vol, $jum_trip, $hasil);
    $html_hasil["trip"] = $jum_trip;
    return $html_hasil;
}

function pembagian_volum($vol_sup, $trip) {
    $tampung = [];
    for ($i = 0; $i < $trip; $i++) {
        foreach ($vol_sup as $vsk => $vsv) {
            $tampung[$i + 1][$vsk] = round(($vsv / $trip), 1);
        }
    }
    return $tampung;
}

function output_table($vol, $trip, $hasil) {
    $output = '';
    $output .= '<table border="1">';
    $output .= '<tr>';
    $output .= '<th>No</th>';
    $output .= '<th>Supplier</th>';
    $output .= '<th>Volume/Day</th>';
    $output .= '<th>Trip</th>';
    for ($i = 1; $i <= $trip; $i++) {
        $output .= '<th>' . $i . '</th>';
    }
    $output .= '</tr>';

    $no = 1;
    foreach ($vol as $vlk => $vlv) {
        $output .= '<tr>';
        $output .= '<td>' . $no . '</td>';
        $output .= '<td>' . $vlk . '</td>';
        $output .= '<td>' . $vlv . '</td>';
        $output .= '<td> </td>';
        for ($i = 1; $i <= $trip; $i++) {
            $output .= '<td>' . $hasil[$i][$vlk] . '</td>';
        }
        $output .= '</tr>';
        $no++;
    }
    $output .= '<tr><td colspan=' . ($trip + 4) . '>Jumlah Trip : ' . $trip . '</td></tr>';


    $output .= '</table> ';

    return $output;
}
?>


<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="" />
        <meta name="description" content="" />
        <title>Hasil</title>
    </head>
    <body>
        <?php
        $trip = [];
        $parkir = ['Parkir 1', 'Parkir 2','Parkir 3'];



        foreach ($hasil as $hk => $hv) {
            echo "<h1>" . $hk . "</h1><br>";
            echo $hv["html"] . "<br>";
            $trip[$hk] = $hv["trip"];
        }

        function selectTimesOfDay($trip, $parkir, $hasil_rute) {
            $tahun = date('Y');
            $bulan = date('m');
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $open_time = strtotime("06-03-2019 07:30:00");
            $close_time = strtotime("07-03-2019 05:00:00");
            $output = "";
            $jam = [];
            for ($i = $open_time; $i < $close_time; $i += 2700) {
                if ($i <= strtotime("06-03-2019 15:45:00")) {
                    if (date("H:i", $i) != "12:00") {
                        $jam[] = date("H:i", $i);
                    }
                } else if ($i > strtotime("06-03-2019 15:45:00") && $i < strtotime("06-03-2019 21:00:00")) {
//                    $output .= "- <br>";
                } else if ($i >= strtotime("06-03-2019 21:00:00")) {
                    if (date("H:i", $i) != "00:00") {
                        $jam[] = date("H:i", $i);
                    }
                }
            }
            $tableHasil= jam_table($jam, "Pagi", $trip, $parkir, $hasil_rute);
//            $tableHasil["malam"] = jam_table($jam["malam"], "Malam", $trip, $parkir, $hasil_rute);
            return $tableHasil;
        }

        function jam_table($jam, $hari, $trip, $parkir, $hasil_rute) {
//            print_r(array_keys($hasil_rute[0])[0]);
            $tableJam = '<table border=1>';
            $tableJam .= '<tr><th>' . $hari . '</th>';
            foreach ($parkir as $pk => $pv) {
                $tableJam .= '<th>' . $pv . '</th>';
            }
            $tableJam .= '</tr>';
            $tableJam .= '<tr>';
            $no = 0;
            $noJam = 0;
            foreach ($hasil_rute as $hr) {
                if ($no == (count($parkir))) {
                    $tableJam .= '</tr>';
                    $no = 0;
                    $tableJam .= '<tr>';
                }
                if ($no == 0) {
                    $tableJam .= '<td>' . $jam[$noJam] . '</td>';
                    $noJam++;
                }
                $tableJam .= '<td>' . array_keys($hr)[0] . '</td>';
                $no++;
            }
            $tableJam .= '</tr>';
            $tableJam .= '</tabler>';
            return $tableJam;
        }

        // hejunka 
        $total_qty = total_qty($trip);

        $hasil_rute = pembagin_rute($trip, $total_qty);

        $jam = selectTimesOfDay($trip, $parkir, $hasil_rute);

        echo $jam;

        function pembagin_rute($trip, $qty) {
            $jumlah = '';
            $hasil = array();
            $group_arr = '';
            foreach ($trip as $pk2 => $pv2) {
                $jumlah[$pk2] = ($pv2 / $qty);
            }

            for ($i = 0; $i < $qty; $i++) {
                foreach ($jumlah as $k => $v) {
                    if (count(array_keys($hasil, $k)) > 0) {
                        $group_arr[$i][$k] = ($v * ($i + 1) - count(array_keys($hasil, $k)));
                    } else {
                        $group_arr[$i][$k] = ($v * ($i + 1));
                    }
                    arsort($group_arr[$i]);
                }
                array_push($hasil, nilai_hasil($group_arr[$i]));
            }
            return $group_arr;
        }

        function nilai_hasil($a) {
            $nilai = max($a);
            $area_antri = array_search($nilai, $a);
            return $area_antri;
        }

        function total_qty($arr) {
            $total = '';
            foreach ($arr as $r => $j) {
                $total = $total + $j;
            }
            return $total;
        }
        ?>

    </body>
</html>
