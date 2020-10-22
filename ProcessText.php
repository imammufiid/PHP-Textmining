<?php

class ProcessText
{
    function index()
    {
        require_once('lib/TextPrepocessing.php');
        require_once('lib/Tfidf.php');

        echo "<h2>Text Mining Metode TF-IDF</h2>";
        echo "<br><br>";
        $textPrepocessing = new TextPrepocessing();
        $tfidf = new Tfidf();

        // CASE FOLDING
        if ($textPrepocessing->caseFolding() == 1) {
            echo "> Case Folding berhasil";
            echo "<br>";
            // TOKENISASI
            if ($textPrepocessing->tokenitation() == 1) {
                echo "> Tokenitation berhasil";
                echo "<br>";
                // FILTERING
                if ($textPrepocessing->filtering() == 1) {
                    echo "> Filtering berhasil";
                    echo "<br>";
                    // STEMMING
                    $stemming = $textPrepocessing->stemming();
                    if ($stemming == 1) {
                        echo "> Stemming berhasil.";
                        echo "<br>";
                        // HITUNG TF
                        if ($tfidf->hitungTf() == 1) {
                            echo "> Hitung TF berhasil";
                            echo "<br>";
                            // HITUNG IDF
                            if ($tfidf->hitungIdf() == 1) {
                                echo "> Hitung IDF berhasil";
                                echo "<br>";
                            } else {
                                echo "> Hitung IDF gagal";
                                die;
                            }
                        } else {
                            echo "> Hitung TF gagal";
                            die;
                        }
                    } else {
                        echo "> Stemming gagal.";
                        die;
                    }
                } else {
                    echo "> Filtering gagal";
                    die;
                }
            } else {
                echo "> Tokenitation gagal";
                die;
            }
        } else {
            echo "> Case Folding gagal";
            die;
        }
    }
}
