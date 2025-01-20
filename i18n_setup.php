<?php
/**
 * Verifies if the given $locale is supported in the project
 * @param string $locale
 * @return bool
 */
function valid($locale) {
    return in_array($locale, ['en_US','en','pt_BR','pt']);
}

//setting the source/default locale, for informational purposes
$lang = 'pt_BR';
if (isset($_GET['lang']) && valid($_GET['lang'])) {
    // the locale can be changed through the query-string
    $lang = $_GET['lang'];    //you should sanitize this!
    setcookie('lang', $lang); //it's stored in a cookie so it can be reused
} elseif (isset($_COOKIE['lang']) && valid($_COOKIE['lang'])) {
    // if the cookie is present instead, let's just keep it
    $lang = $_COOKIE['lang']; //you should sanitize this!
} elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    // default: look for the languages the browser says the user accepts
    $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    array_walk($langs, function (&$lang) { $lang = strtr(strtok($lang, ';'), ['-' => '_']); });
    foreach ($langs as $browser_lang) {
        if (valid($browser_lang)) {
            $lang = $browser_lang;
            break;
        }
    }
}
// here we define the global system locale given the found language
putenv("LANG=$lang");
putenv("LANGUAGE=$lang");

// this might be useful for date functions (LC_TIME) or money formatting (LC_MONETARY), for instance
setlocale(LC_ALL, $lang.'.utf8');

// this will make Gettext look for ../locales/<lang>/LC_MESSAGES/webapp.mo
bindtextdomain('webapp', 'locales');

// indicates in what encoding the file should be read
bind_textdomain_codeset('webapp', 'UTF-8');

// here we indicate the default domain the gettext() calls will respond to
textdomain('webapp');

// this would look for the string in forum.mo instead of main.mo
// echo dgettext('forum', 'Welcome back!');
?>