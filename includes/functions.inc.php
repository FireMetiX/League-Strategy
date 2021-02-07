<?php 
    
    // Strings Hacksicher machen mit funktion

    function desinfect($str){
        $str = trim($str); // LÖSCHT ALLE LEERSCHLÄGE DIE AM ANFANG UND GEGEN SCHLUSS GESETZT WURDEN (space)
        $str = filter_var($str, FILTER_SANITIZE_STRING); // WANDELT IN PUREM STRING UM
        $str = strip_tags($str); // entfernt alle Tags
        $str = htmlspecialchars($str);
        return $str;
    }

    function desinfectEmail($str){
        $str = trim($str); // LÖSCHT ALLE LEERSCHLÄGE DIE AM ANFANG UND GEGEN SCHLUSS GESETZT WURDEN (space)
        $str = filter_var($str, FILTER_SANITIZE_EMAIL); // Remove all characters except letters, digits and !#$%&'*+-=?^_`{|}~@.[].
        $str = strip_tags($str); // entfernt alle Tags
        return $str;
    }

    function desinfectSimple($str){
        $str = trim($str); // LÖSCHT ALLE LEERSCHLÄGE DIE AM ANFANG UND GEGEN SCHLUSS GESETZT WURDEN (space)
        $str = filter_var($str, FILTER_SANITIZE_STRING); // WANDELT IN PUREM STRING UM
        $str = strip_tags($str); // entfernt alle Tags
        return $str;
    }

    function desinfectCKEditor($str) {
        $str = trim($str); // LÖSCHT ALLE LEERSCHLÄGE DIE AM ANFANG UND GEGEN SCHLUSS GESETZT WURDEN (space)
        filter_var($str, FILTER_SANITIZE_STRING); // WANDELT IN PUREM STRING UM
        // $str = htmlspecialchars($str);
        return $str;
    }

?>