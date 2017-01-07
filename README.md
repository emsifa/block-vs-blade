Benchmark Block vs Blade
=======================================================

Benchmark [emsifa/block](https://github.com/emsifa/Block) dengan [illuminate/view](https://github.com/illuminate/view) (Laravel Blade).

Seperti yang saya sebutkan pada dokumentasi `emsifa/block`, library tersebut terinspirasi oleh blade, hanya saja versi nativenya, jadi tidak ada proses kompilasi dan caching.

Karena itu saya penasaran dengan performa library buatan saya tersebut. Untuk itu saya lakukan benchmarking.

Pada benchmarking ini blade yang digunakan adalah blade di laravel versi 5.3. Saya juga tidak menggunakan blade secara langsung, melainkan melalui library buatan saya juga, yakni [rakit/blade](https://github.com/rakit/blade). Tidak ada kode khusus pada rakit blade tersebut, rakit blade hanya mempermudah constructor-nya saja sehingga lebih mudah digunakan. Jadi tidak akan berpengaruh pada performa rendering asal laravel itu sendiri. Oia blade disini juga menggunakan caching karena blade pada Laravel-pun begitu.

Pada benchmark ini selain menggunakan beberapa file view, saya menggunakan 2 buah file lain yaitu `benchmark.php` dan `render.php`. Berikut penjelasan masing-masing filenya:

* `render.php`: file ini berisi kode yang akan merender file view.
* `benchmark.php`: file ini digunakan untuk menghitung waktu yang diperlukan untuk rendering masing-masing library. File ini akan menjalankan `render.php` melalui `shell_exec` untuk melakukan rendering. Jadi proses rendering tidak dilakukan pada proses yang sama dengan `benchmark.php` ini.

Untuk selengkapnya saya menaruh beberapa penjelasan pada masing-masing file tersebut.

## Spesifikasi Hardware dan Software

Saya sendiri menggunakan spesifikasi hardware dan software sebagai berikut dalam benchmarking ini:

* Processor: AMD A10
* RAM: 8GB
* PHP: 7.0.14
* OS: Ubuntu 14.04

## Hasil

Dan berikut hasilnya:

```
#1 Simplest view
- block : 4.694580078125
- blade : 5.5351309776306
  winner = block (15.19%)

#2 Simplest view with a var
- block : 4.6247391700745
- blade : 5.5427670478821
  winner = block (16.56%)

#3 Include another view
- block : 4.6605589389801
- blade : 5.544529914856
  winner = block (15.94%)

#4 Extending file
- block : 4.6460001468658
- blade : 5.5450959205627
  winner = block (16.21%)

#5 Extending and blocking
- block : 4.6508250236511
- blade : 5.6002271175385
  winner = block (16.95%)
```

> Masing-masing test diatas melakukan rendering sebanyak 100 kali

## Kesimpulan

Ternyata nggak jauh-jauh amat bedanya ya hahha :D

Jadi buat kamu yang menggunakan Laravel, saya rasa tidak perlu terlalu khawatir soal performa bladenya. Sedangkan buat kamu yang tidak menggunakan laravel, tapi mau menggunakan blade, kamu bisa pakai blade. Terserah mau setup sendiri atau gunakan `rakit/blade` seperti yang saya gunakan pada benchmark ini.

Untuk block sendiri dibandingkan dengan blade, kelebihan selain performa yg unggul sekitar 15-17% tersebut, block juga lebih mudah diinstall, dan jauh lebih ramping sizenya karena hanya menggunakan single file.

## Mau Benchmark Sendiri?

Kalau kamu mau benchmark sendiri di komputer kamu, caranya:

* Clone repository ini
* Buka terminal/cmd, masuk ke direktori hasil clone, ketikkan `composer install`.
* Kemudian jalankan benchmark dengan perintah `php benchmark.php`.