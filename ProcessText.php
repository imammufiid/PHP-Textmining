<?php

class ProcessText
{
    function index()
    {
        require_once('lib/core/TextPrepocessing.php');
        require_once('lib/Tfidf.php');
        include 'koneksi.php';

        echo "<h2>Text Mining Metode TF-IDF</h2>";
        echo "<br><br>";
        $textPrepocessing = new TextPrepocessing();
        $tfidf = new Tfidf();

        // CASE FOLDING
        mysqli_query($conn, "TRUNCATE case_folding");
        $queryCaseFolding = mysqli_query($conn, "SELECT * FROM documents") or die(mysqli_error($conn));

        foreach ($queryCaseFolding as $key => $val) {
            $id_dok         = $val['doc_id'];
            $kalimat_asli   = $val['complaint'];
            $kode_disposisi = $val['code_disposition'];

            $caseFolding = $textPrepocessing->caseFolding($kalimat_asli);
            $masukan1 = mysqli_query($conn, "INSERT INTO case_folding VALUES('','$caseFolding','$id_dok','$kode_disposisi')");
        }

        if ($masukan1 == 1) {
            echo "> Case Folding berhasil";
            echo "<br>";

            // TOKENISASI
            mysqli_query($conn, "TRUNCATE token"); //kosongkan isi field
            $queryTokenization = mysqli_query($conn, "SELECT * FROM case_folding") or die(mysqli_error($conn));

            while ($row = mysqli_fetch_array($queryTokenization)) {
                $hasilToken = [];
                $kalimat_asli   = $row['case_folding'];
                $id_dok         = $row['doc_id'];
                $kode_disposisi = $row['code_disposition'];

                $tokenization = $textPrepocessing->tokenization($kalimat_asli);
                $masukkan2 = mysqli_query($conn, "INSERT INTO token VALUES('','$tokenization','$id_dok', '$kode_disposisi')");
                unset($hasilToken);
            }

            if ($masukkan2 == 1) {
                echo "> Tokenitation berhasil";
                echo "<br>";

                // FILTERING
                mysqli_query($conn, "TRUNCATE filtering");
                $query = mysqli_query($conn, "SELECT * FROM token") or die(mysqli_error($conn));
                while ($row = mysqli_fetch_array($query)) {
                    $hasilFilter = [];
                    $doc_id = $row['doc_id'];
                    $kode_disposisi = $row['code_disposition'];

                    $filtering = $textPrepocessing->filtering($row['term']);
                    $masukkan3 = mysqli_query($conn, "INSERT INTO filtering VALUES('', '$filtering','$doc_id', '$kode_disposisi')");
                    unset($hasilFilter);
                }
                if ($masukkan3 == 1) {
                    echo "> Filtering berhasil";
                    echo "<br>";

                    // STEMMING
                    mysqli_query($conn, "TRUNCATE stemming");
                    $query = mysqli_query($conn, "SELECT * FROM filtering") or die(mysqli_error($conn));
                    while ($row = mysqli_fetch_array($query)) {
                        $hasilStem = [];
                        $hasil;
                        $kata             = $row['term'];
                        $id_dok           = $row['doc_id'];
                        $kode_disposisi   = $row['code_disposition'];

                        $stemming = $textPrepocessing->stemming($kata);
                        $masukkan4 = mysqli_query($conn, "INSERT INTO stemming VALUES('','$stemming','$id_dok','$kode_disposisi')");
                        unset($hasilStem);
                    }
                    if ($masukkan4 == 1) {
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
