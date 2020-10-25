<?php

class Tfidf {

    function index() {
        echo "Algoritma TF IDF";
    }

    function hitungTf() {
        include 'koneksi.php';
        mysqli_query($conn, "TRUNCATE tbindex");

        // HITUNG TF
        // contoh jawaban
        // $queryAnswer = mysqli_query($conn, "SELECT * FROM stemming WHERE id=2") or die(mysqli_error($conn));
        // ambil semua jawaban
        $queryAnswerAll = mysqli_query($conn, "SELECT * FROM stemming ORDER BY id") or die(mysqli_error($conn));
        // ambil kunci jawaban
        $queryKey = mysqli_query($conn, "SELECT * FROM stemming WHERE id=1") or die(mysqli_error($conn));

        $resultKey = mysqli_fetch_assoc($queryKey);
        $answerKey = json_decode($resultKey['term']);  // array answer key


        // TES BANYAK DOKUMEN
        
        foreach ($queryAnswerAll as $key => $val) {
            $intersect = array_intersect(json_decode($val['term']), $answerKey); // hasil kata setelah disamakan dengan kata kunci jawaban
            $countTermInDoc = array_count_values($intersect); // menghitung jumlah kata per dokumen
            $id_doc = $val['doc_id'];

            $resultTerm = [];
            foreach ($countTermInDoc as $term => $count) {
                
                // -------------------------------
                // $term = [
                //     "term" => $term,
                //     "count" => $count,
                //     "weight" => 0
                // ];
                // array_push($resultTerm, $term);
                // $encTerm = json_encode($resultTerm);
                // -----------------------------------
                
                $masukan5 = mysqli_query($conn, "INSERT INTO tbindex (Term, DocId, Count) VALUES ('$term', '$id_doc', '$count')") or die(mysqli_error($conn));
            }
            // -----------
            // $masukan5 = mysqli_query($conn, "INSERT INTO tbindex (Term, DocId, Count) VALUES ('$encTerm', '$id_doc', '$count')") or die(mysqli_error($conn));
            // unset($encTerm);
            // -----------
        }
        return $masukan5;
    }

    function hitungIdf() {
        // HITUNG IDF
        include 'koneksi.php';
        $resn = mysqli_query($conn, "SELECT DISTINCT DocId FROM tbindex WHERE DocId!= 1");
        $n = mysqli_num_rows($resn); // jumlah total dokumen

        //hitung bobot untuk setiap Term dalam setiap DocId
        $resBobot = mysqli_query($conn, "SELECT * FROM tbindex WHERE DocId != 1 ORDER BY Id");

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