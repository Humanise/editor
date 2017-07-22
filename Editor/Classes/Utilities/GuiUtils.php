<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class GuiUtils {

  static $languages = [
      'AB' => ['Abkhazian'],
      'AA' => ['Afar'],
      'AF' => ['Afrikaans'],
      'SQ' => ['Albanian'],
      'AM' => ['Amharic'],
      'AR' => ['Arabic'],
      'HY' => ['Armenian'],
      'AS' => ['Assamese'],
      'AY' => ['Aymara'],
      'AZ' => ['Azerbaijani'],
      'BA' => ['Bashkir'],
      'EU' => ['Basque'],
      'BN' => ['Bengali/Bangla'],
      'DZ' => ['Bhutani'],
      'BH' => ['Bihari'],
      'BI' => ['Bislama'],
      'BR' => ['Breton'],
      'BG' => ['Bulgarian'],
      'MY' => ['Burmese'],
      'BE' => ['Byelorussian'],
      'KM' => ['Cambodian'],
      'CA' => ['Catalan'],
      'ZH' => ['Chinese'],
      'CO' => ['Corsican'],
      'HR' => ['Croatian'],
      'CS' => ['Czech'],
      'DA' => ['Danish', 'da' => 'Dansk'],
      'NL' => ['Dutch'],
      'EN' => ['English', 'da' => 'Engelsk'],
      'EO' => ['Esperanto'],
      'ET' => ['Estonian'],
      'FO' => ['Faeroese'],
      'FJ' => ['Fiji'],
      'FI' => ['Finnish'],
      'FR' => ['French'],
      'FY' => ['Frisian'],
      'GD' => ['Gaelic/Scots Gaelic'],
      'GL' => ['Galician'],
      'KA' => ['Georgian'],
      'DE' => ['German', 'da' => 'Tysk'],
      'EL' => ['Greek'],
      'KL' => ['Greenlandic'],
      'GN' => ['Guarani'],
      'GU' => ['Gujarati'],
      'HA' => ['Hausa'],
      'IW' => ['Hebrew'],
      'HI' => ['Hindi'],
      'HU' => ['Hungarian'],
      'IS' => ['Icelandic'],
      'IN' => ['Indonesian'],
      'IA' => ['Interlingua'],
      'IE' => ['Interlingue'],
      'IK' => ['Inupiak'],
      'GA' => ['Irish'],
      'IT' => ['Italian'],
      'JA' => ['Japanese'],
      'JW' => ['Javanese'],
      'KN' => ['Kannada'],
      'KS' => ['Kashmiri'],
      'KK' => ['Kazakh'],
      'RW' => ['Kinyarwanda'],
      'KY' => ['Kirghiz'],
      'RN' => ['Kirundi'],
      'KO' => ['Korean'],
      'KU' => ['Kurdish'],
      'LO' => ['Laothian'],
      'LA' => ['Latin'],
      'LV' => ['Latvian/Lettish'],
      'LN' => ['Lingala'],
      'LT' => ['Lithuanian'],
      'MK' => ['Macedonian'],
      'MG' => ['Malagasy'],
      'MS' => ['Malay'],
      'ML' => ['Malayalam'],
      'MT' => ['Maltese'],
      'MI' => ['Maori'],
      'MR' => ['Marathi'],
      'MO' => ['Moldavian'],
      'MN' => ['Mongolian'],
      'NA' => ['Nauru'],
      'NE' => ['Nepali'],
      'NO' => ['Norwegian'],
      'OC' => ['Occitan'],
      'OR' => ['Oriya'],
      'OM' => ['Oromo/Afan'],
      'PS' => ['Pashto/Pushto'],
      'FA' => ['Persian'],
      'PL' => ['Polish'],
      'PT' => ['Portuguese'],
      'PA' => ['Punjabi'],
      'QU' => ['Quechua'],
      'RM' => ['Rhaeto-Romance'],
      'RO' => ['Romanian'],
      'RU' => ['Russian'],
      'SM' => ['Samoan'],
      'SG' => ['Sangro'],
      'SA' => ['Sanskrit'],
      'SR' => ['Serbian'],
      'SH' => ['Serbo-Croatian'],
      'ST' => ['Sesotho'],
      'TN' => ['Setswana'],
      'SN' => ['Shona'],
      'SD' => ['Sindhi'],
      'SI' => ['Singhalese'],
      'SS' => ['Siswati'],
      'SK' => ['Slovak'],
      'SL' => ['Slovenian'],
      'SO' => ['Somali'],
      'ES' => ['Spanish'],
      'SU' => ['Sudanese'],
      'SW' => ['Swahili'],
      'SV' => ['Swedish'],
      'TL' => ['Tagalog'],
      'TG' => ['Tajik'],
      'TA' => ['Tamil'],
      'TT' => ['Tatar'],
      'TE' => ['Tegulu'],
      'TH' => ['Thai'],
      'BO' => ['Tibetan'],
      'TI' => ['Tigrinya'],
      'TO' => ['Tonga'],
      'TS' => ['Tsonga'],
      'TR' => ['Turkish'],
      'TK' => ['Turkmen'],
      'TW' => ['Twi'],
      'UK' => ['Ukrainian'],
      'UR' => ['Urdu'],
      'UZ' => ['Uzbek'],
      'VI' => ['Vietnamese'],
      'VO' => ['Volapuk'],
      'CY' => ['Welsh'],
      'WO' => ['Wolof'],
      'XH' => ['Xhosa'],
      'JI' => ['Yiddish'],
      'YO' => ['Yoruba'],
      'ZU' => ['Zulu']
    ];

  static function getTranslated($value) {
    if (is_array($value)) {
      $lang = InternalSession::getLanguage();
      if (isset($value[$lang])) {
        return $value[$lang];
      }
      return $value[0];
    } else if (is_object($value)) {
      $lang = InternalSession::getLanguage();
      if (isset($value->$lang)) {
        return $value->$lang;
      }
      return $value->en;

    }
    return $value;
  }

  static function buildTranslatedItems($items) {
    $output = '';

    foreach ($items as $key => $texts) {
      $lang = InternalSession::getLanguage();
      $title = isset($texts[$lang]) ? $texts[$lang] : $texts['en'];
      $output .= '<item text="' . Strings::escapeEncodedXML($title) . '" value="' . $key . '"/>';
    }

    return $output;
  }

  static function getLanguageIcon($lang) {
    $languageIcons = [
      'EN' => 'flag/gb',
      'DA' => 'flag/dk',
      'DE' => 'flag/de'
    ];
    return @$languageIcons[$lang];
  }

  static function getLanguageName($lang) {
    return @GuiUtils::getTranslated(GuiUtils::$languages[$lang]);
  }

  static function getLanguages() {
    $out = [];
    foreach (GuiUtils::$languages as $key => $value) {
      $out[$key] = GuiUtils::getTranslated($value);
    }
    return $out;
  }

  /**
   * Formats a number of bytes to abreviated human readable string
   * @param int $input The bytes to format
   * @return string Human readable bytes
   */
  static function bytesToString($input) {
    if ($input < 1024) {
      return $input . ' b';
    }
    else if ($input < (1024 * 1024)) {
      return round(($input / 1024),1) . ' Kb';
    }
    else if ($input < (1024 * 1024 * 1024)) {
      return round(($input / 1024 / 1024),1) . ' Mb';
    }
    else {
      return $input;
    }
  }

  /**
   * Formats a number of bytes to full human readable string
   * @param int $input The bytes to format
   * @return string Human readable bytes
   */
  static function bytesToLongString($input) {
    if ($input < 1024) {
      return $input . ' bytes';
    }
    else if ($input < (1024 * 1024)) {
      return round(($input / 1024),1) . ' kilobytes';
    }
    else if ($input < (1024 * 1024 * 1024)) {
      return round(($input / 1024 / 1024),1) . ' Megabytes';
    }
    else {
      return $input;
    }
  }
}
?>