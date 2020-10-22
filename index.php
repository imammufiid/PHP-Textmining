<?php
require_once('ProcessText.php');

echo "<h2>Text Mining Metode TF-IDF</h2>";
echo "<br><br>";
$prosesText = new ProsesText();

// CASE FOLDING
$caseFolding = $prosesText->caseFolding();
if ($caseFolding == 1) {
    echo "> Case Folding berhasil";
} else {
    echo "> Case Folding gagal";
    die;
}

echo "<br>";

// TOKENISASI
if($prosesText->tokenitation() == 1) {
    echo "> Tokenitation berhasil";
} else {
    echo "> Tokenitation gagal";
    die;
}

echo "<br>";
// FILTERING
if($prosesText->filtering() == 1) {
    echo "> Filtering berhasil";
} else {
    echo "> Filtering gagal";
    die;
}


echo "<br>";
// STEMMING
$stemming = $prosesText->stem();
if($stemming == 1) {
    echo "> Stemming berhasil.";
} else {
    echo "> Stemming gagal.";
    die;
}



echo "<br>";
// HITUNG TF IDF
if ($prosesText->tesTFIDF() == 1) {
    echo "> Hitung IDF berhasil";
} else {
    echo "> Hitung IDF gagal";
}
die;
