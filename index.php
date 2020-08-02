<?php
const DATA_DIR = __DIR__ . '/data';

function controller(array $post) {
    if (isset($post['data'])) {
        $data = $post['data'];
        if (strlen($data) > 1024 * 1024) {
            return array('error' => 'Submitted data is more than 1M');
        }
        $data = trim($data);
        if (strlen($data) === 0) {
            return array('error' => 'Submitted data is empty');
        }
        json_decode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array('error' => 'Submitted data is invalid JSON. Please make sure you have copied the whole payload', 'data' => $data);
        }
        file_put_contents(DATA_DIR.'/'.date('Y-m-d\TH:i:s').'-'.random_int(0, PHP_INT_MAX).'-'.md5($data).'.json', $data);
        return array('success' => 'Thank you very much, your data has been successfully stored');
    }

    return array();
}

function render($template, array $parameters) {
    extract($parameters);
    include $template;
}

$parameters = controller($_POST);
render('template.phtml', $parameters);
