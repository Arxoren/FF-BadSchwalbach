<?php
class model_unwetter extends CI_Model {

    private $dwd_region = "Rheingau-Taunus-Kreis";
    private $dwd_datetime_pattern = "d.m. H:i";
    private $dwd_prewarnings = false;

    public function get_unwetter($load_prewarnings = FALSE) {
      $json_object = $this->get_dwd_json_as_object();
      if ($json_object != NULL) {
        $warnings = $json_object->warnings;
        $hasWarning = false;
        $prewarnings = $json_object->vorabInformation;

        foreach ($warnings as $warning) {
            $content = $warning[0];
            $content->regionName = $this->trans_toUmlaut($content->regionName);

            if ($content->regionName == $this->dwd_region) {
                $hasWarning = true;
                $content->description = $this->trans_toUmlaut($content->description);
                $content->headline = $this->trans_toUmlaut($content->headline);
                $content->event = $this->trans_toUmlaut($content->event);
                $content->instruction = $this->trans_toUmlaut($content->instruction);
                $content->start = date($this->dwd_datetime_pattern, substr($content->start, 0, -3));
                $content->end = date($this->dwd_datetime_pattern, substr($content->end, 0, -3));
                return $content;
            } else {
                continue;
            }
        }

        if($this->dwd_prewarnings == true) {
            if (!$hasWarning && $load_prewarnings) {
                foreach ($prewarnings as $prewarning) {
                    $content = $prewarning[0];
                    $content->regionName = $this->trans_toUmlaut($content->regionName);

                    if ($content->regionName == $this->dwd_region) {
                        $content->description = $this->trans_toUmlaut($content->description);
                        $content->headline = $this->trans_toUmlaut($content->headline);
                        $content->event = $this->trans_toUmlaut($content->event);
                        $content->instruction = $this->trans_toUmlaut($content->instruction);
                        $content->start = date($this->dwd_datetime_pattern, substr($content->start, 0, -3));
                        $content->end = date($this->dwd_datetime_pattern, substr($content->end, 0, -3));
                        return $content;
                    }
                }
            }
        } else {
          return NULL;
        }
        }

      return NULL;
    }

    private function get_dwd_json_as_object() {

        $url = 'https://www.dwd.de/DWD/warnungen/warnapp/json/warnings.json';

            ob_start();
            $out = fopen('php://output', 'w');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_STDERR, $out);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);

            fclose($out);
            $debug = ob_get_clean();

            curl_close($ch);

            //var_dump($debug); die();
            //var_dump($result);

        // JavaScript Function Code entfernen (wird vom DWD als JSONP ausgeliefert)
        $json = $this->extract_unit($result, 'warnWetter.loadWarnings(', ');');

        // Umlaute und ß umwandeln und mit nachfolgendem Q markieren für Rückumwandlung ( wegen json_Decode )
        $json_trans = $this->trans_Umlaut($json);
        return json_decode($json_trans);
    }

    // *** JSON Daten aus JSON Stream extrahieren ***
    private function extract_unit($string, $start, $end) {
        $pos = stripos($string, $start);
        $str = substr($string, $pos);
        $str_two = substr($str, strlen($start));
        $second_pos = stripos($str_two, $end);
        $str_three = substr($str_two, 0, $second_pos);
        $unit = trim($str_three); // remove whitespaces
        return $unit;
    }

    // *** Umlaute als Sonderzeichen entfernen
    private function trans_Umlaut($unit) {
        $trans = array(
            'Ä' => 'AeQ',
            'Ö' => 'OeQ',
            'Ü' => 'UeQ',
            'ä' => 'aeQ',
            'ö' => 'oeQ',
            'ü' => 'ueQ',
            'ß' => 'ssQ',
            //**
            'Ã„' => 'AeQ',
            'Ã–' => 'OeQ',
            'Ãœ' => 'UeQ',
            'Ã¤' => 'aeQ',
            'Ã¶' => 'oeQ',
            'Ã¼' => 'ueQ',
            'ÃŸ' => 'ssQ',
        );
        $data = strtr($unit, $trans);
        return $data;
    }

    // Textformatierung
    private function trans_toUmlaut($unit) {
        $trans = array(
            'AeQ' => 'Ä',
            'OeQ' => 'Ö',
            'UeQ' => 'Ü',
            'aeQ' => 'ä',
            'oeQ' => 'ö',
            'ueQ' => 'ü',
            'ssQ' => 'ß',
            'mÂ²' => 'm²',
            'Â°C' => '°C',
        );
        $data = strtr($unit, $trans);
        return $data;
    }

    private function is_reload($date1, $date2) {

        $interval = $date1->diff($date2);

        if ($interval->y > 0 || $interval->m > 0 || $interval->d > 0 || $interval->h > 1)
            return true;

        if ($interval->h == 1 && $interval->i < 50)
            return true;

        if ($interval->i > 10)
            return true;

        return false;
    }

}
