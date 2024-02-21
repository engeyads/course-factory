<?php
  session_start();


  include '../include/functions.php';
  include '../include/db.php';
  if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
      die("Error: Database session not set or empty.");
  }

  switch($_SESSION['db_name']){
      case 'agile4training':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'agile4training ar':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'blackbird-training':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      case 'blackbird-training.co.uk':
          $DBweekname = 'week';
          $newdbstype = true;
      break;
      case 'mercury english':
          $DBweekname = 'week';
          $newdbstype = false;
      break;
      case 'mercury arabic':
          $DBweekname = 'week';
      break;
      case 'Euro Wings En':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      case 'Euro Wings Ar':
          $DBweekname = 'weeks';
          $newdbstype = false;
      break;
      default:
      $DBweekname = 'week';
      $newdbstype = false;
      break;
  }
  //error_reporting(E_ALL);
  $lvl = $_SESSION['userlevel'];

  function getRandomColor() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
// Function to get the predefined color for each country
function getCountryColor($countryCode)
{
    // Define colors for countries
    $countryColors = array(
        'AE' => '#6dba6e', // United Arab Emirates
        'MO' => '#e3de8e', // Macao
        'GB' => '#9f1c66', // United Kingdom
        'MA' => '#254704', // Morocco
        'FR' => '#937c50', // France
        'SA' => '#91ffbc', // Saudi Arabia
        'NL' => '#e17782', // Netherlands
        'AU' => '#5ee3b5', // Australia
        'GE' => '#747551', // Georgia
        'AZ' => '#909096', // Azerbaijan
        'ES' => '#b8a33c', // Spain
        'IT' => '#52d6aa', // Italy
        'QA' => '#a62d88', // Qatar
        'BH' => '#ab13fa', // Bahrain
        'RU' => '#8e44ad', // Russia
        'US' => '#3498db', // United States
        'CA' => '#ab98db', // Canada
        'IN' => '#e67e22', // India
        'JP' => '#1abc9c', // Japan
        'BR' => '#ff6f61', // Brazil
        'DE' => '#2c3eab', // Germany
        'CN' => '#e74c3c', // China
        'ZA' => '#e67e22', // South Africa
        'KR' => '#16a085', // South Korea
        'NG' => '#d35400', // Nigeria
        'MX' => '#27ae60', // Mexico
        'ID' => '#c0392b', // Indonesia
        'PK' => '#2980b9', // Pakistan
        'BD' => '#f74c3c', // Bangladesh
        'TR' => '#f39c12', // Turkey
        'IR' => '#2c3e50', // Iran
        'PH' => '#349832', // Philippines
        'VN' => '#f1c40f', // Vietnam
        'TH' => '#d35400', // Thailand
        'MY' => '#8e44ad', // Malaysia
        'SG' => '#1abc9c', // Singapore
        'TW' => '#3498db', // Taiwan
        'PL' => '#3498db', // Poland
        'EG' => '#fefcb1', // Egypt
        'AR' => '#e67e22', // Argentina
        'CL' => '#3498db', // Chile
        'CO' => '#1abc9c', // Colombia
        'PE' => '#d35400', // Peru
        'VE' => '#27ae60', // Venezuela
        'EC' => '#c0392b', // Ecuador
        'BO' => '#8e44ad', // Bolivia
        'PY' => '#2ecc71', // Paraguay
        'UY' => '#e74c3c', // Uruguay
        // Add more countries and colors as needed
    );

    // Convert the country code to uppercase for case-insensitive matching
    $upperCountryCode = strtoupper($countryCode);

    // Return the predefined color if available, otherwise generate a random color
    return isset($countryColors[$upperCountryCode]) ? $countryColors[$upperCountryCode] : getRandomColor();
}
$query = "SELECT id, codetwo FROM cities WHERE deleted_at IS NULL AND codetwo IS NOT NULL";
$result = mysqli_query($conn2, $query);

$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $city = array(
        'id' => $row['id'],
        'code' => $row['codetwo'],
        'color' => getCountryColor($row['codetwo']),
    );
    $data[] = $city;
}
header('Content-Type: application/json');
ob_clean();
echo json_encode($data);
ob_flush();

?>