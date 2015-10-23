<?php
/*
Plugin Name:  Rewrite content links
Plugin URI:   https://github.com/martybel
Description:  Rewrite content links using the SITEURL defines
Version:      1.0.0
Author:       Martybel
Author URI:   https://github.com/martybel
License:      MIT License
*/

namespace Martybel\ContentRewriter;

// Only work when we have a WP_HOME define that overrules the database
if ( defined('WP_HOME')) {
   $rewriter = new ContentUrlRewriter();
   $rewriter->register();
}


/**
 * Rewrite content Url's when we move domains
 * You can also define other domains/urls that should be replaced using WP_OLD_URLS
 *
 * @package Martybel\ContentRewriter
 */
class ContentUrlRewriter
{
   /**
    * The domain/url that was used to set up the site
    * @var string
    */
   protected $originalDomain = [];

   /**
    * The new domain that the site listens to now
    * @var string
    */
   protected $newDomain;

   /**
    * Initiates the rewriter
    */
   public function __construct()
   {
      $this->loadOriginalDomain();
      $this->newDomain  = get_bloginfo('url');
   }

   /**
    * Registers Wordpress filter hooks that will do the rewriting
    */
   public function register()
   {
      // Only rewrite when we are on another domain.. otherwise this just does not make sense
      if ( is_array($this->originalDomain) || $this->newDomain !== $this->originalDomain ) {
         \add_filter('the_content',[$this,'rewriteUrls'],99);
         \add_filter('get_the_excerpt',[$this,'rewriteUrls'],99);
      }
   }

   /**
    * Rewrite content url's in the content and excerpt
    *
    * @param string $content
    *
    * @return string
    */
   public function rewriteUrls($content)
   {
      // To ensure we also replace location moving of the wp-content folder
      if (defined('WP_CONTENT_URL')) {
         foreach( $this->originalDomain as $domain ) {
            $subjects[] = 'src="' . $domain . '/wp-content';
            $subjects[] = 'href="' . $domain . '/wp-content';
            $targets[]  = 'src="' . WP_CONTENT_URL;
            $targets[]  = 'href="' . WP_CONTENT_URL;
         }
      }

      // replace links and images
      foreach( $this->originalDomain as $domain ) {
         $subjects[] = ' src="' . $domain;
         $subjects[] = ' href="' . $domain;
         $targets[]  = ' src="' . $this->newDomain;
         $targets[]  = ' href="' . $this->newDomain;
      }
      return str_replace($subjects,$targets,$content);
   }

   /**
    * Loads the domain that was used to set up the site by loading the siteurl option
    */
   private function loadOriginalDomain()
   {
      if ( defined('WP_OLD_URLS') ) {
         $this->originalDomain = explode(';',WP_OLD_URLS);
      }

      $this->originalDomain[] = get_option('siteurl');
   }
}
