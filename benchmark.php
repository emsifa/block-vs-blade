<?php

error_reporting(E_ALL);

// Fungsi menghitung waktu yg dibutuhkan
// untuk mengeksekusi $code sebanyak $count
// ===========================================================================
// $code disini adalah Closure yg berisi 
// perintah untuk rendering file view
function benchmark(Closure $code, $count) {
    $start = microtime(true);
    for($i = 0; $i < $count; $i++) {
        $code();
    }
    $end = microtime(true);
    return $end - $start;
}

// Fungsi untuk menampilkan yg lebih cepat antara 2 lib beserta persentasenya
// ===========================================================================
// $a dan $b = array[name,timelapse]
function winner($a, $b) {
    $diff = abs($a['timelapse'] - $b['timelapse']);
    if ($a['timelapse'] < $b['timelapse']) {
        $winner = [
            'name' => $a['name'],
            'percentage' => number_format(abs(($diff/$b['timelapse']) * 100), 2)
        ];
    } else {
        $winner = [
            'name' => $b['name'],
            'percentage' => number_format(abs(($diff/$a['timelapse']) * 100), 2)
        ];
    }
    return $winner;
}

// Data yg akan di test
// ===========================================================================
// view = nama file view yg akan di render
// data = data yg akan di passing ke view
// desc = deskripsi yg akan ditampilkan pada hasil
$tests = [
    [
        'view' => 'simple',
        'data' => [],
        'desc' => 'Simplest view'
    ],
    [
        'view' => 'variable',
        'data' => ['message' => 'Hello world'],
        'desc' => 'Simplest view with a var'
    ],
    [
        'view' => 'include',
        'data' => [],
        'desc' => 'Include another view'
    ],
    [
        'view' => 'extending',
        'data' => ['title' => 'Lorem Ipsum', 'message' => 'Lorem ipsum dolor sit amet'],
        'desc' => 'Extending file'
    ],
    [
        'view' => 'extending-and-blocking',
        'data' => ['title' => 'Lorem Ipsum', 'message' => 'Lorem ipsum dolor sit amet'],
        'desc' => 'Extending and blocking'
    ],
];

// Benchmarking
// ===========================================================================
// karena API untuk rendering sama, yaitu `$lib->render($view, $data)`
// jadi kita satukan ke array aja biar lebih hemat code
$libs = ['block', 'blade'];
// jumlah rendering untuk setiap test
$count = 100;

foreach ($tests as $i => $test) {
    echo PHP_EOL;
    echo '#'.($i+1).' '.$test['desc'].PHP_EOL;
    $results = []; // untuk menampung hasil yg di test ini
    foreach ($libs as $libName) {
        echo '- '.$libName.' : ';
        $timelapse = benchmark(function() use ($test, $libName) {
            // jalankan rendering di proses yg berbeda
            // karena pada implementasinya, setiap kali user request ke server
            // proses yg dijalankan tiap request adalah proses berbeda
            return shell_exec(implode(' ', [
                'php', // command name
                'render.php', // renderer file
                $libName, // library name
                $test['view'], // view filename
                base64_encode(serialize($test['data'])), // view data
            ]));
        }, $count);
        echo $timelapse.PHP_EOL;
        $results[$libName] = [
            'name' => $libName,
            'timelapse' => $timelapse
        ];
    }
    $winner = winner($results['blade'], $results['block']);
    echo '  winner = '.$winner['name'].' ('.$winner['percentage'].'%)';
    echo PHP_EOL;
}
