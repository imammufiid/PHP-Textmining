<?php
class ProsesText
{
    function caseFolding()
    {
        include 'koneksi.php';
        mysqli_query($conn, "TRUNCATE case_folding");
        $query = mysqli_query($conn, "SELECT * FROM documents") or die(mysqli_error($conn));
        $masukkan1;
        foreach ($query as $key => $val) {
            $id_dok         = $val['doc_id'];
            $kalimat_asli   = $val['complaint'];
            $kode_disposisi = $val['code_disposition'];
            
            //rubah alfabet besar menjadi kecil
            $answerKey = strtolower($kalimat_asli);
            //hilangkan tanda baca
            $answerKey = str_replace("'", " ", $answerKey);
            $answerKey = str_replace("-", " ", $answerKey);
            $answerKey = str_replace(")", " ", $answerKey);
            $answerKey = str_replace("(", " ", $answerKey);
            $answerKey = str_replace("\"", " ", $answerKey);
            $answerKey = str_replace("/", " ", $answerKey);
            $answerKey = str_replace("=", " ", $answerKey);
            $answerKey = str_replace(".", " ", $answerKey);
            $answerKey = str_replace(",", " ", $answerKey);
            $answerKey = str_replace(":", " ", $answerKey);
            $answerKey = str_replace(";", " ", $answerKey);
            $answerKey = str_replace("!", " ", $answerKey);
            $answerKey = str_replace("?", " ", $answerKey);
            $answerKey = str_replace("`", " ", $answerKey);
            $answerKey = str_replace("~", " ", $answerKey);
            $answerKey = str_replace("@", " ", $answerKey);
            $answerKey = str_replace("#", " ", $answerKey);
            $answerKey = str_replace("$", " ", $answerKey);
            $answerKey = str_replace("%", " ", $answerKey);
            $answerKey = str_replace("^", " ", $answerKey);
            $answerKey = str_replace("&", " ", $answerKey);
            $answerKey = str_replace("*", " ", $answerKey);
            $answerKey = str_replace("_", " ", $answerKey);
            $answerKey = str_replace("+", " ", $answerKey);
            $answerKey = str_replace("[", " ", $answerKey);
            $answerKey = str_replace("]", " ", $answerKey);
            $answerKey = str_replace("<", " ", $answerKey);
            $answerKey = str_replace(">", " ", $answerKey);
            $masukan1 = mysqli_query($conn, "INSERT INTO case_folding VALUES('','$answerKey','$id_dok','$kode_disposisi')");
        }
        return $masukan1;
    }

    function tokenitation()
    {
        include 'koneksi.php';
        mysqli_query($conn, "TRUNCATE token"); //kosongkan isi field
        $query = mysqli_query($conn, "SELECT * FROM case_folding") or die(mysqli_error($conn));
        $id_dok = [];
        $masukkan2;
        while ($row = mysqli_fetch_array($query)) {
            $hasilToken = [];
            $kalimat_asli   = $row['case_folding'];
            $id_dok         = $row['doc_id'];
            $kode_disposisi = $row['code_disposition'];

            // menghitung jumlah dan memecah kata dalam kalimat
            $token = str_word_count(strtolower($kalimat_asli), 1);
            foreach ($token as $key => $val) {
                array_push($hasilToken, $val);
            }
            $serialize = json_encode($hasilToken);
            $masukkan2 = mysqli_query($conn, "INSERT INTO token VALUES('','$serialize','$id_dok', '$kode_disposisi')");
            unset($hasilToken);
        }
        return $masukkan2;
    }

    function filtering()
    {
        include 'koneksi.php';
        require_once('lib/Filtering.php');
        mysqli_query($conn, "TRUNCATE filtering");
        $test = new Filtering();
        $masukkan3;
        $query = mysqli_query($conn, "SELECT * FROM token") or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($query)) {
            $hasilFilter = [];
            $doc_id = $row['doc_id'];
            $kode_disposisi = $row['code_disposition'];

            foreach (json_decode($row['term']) as $key => $value) {
                $proses = $test->getToken($value, 9);
                array_push($hasilFilter, $proses);
            }

            $filterTerm = json_encode(array_values(array_filter($hasilFilter)));
            $masukkan3 = mysqli_query($conn, "INSERT INTO filtering VALUES('', '$filterTerm','$doc_id', '$kode_disposisi')");

            unset($hasilFilter);
        }
        return $masukkan3;
    }

    function stem()
    {
        include 'koneksi.php';
        include 'lib/Stemming.php';
        $masukkan4;
        mysqli_query($conn, "TRUNCATE stemming");
        $query = mysqli_query($conn, "SELECT * FROM filtering") or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($query)) {
            $hasilStem = [];
            $hasil;
            $kata             = $row['term'];
            $id_dok           = $row['doc_id'];
            $kode_disposisi   = $row['code_disposition'];

            foreach (json_decode($kata) as $key => $value) {
                $hasil = stemming($value); //proses stemming
                array_push($hasilStem, $hasil);
            }

            $result = json_encode($hasilStem);
            if ($hasil != "") { //jika hasil stemming tidak kosong maka masukkan
                $masukkan4 = mysqli_query($conn, "INSERT INTO stemming VALUES('','$result','$id_dok','$kode_disposisi')");
            }

            unset($hasilStem);
        }

        return $masukkan4;
    }


    function tesTFIDF()
    {
        include 'koneksi.php';
        $masukkan5;
        $masukkan6;
        mysqli_query($conn, "TRUNCATE tbindex");

        // HITUNG TF
        // contoh jawaban
        $queryAnswer = mysqli_query($conn, "SELECT * FROM stemming WHERE id=2") or die(mysqli_error($conn));
        // contoh semua jawaban
        $queryAnswerAll = mysqli_query($conn, "SELECT * FROM stemming ORDER BY id") or die(mysqli_error($conn));
        $banyakDocAnswer = mysqli_num_rows($queryAnswerAll);
        // contoh kunci jawaban
        $queryKey = mysqli_query($conn, "SELECT * FROM stemming WHERE id=1") or die(mysqli_error($conn));

        $resultAnswer = mysqli_fetch_assoc($queryAnswer);
        $answer = json_decode($resultAnswer['term']);  // array answer
        $resultKey = mysqli_fetch_assoc($queryKey);
        $answerKey = json_decode($resultKey['term']);  // array answer key

        $array_intersect = array_intersect($answer, $answerKey); // hasil kata setelah disamakan dengan kata kunci jawaban
        $countWordInDoc = array_count_values($array_intersect); // menghitung jumlah kata per dokumen

        // TES BANYAK DOKUMEN
        foreach ($queryAnswerAll as $key => $val) {
            $intersect = array_intersect(json_decode($val['term']), $answerKey);
            $countTermInDoc = array_count_values($intersect);
            $id_doc = $val['doc_id'];

            foreach ($countTermInDoc as $term => $count) {
                $masukan5 = mysqli_query($conn, "INSERT INTO tbindex (Term, DocId, Count) VALUES ('$term', '$id_doc', '$count')") or die(mysqli_error($conn));
            }
        }
        if ($masukan5 == 1) {
            echo "> Hitung TF berhasil";
        } else {
            echo "> Hitung TF gagal";
            die;
        }
        echo "<br>";

        // HITUNG IDF
        $resn = mysqli_query($conn, "SELECT DISTINCT DocId FROM tbindex WHERE DocId!= 1");
        $n = mysqli_num_rows($resn); // jumlah total pengaduan

        //hitung bobot untuk setiap Term dalam setiap DocId
        $resBobot = mysqli_query($conn, "SELECT * FROM tbindex WHERE DocId != 1 ORDER BY Id");
        $num_rows = mysqli_num_rows($resBobot);


        foreach ($resBobot as $key => $value) {
            $term = $value['Term'];
            $tf   = $value['Count'];
            $id   = $value['Id'];

            //berapa jumlah dokumen yang mengandung term tersebut?, N
            $resNTerm = mysqli_query($conn, "SELECT Count(*) as N FROM tbindex  WHERE Term = '$term' AND DocId != 1");
            $rowNTerm = mysqli_fetch_array($resNTerm);
            $NTerm    = $rowNTerm['N']; // nilai df

            //Hitung TF-IDF
            //$w = tf * log (n/df)
            $idf = log10($n / $NTerm);
            $w      = ($tf * ($idf + 1));
            $tf_idf = round($w, 4); //pembulatan 

            //update bobot dari term tersebut
            $masukan6 = mysqli_query($conn, "UPDATE tbindex SET Weight = $tf_idf WHERE Id = $id");
        }

        return $masukan6;
    }
}
