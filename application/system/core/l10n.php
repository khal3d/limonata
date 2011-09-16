<?php if (!defined('LIMONATA')) exit('No direct script access allowed');

include_once( COREPATH . 'gettext' . DS . 'gettext.php' );
include_once( COREPATH . 'gettext' . DS . 'streams.php' );

class LIM_l10n
{
	public $text_domains = array();
	public $default_domain = 'messages';
	public $LC_CATEGORIES = array('LC_CTYPE', 'LC_NUMERIC', 'LC_TIME', 'LC_COLLATE', 'LC_MONETARY', 'LC_MESSAGES', 'LC_ALL');
	public $EMULATEGETTEXT = FALSE;
	public $CURRENTLOCALE = '';
	
	public $domain;
	
	public function __construct()
	{
		// LC_MESSAGES is not available if php-gettext is not loaded
		// while the other constants are already available from session extension.
		if (!defined('LC_MESSAGES'))
			define('LC_MESSAGES', 5);
		
		/* Object to hold a single domain included in $text_domains. */
		$this->domain->l10n;
		$this->domain->path;
		$this->domain->codeset;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Return a list of locales to try for any POSIX-style locale specification.
	 *
	 * @param String $locale
	 */
	function get_list_of_locales($locale)
	{
		/* Figure out all possible locale names and start with the most
		 * specific ones.  I.e. for sr_CS.UTF-8@latin, look through all of
		 * sr_CS.UTF-8@latin, sr_CS@latin, sr@latin, sr_CS.UTF-8, sr_CS, sr.
		 */
		$locale_names = array();
		$lang         = NULL;
		$country      = NULL;
		$charset      = NULL;
		$modifier     = NULL;
		if ($locale) {
			if (preg_match("/^(?P<lang>[a-z]{2,3})" // language code
				. "(?:_(?P<country>[A-Z]{2}))?" // country code
				. "(?:\.(?P<charset>[-A-Za-z0-9_]+))?" // charset
				. "(?:@(?P<modifier>[-A-Za-z0-9_]+))?$/", // @ modifier
				$locale, $matches)) {
				if (isset($matches["lang"]))
					$lang = $matches["lang"];
				if (isset($matches["country"]))
					$country = $matches["country"];
				if (isset($matches["charset"]))
					$charset = $matches["charset"];
				if (isset($matches["modifier"]))
					$modifier = $matches["modifier"];
				
				if ($modifier) {
					if ($country) {
						if ($charset)
							array_push($locale_names, "${lang}_$country.$charset@$modifier");
						array_push($locale_names, "${lang}_$country@$modifier");
					} elseif ($charset)
						array_push($locale_names, "${lang}.$charset@$modifier");
					array_push($locale_names, "$lang@$modifier");
				}
				if ($country) {
					if ($charset)
						array_push($locale_names, "${lang}_$country.$charset");
					array_push($locale_names, "${lang}_$country");
				} elseif ($charset)
					array_push($locale_names, "${lang}.$charset");
				array_push($locale_names, $lang);
			}
			
			// If the locale name doesn't match POSIX style, just include it as-is.
			if (!in_array($locale, $locale_names))
				array_push($locale_names, $locale);
		}
		return $locale_names;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Utility function to get a StreamReader for the given text domain.
	 * 
	 * @param unknown_type $domain
	 * @param int $category
	 * @param unknown_type $enable_cache
	 */
	function _get_reader($domain = null, $category = 5, $enable_cache = TRUE)
	{
		global $text_domains, $default_domain, $LC_CATEGORIES;
		if (!isset($domain))
			$domain = $default_domain;
		if (!isset($text_domains[$domain]->l10n)) {
			// get the current locale
			$locale     = _setlocale(LC_MESSAGES, 0);
			$bound_path = isset($text_domains[$domain]->path) ? $text_domains[$domain]->path : './';
			$subpath    = $LC_CATEGORIES[$category] . "/$domain.mo";
			
			$locale_names = get_list_of_locales($locale);
			$input        = null;
			foreach ($locale_names as $locale) {
				$full_path = $bound_path . $locale . "/" . $subpath;
				if (file_exists($full_path)) {
					$input = new FileReader($full_path);
					break;
				}
			}
			
			if (!array_key_exists($domain, $text_domains)) {
				// Initialize an empty domain object.
				$text_domains[$domain] = new domain();
			}
			$text_domains[$domain]->l10n = new gettext_reader($input, $enable_cache);
		}
		return $text_domains[$domain]->l10n;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Returns whether we are using our emulated gettext API or PHP built-in one.
	 */
	function locale_emulation()
	{
		global $EMULATEGETTEXT;
		return $EMULATEGETTEXT;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Checks if the current locale is supported on this system.
	 */
	function _check_locale_and_function($function = false)
	{
		global $EMULATEGETTEXT;
		if ($function and !function_exists($function))
			return false;
		return !$EMULATEGETTEXT;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Get the codeset for the given domain.
	 */
	function _get_codeset($domain = null)
	{
		global $text_domains, $default_domain, $LC_CATEGORIES;
		if (!isset($domain))
			$domain = $default_domain;
		return (isset($text_domains[$domain]->codeset)) ? $text_domains[$domain]->codeset : ini_get('mbstring.internal_encoding');
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Convert the given string to the encoding set by bind_textdomain_codeset.
	 */
	function _encode($text)
	{
		$source_encoding = mb_detect_encoding($text);
		$target_encoding = _get_codeset();
		if ($source_encoding != $target_encoding) {
			return mb_convert_encoding($text, $target_encoding, $source_encoding);
		} else {
			return $text;
		}
	}
	
	## ---------------------------------------------------------------
	
	// Custom implementation of the standard gettext related functions
	
	/**
	 * Returns passed in $locale, or environment variable $LANG if $locale == ''.
	 */
	function _get_default_locale($locale)
	{
		if ($locale == '') // emulate variable support
			return getenv('LANG');
		else
			return $locale;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Sets a requested locale, if needed emulates it.
	 */
	function _setlocale($category, $locale)
	{
		global $CURRENTLOCALE, $EMULATEGETTEXT;
		if ($locale === 0) { // use === to differentiate between string "0"
			if ($CURRENTLOCALE != '')
				return $CURRENTLOCALE;
			else
			// obey LANG variable, maybe extend to support all of LC_* vars
			// even if we tried to read locale without setting it first
				return _setlocale($category, $CURRENTLOCALE);
		} else {
			if (function_exists('setlocale')) {
				$ret = setlocale($category, $locale);
				if (($locale == '' and !$ret) or // failed setting it by env
					($locale != '' and $ret != $locale)) { // failed setting it
					// Failed setting it according to environment.
					$CURRENTLOCALE  = _get_default_locale($locale);
					$EMULATEGETTEXT = 1;
				} else {
					$CURRENTLOCALE  = $ret;
					$EMULATEGETTEXT = 0;
				}
			} else {
				// No function setlocale(), emulate it all.
				$CURRENTLOCALE  = _get_default_locale($locale);
				$EMULATEGETTEXT = 1;
			}
			// Allow locale to be changed on the go for one translation domain.
			global $text_domains, $default_domain;
			if (array_key_exists($default_domain, $text_domains)) {
				unset($text_domains[$default_domain]->l10n);
			}
			return $CURRENTLOCALE;
		}
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Sets the path for a domain.
	 */
	function _bindtextdomain($domain, $path)
	{
		global $text_domains;
		// ensure $path ends with a slash ('/' should work for both, but lets still play nice)
		if (substr(php_uname(), 0, 7) == "Windows") {
			if ($path[strlen($path) - 1] != '\\' and $path[strlen($path) - 1] != '/')
				$path .= '\\';
		} else {
			if ($path[strlen($path) - 1] != '/')
				$path .= '/';
		}
		if (!array_key_exists($domain, $text_domains)) {
			// Initialize an empty domain object.
			$text_domains[$domain] = new domain();
		}
		$text_domains[$domain]->path = $path;
	}
	
	
	## ---------------------------------------------------------------
	
	/**
	 * Specify the character encoding in which the messages from the DOMAIN message catalog will be returned.
	 */
	function _bind_textdomain_codeset($domain, $codeset)
	{
		global $text_domains;
		$text_domains[$domain]->codeset = $codeset;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Sets the default domain.
	 */
	function _textdomain($domain)
	{
		global $default_domain;
		$default_domain = $domain;
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Lookup a message in the current domain.
	 */
	function _gettext($msgid)
	{
		$l10n = _get_reader();
		return _encode($l10n->translate($msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Alias for gettext.
	 */
	function __($msgid)
	{
		return _gettext($msgid);
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Plural version of gettext.
	 */
	function _ngettext($singular, $plural, $number)
	{
		$l10n = _get_reader();
		return _encode($l10n->ngettext($singular, $plural, $number));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Override the current domain.
	 */
	function _dgettext($domain, $msgid)
	{
		$l10n = _get_reader($domain);
		return _encode($l10n->translate($msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Plural version of dgettext.
	 */
	function _dngettext($domain, $singular, $plural, $number)
	{
		$l10n = _get_reader($domain);
		return _encode($l10n->ngettext($singular, $plural, $number));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Overrides the domain and category for a single lookup.
	 */
	function _dcgettext($domain, $msgid, $category)
	{
		$l10n = _get_reader($domain, $category);
		return _encode($l10n->translate($msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Plural version of dcgettext.
	 */
	function _dcngettext($domain, $singular, $plural, $number, $category)
	{
		$l10n = _get_reader($domain, $category);
		return _encode($l10n->ngettext($singular, $plural, $number));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Context version of gettext.
	 */
	function _pgettext($context, $msgid)
	{
		$l10n = _get_reader();
		return _encode($l10n->pgettext($context, $msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Override the current domain in a context gettext call.
	 */
	function _dpgettext($domain, $context, $msgid)
	{
		$l10n = _get_reader($domain);
		return _encode($l10n->pgettext($context, $msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Overrides the domain and category for a single context-based lookup.
	 */
	function _dcpgettext($domain, $context, $msgid, $category)
	{
		$l10n = _get_reader($domain, $category);
		return _encode($l10n->pgettext($context, $msgid));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Context version of ngettext.
	 */
	function _npgettext($context, $singular, $plural)
	{
		$l10n = _get_reader();
		return _encode($l10n->npgettext($context, $singular, $plural));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Override the current domain in a context ngettext call.
	 */
	function _dnpgettext($domain, $context, $singular, $plural)
	{
		$l10n = _get_reader($domain);
		return _encode($l10n->npgettext($context, $singular, $plural));
	}
	
	## ---------------------------------------------------------------
	
	/**
	 * Overrides the domain and category for a plural context-based lookup.
	 */
	function _dcnpgettext($domain, $context, $singular, $plural, $category)
	{
		$l10n = _get_reader($domain, $category);
		return _encode($l10n->npgettext($context, $singular, $plural));
	}
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
	
	
	## ---------------------------------------------------------------
	
}

/* End of file l10n.php */
/* Location: ./system/core/l10n.php */