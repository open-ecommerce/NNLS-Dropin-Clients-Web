<?php
/**
* theme functions.
*
* @link http://open-ecommeerce.org
* @since oewp 1.0.0
*/
class WP_HTML_Compression
{
    // found at http://www.intert3chmedia.net/2011/12/minify-html-javascript-css-without.html
    // Settings
    protected $compress_css = true;
    protected $compress_js = true;
    protected $info_comment = true;
    protected $remove_comments = true;

    // Variables
    protected $html;
    public function __construct($html)
    {
        if (!empty($html)) {
            $this->parseHTML($html);
        }
    }
    public function __toString()
    {
        return $this->html;
    }
    protected function bottomComment($raw, $compressed)
    {
        $raw = strlen($raw);
        $compressed = strlen($compressed);

        $savings = ($raw - $compressed) / $raw * 100;

        $savings = round($savings, 2);

        return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
    }
    protected function minifyHTML($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        // Variable reused for output
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {
                if (!empty($token['script'])) {
                    $strip = $this->compress_js;
                } elseif (!empty($token['style'])) {
                    $strip = $this->compress_css;
                } elseif ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;

                    // Don't print the comment
                    continue;
                } elseif ($this->remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        // Remove any HTML comments, except MSIE conditional comments
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                } elseif ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    } else {
                        $strip = true;

                        // Remove any empty attributes, except:
                        // action, alt, content, src
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                        // Remove any space before the end of self-closing XHTML tags
                        // JavaScript excluded
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }

            if ($strip) {
                $content = $this->removeWhiteSpace($content);
            }

            $html .= $content;
        }

        return $html;
    }

    public function parseHTML($html)
    {
        $this->html = $this->minifyHTML($html);

        if ($this->info_comment) {
            $this->html .= "\n".$this->bottomComment($html, $this->html);
        }
    }

    protected function removeWhiteSpace($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n",  '', $str);
        $str = str_replace("\r",  '', $str);

        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }
}

function wp_html_compression_finish($html)
{
    return new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
    ob_start('wp_html_compression_finish');
}
//not yet add_action('get_header', 'wp_html_compression_start');

    //revienta el content
  //add_filter("the_content", "plugin_oeContentFilter");
    function plugin_oeContentFilter($content)
    {
        // Take the existing content and return a subset of it
    return substr($content, 0, 300);
    }

    function catch_that_image()
    {
        global $post, $posts;
        $first_img = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $first_img = $matches [1] [0];

        if (empty($first_img)) { //Defines a default image
            $first_img = get_template_directory_uri().'/assets/images/defaults/mds-pages-headers.jpg';
        }

        return $first_img;
    }

    function catch_that_image_by_post($post, $size)
    {

        $first_img = '';

        // first check if has feature image but not allways work
        if (has_post_thumbnail($post->ID)) {
            $first_img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
            $first_img = $first_img[0];
        }

        // if didnt work or dosn't have it
        if (empty($first_img)) {
          ob_start();
            ob_end_clean();
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
            $first_img = isset($matches [1] [0]);
            //$first_img = "estoy aca";

          if (empty($first_img)) {
                if ($size == 'small') {
                    $first_img = get_template_directory_uri().'/assets/images/defaults/mds-post-small-200x200.jpg';
                } else {
                    $first_img = get_template_directory_uri().'/assets/images/defaults/mds-post-medium-700x300.jpg';
                }
          }
        }

        return $first_img;
    }

    function get_first_embed_media($post_id)
    {
        $post = get_post($post_id);
        $content = do_shortcode(apply_filters('the_content', $post->post_content));
        $embeds = get_media_embedded_in_content($content);

        if (!empty($embeds)) {
            //return first embed
            return $embeds[0];
        } else {
            //No embeds found
            return false;
        }
    }

    // Original PHP code by Chirp Internet: www.chirp.com.au
    // Please acknowledge use of this code by including this header.

    function myTruncate($string, $limit, $break = '.', $pad = '...')
    {
        // return with no change if string is shorter than $limit
      if (strlen($string) <= $limit) {
          return $string;
      }

      // is $break present between $limit and the end of the string?
      if (false !== ($breakpoint = strpos($string, $break, $limit))) {
          if ($breakpoint < strlen($string) - 1) {
              $string = substr($string, 0, $breakpoint).$pad;
          }
      }

        return $string;
    }

    # Crawls the pages tree up to top level page ancestor
    # and returns that page as object
    function get_page_ancestor($page_id)
    {
        $page_obj = get_page($page_id);
        while ($page_obj->post_parent != 0) {
            $page_obj = get_page($page_obj->post_parent);
        }

        return get_page($page_obj->ID);
    }
