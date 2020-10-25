<?php
class TextPrepocessing
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
        require_once('lib/core/Filtering.php');
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

    function stemming()
    {
        include 'koneksi.php';
        include 'lib/core/Stemming.php';
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
}
