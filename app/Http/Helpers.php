<?php

/**
 * Return active class name
 */
function isActive($url, $contain = true)
{
    if ($contain) {
        return (strpos(currentUrl(), $url) === 0) ? 'active' : '';
    } else {
        return $url === currentUrl() ? 'active' : '';
    }
}

/**
 * return flash value
 * @param string $message
 * @return string
 */
function getFlash($message){
    return allFlashes()[$message];
}

/**
 * Return error class name
 */
function errorClass($name)
{
    return errorExists($name) ? 'is-invalid' : '';
}

/**
 * Return error text was set in System\Request
 */
function errorText($name)
{
    return errorExists($name) ? '<div><small class="text-danger">' . error($name) . '</small></div>' : '';

}

/**
 * Return value of inputs, when user has an error after submit.
 */
function oldOrValue($name, $value)
{
    return empty(old($name)) ? $value : old($name);
}

/**
 * create paginate HTML tags.
 */
function paginateView($data, $perPage)
{
    $totalRows = $data;
    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $totalPages = ceil($totalRows / $perPage);
    $currentPage = min($currentPage, $totalPages);
    $currentPage = max($currentPage, 1);

    $paginateView = '';
    '<a href="#" ><i class="ui-arrow-left"></i></a>';
    $paginateView .= ($currentPage != 1) ? '<a href="' . paginateUrl(1) . '" class="pagination__page pagination__icon pagination__page--next"><i class="ui-arrow-right"></i></a>' : '';
    $paginateView .= (($currentPage - 2) >= 1) ? '<a href="' . paginateUrl($currentPage - 2) . '" class="pagination__page">' . ($currentPage - 2) . '</a>' : '';
    $paginateView .= (($currentPage - 1) >= 1) ? '<a href="' . paginateUrl($currentPage - 1) . '" class="pagination__page">' . ($currentPage - 1) . '</a>' : '';
    $paginateView .= '<a href="' . paginateUrl($currentPage) . '" class="pagination__page pagination__page--current">' . ($currentPage) . '</a>';
    $paginateView .= (($currentPage + 1) <= $totalPages) ? '<a href="' . paginateUrl($currentPage + 1) . '" class="pagination__page">' . ($currentPage + 1) . '</a>' : '';
    $paginateView .= (($currentPage + 2) <= $totalPages) ? '<a href="' . paginateUrl($currentPage + 2) . '" class="pagination__page">' . ($currentPage + 2) . '</a>' : '';
    $paginateView .= ($currentPage != $totalPages) ? '<a href="' . paginateUrl($totalPages) . '" class="pagination__page pagination__icon pagination__page--next"><i class="ui-arrow-left"></i></a>' : '';

    return '<nav class="pagination">' . $paginateView . '</nav>';
}

/**
 * generate pginate url.
 */
function paginateUrl($page)
{
    $urlArray = explode('?', currentUrl());
    if (isset($urlArray[1])) {

        $_GET['page'] = $page;
        $getVariables = array_map(function ($value, $key) {return $key . '=' . $value;}, $_GET, array_keys($_GET));
        return $urlArray[0] . '?' . implode('&', $getVariables);
    } else {
        return currentUrl() . '?page=' . $page;
    }
}

/**
 * Get first $limit characters from string, respecting full words
 *
 * @param string $value
 * @param integer $limit
 * @param string $end
 * @return void
 */
function str_limit($value, $limit = 100, $end = '...')
{
    $limit = $limit - mb_strlen($end); // Take into account $end string into the limit
    $valuelen = mb_strlen($value);
    return $limit < $valuelen ? mb_substr($value, 0, mb_strrpos($value, ' ', $limit - $valuelen)) . $end : $value;
}

/**
 * @param $value
 * @return string
 */
function slug($string, $separator = '-') {
    $_transliteration = ["/??|??/" => "e",
        "/??/" => "e",
        "/??/" => "e",
        "/??/" => "e",
        "/??/" => "e",
        "/??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??/" => "",
        "/??|??|??|??/" => "",
        "/??|??/" => "",
        "/??|??/" => "",
        "/??|??|??|??|??|??| ??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??/" => "",
        "/??/" => "",
        "/??/" => "",
        "/??/" => "",
        "/??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??|??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??|??|??|??/" => "",
        "/??|??|??|??|??|??/" => "",
        "/??|??|??|??/" => "",
        "/??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??|??|??|??|??|??|??|??|??|??|??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??|??/" => "",
        "/??/" => "",
        "/??/" => "",
        "/??|??|??/" => "",
        "/??|??|??/" => "",
        "/??|??/" => "E",
        "/??/" => "s",
        "/??/" => "J",
        "/??/" => "j",
        "/??/" => "E",
        "/??/" => ""];
    $quotedReplacement = preg_quote($separator, '/');
    $merge = [
        '/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
        '/[\s\p{Zs}]+/mu' => $separator,
        sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
    ];
    $map = $_transliteration + $merge;
    unset($_transliteration);
    return preg_replace(array_keys($map), array_values($map), $string);
}
