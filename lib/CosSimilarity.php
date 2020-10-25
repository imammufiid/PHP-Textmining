<?php

class CosSimilarity
{

    function hitung()
    {
        include 'koneksi.php';
        require_once('core/CosineSimilarity.php');

        // jawaban
        $queryAnswer2 = mysqli_query($conn, "SELECT * FROM stemming WHERE id=3") or die(mysqli_error($conn));
        $queryAnswer = mysqli_query($conn, "SELECT * FROM stemming WHERE id=2") or die(mysqli_error($conn));
        $resultQueryAnswer = mysqli_fetch_assoc($queryAnswer);
        $resultQueryAnswer2 = mysqli_fetch_assoc($queryAnswer2);

        // soal
        $queryKey = mysqli_query($conn, "SELECT * FROM stemming WHERE id=1") or die(mysqli_error($conn));
        $resultQueryKey = mysqli_fetch_assoc($queryKey);

        $resultIntersect1 = $this->intersect(json_decode($resultQueryAnswer['term']), json_decode($resultQueryKey['term']));
        $resultIntersect2 = $this->intersect(json_decode($resultQueryAnswer2['term']), json_decode($resultQueryKey['term']));
        $resultIntersect3 = $this->intersect(json_decode($resultQueryKey['term']), json_decode($resultQueryKey['term']));


        $cs = new CosineSimilarity($resultIntersect1, $resultIntersect3);
        $obj = $cs->calc($resultIntersect1, $resultIntersect3);
        
        return $obj;


    }

    function intersect($arrayOfAnswer, $arrayOfkey)
    {
        $intersect = array_intersect($arrayOfAnswer, $arrayOfkey); // hasil kata setelah disamakan dengan kata kunci jawaban
        $countTermAnswerInDoc = array_count_values($intersect); // menghitung jumlah kata per dokumen

        return $countTermAnswerInDoc;
    }
}
