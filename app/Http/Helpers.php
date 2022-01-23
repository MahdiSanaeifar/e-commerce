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
    $_transliteration = ["/ö|œ/" => "e",
        "/ü/" => "e",
        "/Ä/" => "e",
        "/Ü/" => "e",
        "/Ö/" => "e",
        "/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/" => "",
        "/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/" => "",
        "/Ç|Ć|Ĉ|Ċ|Č/" => "",
        "/ç|ć|ĉ|ċ|č/" => "",
        "/Ð|Ď|Đ/" => "",
        "/ð|ď|đ/" => "",
        "/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/" => "",
        "/è|é|ê|ë|ē|ĕ|ė|ę|ě/" => "",
        "/Ĝ|Ğ|Ġ|Ģ/" => "",
        "/ĝ|ğ|ġ|ģ/" => "",
        "/Ĥ|Ħ/" => "",
        "/ĥ|ħ/" => "",
        "/Ì|Í|Î|Ï|Ĩ|Ī| Ĭ|Ǐ|Į|İ/" => "",
        "/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/" => "",
        "/Ĵ/" => "",
        "/ĵ/" => "",
        "/Ķ/" => "",
        "/ķ/" => "",
        "/Ĺ|Ļ|Ľ|Ŀ|Ł/" => "",
        "/ĺ|ļ|ľ|ŀ|ł/" => "",
        "/Ñ|Ń|Ņ|Ň/" => "",
        "/ñ|ń|ņ|ň|ŉ/" => "",
        "/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/" => "",
        "/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/" => "",
        "/Ŕ|Ŗ|Ř/" => "",
        "/ŕ|ŗ|ř/" => "",
        "/Ś|Ŝ|Ş|Ș|Š/" => "",
        "/ś|ŝ|ş|ș|š|ſ/" => "",
        "/Ţ|Ț|Ť|Ŧ/" => "",
        "/ţ|ț|ť|ŧ/" => "",
        "/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/" => "",
        "/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/" => "",
        "/Ý|Ÿ|Ŷ/" => "",
        "/ý|ÿ|ŷ/" => "",
        "/Ŵ/" => "",
        "/ŵ/" => "",
        "/Ź|Ż|Ž/" => "",
        "/ź|ż|ž/" => "",
        "/Æ|Ǽ/" => "E",
        "/ß/" => "s",
        "/Ĳ/" => "J",
        "/ĳ/" => "j",
        "/Œ/" => "E",
        "/ƒ/" => ""];
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
