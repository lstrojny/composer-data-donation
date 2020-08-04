<?php

const DATA_DIR = __DIR__ . '/data';

function controller(array $post): array
{
    if (! isset($post['data'])) {
        return [];
    }

    $data = $post['data'];
    if (strlen($data) > 1024 * 1024) {
        return ['error' => 'Submitted data is more than 1M'];
    }

    $data = trim($data);
    if (strlen($data) === 0) {
        return ['error' => 'Submitted data is empty'];
    }

    json_decode($data, true, 1024, 0b0001);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'error' => 'Submitted data is invalid JSON. Please make sure you have copied the whole payload',
            'data' => $data,
        ];
    }

    file_put_contents(sprintf(
        '%s/%s-%d-%s.json',
        DATA_DIR,
        date('Y-m-d\TH:i:s'),
        random_int(0, PHP_INT_MAX),
        md5($data)
    ), $data);
    return ['success' => 'Thank you very much, your data has been successfully stored'];
}

function render(string $template, array $parameters): void
{
    extract($parameters);
    include $template;
}

$parameters = controller($_POST);
render('template.phtml', $parameters);
