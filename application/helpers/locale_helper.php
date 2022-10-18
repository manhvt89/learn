<?php

/*
 * Get url tracking shipper
 */

function get_url_tracking_shipping($shipping_key)
{
    $arr = array(
        'vnp'=>'http://www.vnpost.vn/vi-vn/dinh-vi/buu-pham?key=',
        'vtp'=>'https://www.viettelpost.com.vn/Tracking?KEY=',
        'ghn'=>'#'
    );
    if(isset($arr[$shipping_key])) {
        return $arr[$shipping_key];
    }else{
        return '#';
    }
}


/*
 * Get resource to buy
 */

function get_order_completeds($completed)
{
    $completeds = array();

    if($completed == 0)
    {
        $completeds = array(
            1=> 'Đặt hàng',
            -1=>'Hủy đơn'
        );
    }elseif ($completed == 1)
    {
        $completeds = array(
            1=> 'Đặt hàng',
            2=> 'Đang chuyển',
            -1=>'Hủy đơn'
        );
    }elseif ($completed == 2)
    {
        $completeds = array(
            3=> 'Nhận hàng',
            -1=>'Hủy đơn'
        );
    }else{
        $completeds = array(
            4=> 'Hoàn thành',
            -1=>'Hủy đơn'
        );
    }
    return $completeds;
}
function get_status_list()
{
    $CI =& get_instance();
    $arr = array(
        -1=>$CI->lang->line('order_return'),
        0=>$CI->lang->line('order_info'),
        1=>$CI->lang->line('order_ordered'),
        2=>$CI->lang->line('order_shipping'),
        3=>$CI->lang->line('order_received'),
        4=>$CI->lang->line('order_completed')
    );
    return $arr;
}
function get_sources_to_buy()
{
    $sourses = array(
        'fb'=>'facebook',
        'sp'=>'shopee',
        'ad'=>'adayroi',
        'ld'=>'lazada',
    );
    return $sourses;
}


/*
 * Get shiping_methods
 */

function get_shipping_methods()
{
    $shipping_methods = array(
        'vnp'=>'VN POST',
        'vtp'=>'Viettel Post',
        'ghn'=>'Giao Hàng Nhanh'
    );

    return $shipping_methods;
}

/*
 * City list
 */
function get_cities_list()
{
    $cities = array(
        'An Giang',
        'Bà Rịa - Vũng Tàu',
        'Bắc Giang',
        'Bắc Kạn',
        'Bạc Liêu',
        'Bắc Ninh',
        'Bến Tre',
        'Bình Định',
        'Bình Dương',
        'Bình Phước',
        'Bình Thuận',
        'Cà Mau',
        'Cao Bằng',
        'Đắk Lắk',
        'Đắk Nông',
        'Điện Biên',
        'Đồng Nai',
        'Đồng Tháp',
        'Gia Lai',
        'Hà Giang',
        'Hà Nam',
        'Hà Tĩnh',
        'Hải Dương',
        'Hậu Giang',
        'Hòa Bình',
        'Hưng Yên',
        'Khánh Hòa',
        'Kiên Giang',
        'Kon Tum',
        'Lai Châu',
        'Lâm Đồng',
        'Lạng Sơn',
        'Lào Cai',
        'Long An',
        'Nam Định',
        'Nghệ An',
        'Ninh Bình',
        'Ninh Thuận',
        'Phú Thọ',
        'Quảng Bình',
        'Quảng Nam',
        'Quảng Ngãi',
        'Quảng Ninh',
        'Quảng Trị',
        'Sóc Trăng',
        'Sơn La',
        'Tây Ninh',
        'Thái Bình',
        'Thái Nguyên',
        'Thanh Hóa',
        'Thừa Thiên Huế',
        'Tiền Giang',
        'Trà Vinh',
        'Tuyên Quang',
        'Vĩnh Long',
        'Vĩnh Phúc',
        'Yên Bái',
        'Phú Yên',
        'Cần Thơ',
        'Đà Nẵng',
        'Hải Phòng',
        'Hà Nội',
        'TP HCM',
    );
    return $cities;
}

/*
 * Currency locale
 */

function current_language_code()
{
    return get_instance()->config->item('language_code');
}

function current_language()
{
    return get_instance()->config->item('language');
}

function currency_side()
{
    $config = get_instance()->config;
    $fmt = new \NumberFormatter($config->item('number_locale'), \NumberFormatter::CURRENCY);
    $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $config->item('currency_symbol'));
    return !preg_match('/^¤/', $fmt->getPattern());
}

function quantity_decimals()
{
    $config = get_instance()->config;
    return $config->item('quantity_decimals') ? $config->item('quantity_decimals') : 0;
}

function totals_decimals()
{
	$config = get_instance()->config;
	return $config->item('currency_decimals') ? $config->item('currency_decimals') : 0;
}

function to_currency($number)
{
    return to_decimals($number, 'currency_decimals', \NumberFormatter::CURRENCY);
}

function to_currency_no_money($number)
{
    return to_decimals($number, 'currency_decimals');
}

function to_tax_decimals($number)
{
	// taxes that are NULL, '' or 0 don't need to be displayed
	// NOTE: do not remove this line otherwise the items edit form will show a tax with 0 and it will save it
    if(empty($number))
    {
        return $number;
    }
	
    return to_decimals($number, 'tax_decimals');
}

function to_quantity_decimals($number)
{
    return to_decimals($number, 'quantity_decimals');
}

function to_decimals($number, $decimals, $type=\NumberFormatter::DECIMAL)
{
	// ignore empty strings and return
	// NOTE: do not change it to empty otherwise tables will show a 0 with no decimal nor currency symbol
    if(!isset($number))
    {
        return $number;
    }
	
    $config = get_instance()->config;
    $fmt = new \NumberFormatter($config->item('number_locale'), $type);
	if($decimals == '2')
    {
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
        $fmt->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);
        if (empty($config->item('thousands_separator')))
        {
            $fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
        }
        $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $config->item('currency_symbol'));
    
    } else {
        
        $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $config->item($decimals));
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $config->item($decimals));
        if (empty($config->item('thousands_separator')))
        {
            $fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
        }
        $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $config->item('currency_symbol'));
    }
    return $fmt->format($number);
}

function parse_decimals($number)
{
    // ignore empty strings and return
    if(empty($number))
    {
        return $number;
    }

    $config = get_instance()->config;
    $fmt = new \NumberFormatter( $config->item('number_locale'), \NumberFormatter::DECIMAL );
    if (empty($config->item('thousands_separator')))
    {
        $fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }
    return $fmt->parse($number);
}

/*
 * Time locale conversion utility
 */

function dateformat_momentjs($php_format)
{
    $SYMBOLS_MATCHING = array(
        'd' => 'DD',
        'D' => 'ddd',
        'j' => 'D',
        'l' => 'dddd',
        'N' => 'E',
        'S' => 'o',
        'w' => 'e',
        'z' => 'DDD',
        'W' => 'W',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '', // no equivalent
        'L' => '', // no equivalent
        'o' => 'YYYY',
        'Y' => 'YYYY',
        'y' => 'YY',
        'a' => 'a',
        'A' => 'A',
        'B' => '', // no equivalent
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => 'SSS',
        'e' => 'zz', // deprecated since version $1.6.0 of moment.js
        'I' => '', // no equivalent
        'O' => '', // no equivalent
        'P' => '', // no equivalent
        'T' => '', // no equivalent
        'Z' => '', // no equivalent
        'c' => '', // no equivalent
        'r' => '', // no equivalent
        'U' => 'X'
    );

    return strtr($php_format, $SYMBOLS_MATCHING);
}

function dateformat_bootstrap($php_format)
{
    $SYMBOLS_MATCHING = array(
        // Day
        'd' => 'dd',
        'D' => 'd',
        'j' => 'd',
        'l' => 'dd',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => '',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yyyy',
        'y' => 'yy',
        // Time
        'a' => 'p',
        'A' => 'P',
        'B' => '',
        'g' => 'H',
        'G' => 'h',
        'h' => 'HH',
        'H' => 'hh',
        'i' => 'ii',
        's' => 'ss',
        'u' => ''
    );

    return strtr($php_format, $SYMBOLS_MATCHING);
}

function convert_number_to_words($number) {

    $hyphen      = ' ';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'âm ';
    $decimal     = ' phẩy ';
    $dictionary  = array(
        0                   => 'Không',
        1                   => 'Một',
        2                   => 'Hai',
        3                   => 'Ba',
        4                   => 'Bốn',
        5                   => 'Năm',
        6                   => 'Sáu',
        7                   => 'Bảy',
        8                   => 'Tám',
        9                   => 'Chín',
        10                  => 'Mười',
        11                  => 'Mười một',
        12                  => 'Mười hai',
        13                  => 'Mười ba',
        14                  => 'Mười bốn',
        15                  => 'Mười lăm',
        16                  => 'Mười sáu',
        17                  => 'Mười bảy',
        18                  => 'Mười tám',
        19                  => 'Mười chín',
        20                  => 'Hai mươi',
        21                  => 'Hai mốt',
        22                  => 'Hai hai',
        23                  => 'Hai ba',
        24                  => 'Hai bốn',
        25                  => 'Hai lăm',
        26                 => 'Hai sáu',
        27                  => 'Hai bẩy',
        28                  => 'Hai tám',
        29                  => 'Hai chín',
        30                  => 'Ba mươi',
        31                  => 'Ba mốt',
        32                  => 'Ba hai',
        33                  => 'Ba ba',
        34                  => 'Ba bốn',
        35                  => 'Ba lăm',
        36                  => 'Ba sáu',
        37                  => 'Ba bẩy',
        38                  => 'Ba tám',
        39                  => 'Ba chín',
        40                  => 'Bốn mươi',
        41                  => 'Bốn mốt',
        42                  => 'Bốn hai',
        43                  => 'Bốn ba',
        44                  => 'Bốn bốn',
        45                  => 'Bốn lăm',
        46                  => 'Bốn sáu',
        47                  => 'Bốn bẩy',
        48                  => 'Bốn tám',
        49                  => 'Bốn chín',
        50                  => 'Năm mươi',
        51                  => 'Năm mốt',
        52                  => 'Năm hai',
        53                  => 'Năm ba',
        54                  => 'Năm bốn',
        55                  => 'Năm lăm',
        56                  => 'Năm sáu',
        57                  => 'Năm bẩy',
        58                  => 'Năm tám',
        59                  => 'Năm chín',
        60                  => 'Sáu mươi',
        61                  => 'Sáu mốt',
        62                  => 'Sáu hai',
        63                  => 'Sáu ba',
        64                  => 'Sáu bốn',
        65                  => 'Sáu lăm',
        66                  => 'Sáu sáu',
        67                  => 'Sáu bẩy',
        68                  => 'Sáu tám',
        69                  => 'Sáu chín',
        70                  => 'Bảy mươi',
        71                  => 'Bảy mốt',
        72                  => 'Bảy hai',
        73                  => 'Bảy ba',
        74                  => 'Bảy bốn',
        75                  => 'Bảy lăm',
        76                  => 'Bảy sáu',
        77                  => 'Bảy bẩy',
        78                  => 'Bảy tám',
        79                  => 'Bảy chín',
        80                  => 'Tám mươi',
        81                  => 'Tám mốt',
        82                  => 'Tám hai',
        83                  => 'Tám ba',
        84                  => 'Tám tư',
        85                  => 'Tám lăm',
        86                  => 'Tám sáu',
        87                  => 'Tám bẩy',
        88                  => 'Tám tám',
        89                  => 'Tám chín',
        90                  => 'Chín mươi',
        91                  => 'Chín mốt',
        92                  => 'Chín hai',
        93                  => 'Chín ba',
        94                  => 'Chín bốn',
        95                  => 'Chín lăm',
        96                  => 'Chín sáu',
        97                  => 'Chín bẩy',
        98                  => 'Chín tám',
        99                  => 'Chín chín',
        100                 => 'trăm',
        1000                => 'ngìn',
        1000000             => 'triệu',
        1000000000          => 'tỷ',
        1000000000000       => 'nghìn tỷ',
        1000000000000000    => 'ngàn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 100:
            $string = $dictionary[$number];
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return mb_ucfirst($string,'UTF-8');
}
function mb_ucfirst($string, $encoding)
{
    $string = mb_convert_case($string, MB_CASE_LOWER, 'UTF-8');
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}
?>
