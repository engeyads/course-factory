<?php
define('ROOT', __DIR__.'/../');
require ROOT.'include/functions.php';
$prompt = $_POST['prompt'];
$system = $_POST['system'];

$result =  call_openai($prompt, $openai_api_key, $system);
if (isset($result['choices'])) {

    $resulttxt = $result['choices'][0]['message']['content'];
}else{
    $resulttxt = $result['error']['message'];

}
echo $cleaned_string = str_replace(array(';', '\'', '"'), '', $resulttxt);
?>