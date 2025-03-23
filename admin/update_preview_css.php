<?php
/**
 * Renk şeması önizleme için CSS değişkenlerini döndüren script
 * Bu dosya, admin panelinde renk şeması değiştirildiğinde AJAX ile çağrılır
 */

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Config dosyasını dahil et
require_once '../config.php';

// AJAX isteğinden renk şeması değerini al
$scheme = isset($_GET['scheme']) ? $_GET['scheme'] : 'blue-green';

// CSS içeriğini ayarla
header('Content-Type: text/css');

// Cache'leme yapılmasını engelle
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Renkler için RGB değişkenleri de ekle, bu sayede rgba() kullanımı kolaylaşır
$primaryRgb = '';
$secondaryRgb = '';
$accentRgb = '';
$darkRgb = '';

// Seçilen renk şemasına göre CSS değişkenlerini döndür
switch ($scheme) {
    case 'blue-green':
        $primaryRgb = '26, 115, 232'; // #1a73e8
        $secondaryRgb = '52, 168, 83'; // #34a853
        $accentRgb = '251, 188, 4'; // #fbbc04
        $darkRgb = '32, 33, 36'; // #202124
        
        echo "
            :root {
                --primary-color: #1a73e8;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #0d47a1;
                --secondary-color: #34a853;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #fbbc04;
                --accent-rgb: {$accentRgb};
                --dark-color: #202124;
                --dark-rgb: {$darkRgb};
                --light-color: #f8f9fa;
                --main-bg-color: #ffffff;
                --header-bg-color: #1a73e8;
                --footer-bg-color: #202124;
            }
        ";
        break;
    case 'red-orange':
        $primaryRgb = '234, 67, 53'; // #ea4335
        $secondaryRgb = '255, 112, 67'; // #ff7043
        $accentRgb = '255, 202, 40'; // #ffca28
        $darkRgb = '62, 39, 35'; // #3e2723
        
        echo "
            :root {
                --primary-color: #ea4335;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #b71c1c;
                --secondary-color: #ff7043;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #ffca28;
                --accent-rgb: {$accentRgb};
                --dark-color: #3e2723;
                --dark-rgb: {$darkRgb};
                --light-color: #fff3e0;
                --main-bg-color: #ffffff;
                --header-bg-color: #ea4335;
                --footer-bg-color: #3e2723;
            }
        ";
        break;
    case 'purple-pink':
        $primaryRgb = '103, 58, 183'; // #673ab7
        $secondaryRgb = '233, 30, 99'; // #e91e63
        $accentRgb = '255, 193, 7'; // #ffc107
        $darkRgb = '49, 27, 146'; // #311b92
        
        echo "
            :root {
                --primary-color: #673ab7;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #4527a0;
                --secondary-color: #e91e63;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #ffc107;
                --accent-rgb: {$accentRgb};
                --dark-color: #311b92;
                --dark-rgb: {$darkRgb};
                --light-color: #f3e5f5;
                --main-bg-color: #ffffff;
                --header-bg-color: #673ab7;
                --footer-bg-color: #311b92;
            }
        ";
        break;
    case 'green-teal':
        $primaryRgb = '76, 175, 80'; // #4caf50
        $secondaryRgb = '0, 150, 136'; // #009688
        $accentRgb = '255, 214, 0'; // #ffd600
        $darkRgb = '27, 94, 32'; // #1b5e20
        
        echo "
            :root {
                --primary-color: #4caf50;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #2e7d32;
                --secondary-color: #009688;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #ffd600;
                --accent-rgb: {$accentRgb};
                --dark-color: #1b5e20;
                --dark-rgb: {$darkRgb};
                --light-color: #e8f5e9;
                --main-bg-color: #ffffff;
                --header-bg-color: #4caf50;
                --footer-bg-color: #1b5e20;
            }
        ";
        break;
    case 'dark-blue':
        $primaryRgb = '13, 71, 161'; // #0d47a1
        $secondaryRgb = '41, 182, 246'; // #29b6f6
        $accentRgb = '255, 214, 0'; // #ffd600
        $darkRgb = '0, 33, 113'; // #002171
        
        echo "
            :root {
                --primary-color: #0d47a1;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #002171;
                --secondary-color: #29b6f6;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #ffd600;
                --accent-rgb: {$accentRgb};
                --dark-color: #002171;
                --dark-rgb: {$darkRgb};
                --light-color: #e3f2fd;
                --main-bg-color: #ffffff;
                --header-bg-color: #0d47a1;
                --footer-bg-color: #002171;
            }
        ";
        break;
    case 'green-brown':
        $primaryRgb = '76, 175, 80'; // #4caf50
        $secondaryRgb = '141, 110, 99'; // #8d6e63
        $accentRgb = '255, 193, 7'; // #ffc107
        $darkRgb = '27, 94, 32'; // #1b5e20
        
        echo "
            :root {
                --primary-color: #4caf50;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #2e7d32;
                --secondary-color: #8d6e63;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #ffc107;
                --accent-rgb: {$accentRgb};
                --dark-color: #1b5e20;
                --dark-rgb: {$darkRgb};
                --light-color: #e8f5e9;
                --main-bg-color: #ffffff;
                --header-bg-color: #4caf50;
                --footer-bg-color: #3e2723;
            }
        ";
        break;
    default:
        $primaryRgb = '26, 115, 232'; // #1a73e8
        $secondaryRgb = '52, 168, 83'; // #34a853
        $accentRgb = '251, 188, 4'; // #fbbc04
        $darkRgb = '32, 33, 36'; // #202124
        
        echo "
            :root {
                --primary-color: #1a73e8;
                --primary-rgb: {$primaryRgb};
                --primary-dark: #0d47a1;
                --secondary-color: #34a853;
                --secondary-rgb: {$secondaryRgb};
                --accent-color: #fbbc04;
                --accent-rgb: {$accentRgb};
                --dark-color: #202124;
                --dark-rgb: {$darkRgb};
                --light-color: #f8f9fa;
                --main-bg-color: #ffffff;
                --header-bg-color: #1a73e8;
                --footer-bg-color: #202124;
            }
        ";
}

// CSS önbellek busting yorumu ekle
echo "\n/* Cache Buster: " . time() . " */";

// JavaScript objesi şeklinde cache-busting bilgilerini çıktıla
echo "\n/* 
{
    \"cache_timestamp\": " . time() . ",
    \"color_scheme\": \"" . $scheme . "\",
    \"random_id\": \"" . uniqid() . "\"
}
*/"; 