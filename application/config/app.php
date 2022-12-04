<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['cyls'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                        );
$config['mysphs'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                            '4.25',
                            '4.50',
                            '4.75',
                            '5.00',
                            '5.25',
                            '5.50',
                            '5.75',
                            '6.00',
                            '6.25',
                            '6.50',
                            '6.75',
                            '7.00',
                            '7.25',
                            '7.50',
                            '7.75',
                            '8.00',
                            '8.25',
                            '8.50',
                            '8.75',
                            '9.00',
                            '9.25',
                            '9.50',
                            '9.75',
                            '10.00',
                            '10.25',
                            '10.50',
                            '10.75',
                            '11.00',
                            '11.25',
                            '11.50',
                            '11.75',
                            '12.00',
                            '12.25',
                            '12.50',
                            '12.75',
                            '13.00',
                            '13.25',
                            '13.50',
                            '13.75',
                            '14.00',
                            '14.25',
                            '14.50',
                            '14.75',
                            '15.00',
                        );
$config['hysphs'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                            '4.25',
                            '4.50',
                            '4.75',
                            '5.00',
                            '5.25',
                            '5.50',
                            '5.75',
                            '6.00',
                            '6.25',
                            '6.50',
                            '6.75',
                            '7.00',
                            '7.25',
                            '7.50',
                            '7.75',
                            '8.00',
                        );

$config['iKindOfLens'] = array(
        '1.56 CHEMI', //ok
        '1.60 CHEMI U2',
        '1.61 CHEMI Crystal U2', //ok
        '1.56 CHEMI Crystal U2', //ok
        '1.56 CHEMI Crystal U6',//ok
        '1.60 CHEMI Crystal U6',//ok
        '1.67 CHEMI Crystal U2', //ok
        '1.67 CHEMI Crystal U6',//ok
        '1.74 CHEMI Crystal U2', //ok
        '1.56 CHEMI ASP PHOTOCHROMIC GRAY',//ok

        '1.67 KODAK FSV,UV400 Clean\'N\'CleAR', //ok
        '1.60 KODAK FSV,UV400 Clean\'N\'CleAR', //ok
        '1.67 KODAK FSV,UV400 Clean\'N\'CleAR', //ok
        '1.60 KODAK UV400 BLUE',//ok

        '1.67 HOYA NULUX SFT SV',//ok
        '1.60 HOYA NULUX SFT SV', //ok
        '1.60 ESSILOR CRIZAL ALIZE ',//ok
        

        '1.56 NAHAMI CRYSTAL COATED',
        '1.60 NAHAMI SUPER HMC A+', //ok
        '1.67 NAHAMI SUPER HMC',//ok

        '1.56 KOREA TC',//ok
        '1.56 Đổi màu TC',//ok
        '1.56 ĐM PQ Korea',//ok
        '1.56 CR Korea',//ok
        '1.56 Polaroid CR Korea',//ok
        //'1.56 2 Tròng TC Korea', //Chua ho tro da trong bao cao
        //'1.56 2 Tròng QP TC Korea',//Chua ho tro da trong bao cao
        '1.56 TRÁNG CỨNG', //ok
        '1.60 U1 ECOVIS', //ok
        'ĐỔI MÀU KOREA',
        '1.49 CR Korea',//ok
        'MẮT 1.56 POLAROD KHÓI',//ok
        'MẮT 1.56 POLAROD XANH',//ok,
        '1.56 KHÓI 1 MÀU CR',
        '1.56 KHÓI 2 MÀU CR',
        '1.56 TRÀ 1 MÀU CR',
        '1.56 TRÀ 2 MÀU CR',
        '1.56 XANH 1 MÀU CR'
        );

$_arrTmp = array();
foreach($config['iKindOfLens'] as $v)
{
    $_arrTmp[$v] = $v;
}
$config['KindOfLens'] = $_arrTmp;
/*$config['KindOfLens'] = array(
    '1.56 CHEMI' => '1.56 CHEMI',
    '1.60 U2 CHEMI' => '1.60 U2 CHEMI',
    '1.67 U2 CHEMI' => '1.67 U2 CHEMI',
    '1.56 KODAK FSV,UV400 Clean\'N\'CleAR' => '1.60 KODAK FSV,UV400 Clean\'N\'CleAR',
    '1.60 KODAK FSV,UV400 Clean\'N\'CleAR' => '1.60 KODAK FSV,UV400 Clean\'N\'CleAR',
    '1.67 KODAK FSV,UV400 Clean\'N\'CleAR' => '1.67 KODAK FSV,UV400 Clean\'N\'CleAR',
    '1.60 ESSILOR CRIZAL ALIZE UV' => '1.60 ESSILOR CRIZAL ALIZE UV',
    '1.60 HOYA - NULUX SFT SV' => '1.60 HOYA - NULUX SFT SV',
    '1.60 NAHAmi SUPER HMC A+'=>'1.60 NAHAmi SUPER HMC A+',
    '1.61 CHEMI Crystal U2'=>'1.61 CHEMI Crystal U2',
    '1.67 HOYA - NULUX SFT SV'=>'1.67 HOYA - NULUX SFT SV',
    '1.67 CHEMI Crystal U2'=>'1.67 CHEMI Crystal U2',
    '1.49 CR Korea'=>'1.49 CR Korea',
    '1.67 CHEMI Crystal U6'=>'1.67 CHEMI Crystal U6',
    );
*/
$config['exclude_module'] = array(
    'secure_controller',
    'no_access',
    'login',
    'testex',
    'home',
    'persons',
    'cron',
    'verify'
);
/*
** config qrcode; = 0 don't active qrcode; 
** = 1: Active module qrcode;
*/
$config['qrcode'] = 1;
$config['barcode'] = 1;

/*
Báo cáo Khác;
*/

$config['filter'] = array('GỌNG <1T','GỌNG <2T','GỌNG <3T','GỌNG <4T','GỌNG <5T','GỌNG >5T','G07','G08','G09','G10','G11','G12','G13','G14','G15',
            'KÍNH MÁT <1T','KÍNH MÁT <2T','KÍNH MÁT <3T','KÍNH MÁT <4T','KÍNH MÁT <5T','KÍNH MÁT >5T','M07','M08','M09','M10','M11','M12','M13','VẬT TƯ','M.HOYA','G.CHEMI');
