<?php

class Counter
{

    public function write($name)
    {

        if (!file_exists($name)) {
            $f = fopen($name, "w");
            fwrite($f, "0");
            fclose($f);
        }

// Read the current value of our counter file
        $f = fopen($name, "r");
        $counterVal = fread($f, filesize($name));
        fclose($f);
// Has visitor been counted in this session?
// If not, increase counter value by one
        if (!isset($_SESSION['$name'])) {
            $_SESSION['$name'] = "yes";
            $counterVal++;
            $f = fopen($name, "w");
            fwrite($f, $counterVal);
            fclose($f);
        }
    }
    public function read($file){
        if (file_exists($file)) {
            $f = fopen($file, "r");
            $counterVal = fread($f, filesize($file));
            return $counterVal;
            fclose($f);
        }
        else{
            echo "Errore apertura file";
            exit();
        }
    }
}

