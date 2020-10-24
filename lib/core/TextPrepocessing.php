<?php

class TextPrepocessing
{

    function caseFolding($sentence = "")
    {
        //rubah alfabet besar menjadi kecil
        $newSentence = strtolower($sentence);
        //hilangkan tanda baca
        $newSentence = str_replace("'", " ", $newSentence);
        $newSentence = str_replace("-", " ", $newSentence);
        $newSentence = str_replace(")", " ", $newSentence);
        $newSentence = str_replace("(", " ", $newSentence);
        $newSentence = str_replace("\"", " ", $newSentence);
        $newSentence = str_replace("/", " ", $newSentence);
        $newSentence = str_replace("=", " ", $newSentence);
        $newSentence = str_replace(".", " ", $newSentence);
        $newSentence = str_replace(",", " ", $newSentence);
        $newSentence = str_replace(":", " ", $newSentence);
        $newSentence = str_replace(";", " ", $newSentence);
        $newSentence = str_replace("!", " ", $newSentence);
        $newSentence = str_replace("?", " ", $newSentence);
        $newSentence = str_replace("`", " ", $newSentence);
        $newSentence = str_replace("~", " ", $newSentence);
        $newSentence = str_replace("@", " ", $newSentence);
        $newSentence = str_replace("#", " ", $newSentence);
        $newSentence = str_replace("$", " ", $newSentence);
        $newSentence = str_replace("%", " ", $newSentence);
        $newSentence = str_replace("^", " ", $newSentence);
        $newSentence = str_replace("&", " ", $newSentence);
        $newSentence = str_replace("*", " ", $newSentence);
        $newSentence = str_replace("_", " ", $newSentence);
        $newSentence = str_replace("+", " ", $newSentence);
        $newSentence = str_replace("[", " ", $newSentence);
        $newSentence = str_replace("]", " ", $newSentence);
        $newSentence = str_replace("<", " ", $newSentence);
        $newSentence = str_replace(">", " ", $newSentence);

        return $newSentence;
    }

    function tokenization($resultCaseFolding = "")
    {
        $hasilToken = [];
        // menghitung jumlah dan memecah kata dalam kalimat
        $token = str_word_count(strtolower($resultCaseFolding), 1);
        foreach ($token as $key => $val) {
            array_push($hasilToken, $val);
        }

        return json_encode($hasilToken);
    }

    function filtering($resultTokenization)
    {
        require_once('lib/Filtering.php');
        $filtering = new Filtering();

        $hasilFilter = [];
        foreach (json_decode($resultTokenization) as $key => $value) {
            $proses = $filtering->getToken($value, 9);
            array_push($hasilFilter, $proses);
        }

        $filterTerm = json_encode(array_values(array_filter($hasilFilter)));
        return $filterTerm;
    }

    function stemming($resultFiltering)
    {
        include 'lib/Stemming.php';

        $hasilStem = [];
        foreach (json_decode($resultFiltering) as $key => $value) {
            $hasil = stemming($value); //proses stemming
            array_push($hasilStem, $hasil);
        }

        $result = json_encode($hasilStem);
        return $result;
    }
}
