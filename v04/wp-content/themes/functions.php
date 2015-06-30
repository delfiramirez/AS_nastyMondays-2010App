<?php

function custom_admin_footer ()
    {
    echo '<a href="http://segonquart.net">Nasty Mondays Website designed and developed by Delfi Ramirez for Nasty Garage SL.</a>';
    }

add_filter ('admin_footer_text', 'custom_admin_footer');


if ( function_exists ('add_theme_support') )
    {
    add_theme_support ('custom-background');
    add_theme_support ('automatic-feed-links');
    add_post_type_support ('page', 'excerpt');
    add_theme_support ('menus');
    add_theme_support ('nav-menus');
    add_theme_support ('custom-header');
    }
add_custom_background ();
add_editor_style ();
add_theme_support ('wp_paginate');

/**
 * Archive que nos permite incluir enlaces directos - sin la etiqueta A - en nuestros POSTS.
 *
 */
add_filter ('the_content', 'make_clickable');

/**
 * Archive que nos permite incluir extractos EXCERPTS de nuestros POSTS.
 *
 */
add_post_type_support ('page', 'excerpt');


remove_action ('wp_head', 'rsd_link');
remove_action ('wp_head', 'wp_generator');

remove_action ('wp_head', 'index_rel_link');
remove_action ('wp_head', 'wlwmanifest_link');

remove_action ('wp_head', 'start_post_rel_link', 10, 0);
remove_action ('wp_head', 'parent_post_rel_link', 10, 0);
remove_action ('wp_head', 'adjacent_posts_rel_link', 10, 0);


/**
 * Archive que nos permite utilizar miniaturas de imagen (THUMBNAIL) en nuestro POST.
 * Tamaño por defecto 150x150
 *
 */
add_theme_support ('post-thumbnails');
set_post_thumbnail_size (150, 150, true);
add_image_size ('featured-thumbnail', 150, 150);
add_image_size ('seccion-thumbnail', 250, 250);


/**
 * Archive que nos permite identificar e insertar en el elemento BODY una clase de CSS que permite dirigir el diseño segun el navegador.
 *
 */
add_filter ('body_class', 'browser_body_class');

function browser_body_class ($classes)
    {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
    if ( $is_lynx )
        $classes[] = 'lynx';
    elseif ( $is_gecko )
        $classes[] = 'gecko';
    elseif ( $is_opera )
        $classes[] = 'opera';
    elseif ( $is_NS4 )
        $classes[] = 'ns4';
    elseif ( $is_safari )
        $classes[] = 'safari';
    elseif ( $is_chrome )
        $classes[] = 'chrome';
    elseif ( $is_IE )
        $classes[] = 'ie';
    else
        $classes[] = 'unknown';
    if ( $is_iphone )
        $classes[] = 'iphone';
    return $classes;
    }

// category id in body and post class
function category_id_class ($classes)
    {
    global $post;
    foreach ( (get_the_category ($post->ID) ) as $category )
        $classes [] = 'cat-' . $category->cat_ID . '-id';
    return $classes;
    }

add_filter ('post_class', 'category_id_class');
add_filter ('body_class', 'category_id_class');

/**
 * Archive que nos perite obtener el EXCERPT de un POST
 *
 */
$myExcerpt = get_the_excerpt ();
$tags      = array ( '<p>', '</p>' );
$myExcerpt = str_replace ($tags, '', $myExcerpt);
echo $myExcerpt;

/**
 * Archive que nos permite encriptar los emails que figuren en el site
 *
 */
function wpe_secure_mail ($atts)
    {
    extract (shortcode_atts (array (
        "mailto" => '',
        "txt"    => ''
                    ), $atts));
    $mailto = antispambot ($mailto);
    $txt    = antispambot ($txt);
    return '<a href="mailto:' . $mailto . '">' . $txt . '</a>';
    }

if ( function_exists ('add_shortcode') )
    add_shortcode ('sm', 'wpe_secure_mail');

/**
 * Archive que nos permite limpiar el codigo HTML mal formatedao de los POSTS en modo "edicion HTML"
 *
 */
function clean_bad_content ($bPrint = false)
    {
    global $post;
    $szPostContent  = $post->post_content;
    $szRemoveFilter = array ( "~<p[^>]*>\s?</p>~", "~<a[^>]*>\s?</a>~", "~<font[^>]*>~", "~<\/font>~", "~style\=\"[^\"]*\"~", "~<span[^>]*>\s?</span>~" );
    $szPostContent  = preg_replace ($szRemoveFilter, '', $szPostContent);
    $szPostContent  = apply_filters ('the_content', $szPostContent);
    if ( $bPrint == false )
        return $szPostContent;
    else
        echo $szPostContent;
    }

/**
 * Archive que permite mostar el contenido MENSUAL en el SITEMAP
 *
 */
function bm_displayArchives ()
    {
    global $month, $wpdb, $wp_version;

    // a mysql query to get the list of distinct years and months that posts have been created
    $sql = 'SELECT
            DISTINCT YEAR(post_date) AS year,
            MONTH(post_date) AS month,
            count(ID) as posts
        FROM ' . $wpdb->posts . '
        WHERE post_status="publish"
            AND post_type="post"
            AND post_password=""
        GROUP BY YEAR(post_date),
            MONTH(post_date)
        ORDER BY post_date DESC';

    // use get_results to do a query directly on the database
    $archiveSummary = $wpdb->get_results ($sql);

    // if there are any posts
    if ( $archiveSummary )
        {
        // loop through the posts
        foreach ( $archiveSummary as $date )
            {
            // reset the query variable
            unset ($bmWp);
            // create a new query variable for the current month and year combination
            $bmWp = new WP_Query ('year=' . $date->year . '&monthnum=' . zeroise ($date->month, 2) . '&posts_per_page=-1' . '&cat=-126,-127' . '&paged=' . $paged);

            // if there are any posts for that month display them
            if ( $bmWp->have_posts () )
                {
                // display the archives heading
                $url  = get_month_link ($date->year, $date->month);
                $text = $month[ zeroise ($date->month, 2) ] . ' ' . $date->year;

                echo get_archives_link ($url, $text, '', '<div class="sitemap"><h5>', '</h5>');
                echo '<ul>';

                // display an unordered list of posts for the current month
                while ( $bmWp->have_posts () )
                    {
                    $bmWp->the_post ();
                    echo '<li>&spades; <a href=" ' . get_permalink ($bmWp->post) . '" title="' . wp_specialchars ($text, 1) . '">' . wptexturize ($bmWp->post->post_title) . '</a></li>';
                    }

                echo '</ul></div>';
                }
            }
        }
    }

/**
 * Archive que remueve el atributo NOFOLLOW de los links del POST
 *
 */
add_filter ('the_content', 'remove_nofollow');

function remove_nofollow ($string)
    {
    $string = str_ireplace (' rel="nofollow"', '', $string);
    return $string;
    }

/**
 * Archive para Optimizacion SEO eliminando palabras del SLUG del POST
 *
 */
add_filter ('name_save_pre', 'seo_slugs', 0);

function seo_slugs ($slug)
    {
    // We don't want to change an existing slug
    if ( $slug )
        return $slug;
    global $wpdb;
    $seo_slug       = strtolower (stripslashes ($_POST[ 'post_title' ]));
    $seo_slug       = preg_replace ('/&.+?;/', '', $seo_slug); // kill HTML entities
    // kill anything that is not a letter, digit, space or apostrophe
    $seo_slug       = preg_replace ("/[^a-zA-Z0-9 \']/", "", $seo_slug);
    // Turn it to an array and strip common words by comparing against c.w. array
    $seo_slug_array = array_diff (split (" ", $seo_slug), seo_slugs_stop_words ());
    // Turn the sanitized array into a string
    $seo_slug       = join ("-", $seo_slug_array);
    return $seo_slug;
    }

function seo_slugs_stop_words ()
    {
    return array ( "a", "able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain't", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "a's", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "b", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "c", "came", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "c's", "currently", "d", "dare", "daren't", "definitely", "described", "despite", "did", "didn't", "different", "directly", "do", "does", "doesn't", "doing", "done", "don't", "down", "downwards", "during", "e", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "f", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "g", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "h", "had", "hadn't", "half", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "hello", "help", "hence", "her", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hundred", "i", "i'd", "ie", "if", "ignored", "i'll", "i'm", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "j", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "l", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "m", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "n", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn't", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "ninety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "o", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one's", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "p", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "q", "que", "quite", "qv", "r", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "s", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "t", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "t's", "twice", "two", "u", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "w", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "will", "willing", "wish", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "x", "y", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "z", "zero" );
    }

/**
 * Archive RSS FEEDS
 *
 */
add_filter ('pre_get_posts', 'feedFilter');

function feedFilter ($query)
    {
    if ( $query->is_feed )
        {
        $query->set ('post_type', 'any');
        $query->set ('post_parent', '0');
        }
    return $query;
    }

/**
 * Archive evitar HTML paste en COMMENTS ( Evitamos hacks y enlaces no deseados)
 *
 */
function plc_comment_post ($incoming_comment)
    {

    $incoming_comment[ 'comment_content' ] = htmlspecialchars ($incoming_comment[ 'comment_content' ]);

    $incoming_comment[ 'comment_content' ] = str_replace ("'", '&apos;', $incoming_comment[ 'comment_content' ]);

    return( $incoming_comment );
    }

function plc_comment_display ($comment_to_display)
    {

    $comment_to_display = str_replace ('&apos;', "'", $comment_to_display);

    return $comment_to_display;
    }

add_filter ('preprocess_comment', 'plc_comment_post', '', 1);
add_filter ('comment_text', 'plc_comment_display', '', 1);
add_filter ('comment_text_rss', 'plc_comment_display', '', 1);
add_filter ('comment_excerpt', 'plc_comment_display', '', 1);

/**
 * Archive que nos permite evitar SPAM en COMMENTS
 *
 */
function in_comment_post_like ($string, $array)
    {
    foreach ( $array as $ref )
        {
        if ( strstr ($string, $ref) )
            {
            return true;
            }
        }
    return false;
    }

function drop_bad_comments ()
    {
    if ( !empty ($_POST[ 'comment' ]) )
        {
        $post_comment_content = $_POST[ 'comment' ];
        $lower_case_comment   = strtolower ($_POST[ 'comment' ]);
        $bad_comment_content  = array (
            'viagra',
            'hydrocodone',
            'hair loss',
            '[url=http',
            '[link=http',
            'xanax',
            'tramadol',
            'konfuxi',
            'russian girls',
            'russian brides',
            'lorazepam',
            'adderall',
            'dexadrine',
            'no prescription',
            'oxycontin',
            'without a prescription',
            'sex pics',
            'family incest',
            'online casinos',
            'online dating',
            'cialis',
            'best forex',
            'amoxicillin'
        );
        if ( in_comment_post_like ($lower_case_comment, $bad_comment_content) )
            {
            $comment_box_text = wordwrap (trim ($post_comment_content), 80, "\n  ", true);
            $txtdrop          = fopen ('/var/log/httpd/wp_post-logger/nullamatix.com-text-area_dropped.txt', 'a');
            fwrite ($txtdrop, "  --------------\n  [COMMENT] = " . $post_comment_content . "\n  --------------\n");
            fwrite ($txtdrop, "  [SOURCE_IP] = " . $_SERVER[ 'REMOTE_ADDR' ] . " @ " . date ("F j, Y, g:i a") . "\n");
            fwrite ($txtdrop, "  [USERAGENT] = " . $_SERVER[ 'HTTP_USER_AGENT' ] . "\n");
            fwrite ($txtdrop, "  [REFERER  ] = " . $_SERVER[ 'HTTP_REFERER' ] . "\n");
            fwrite ($txtdrop, "  [FILE_NAME] = " . $_SERVER[ 'SCRIPT_NAME' ] . " - [REQ_URI] = " . $_SERVER[ 'REQUEST_URI' ] . "\n");
            fwrite ($txtdrop, '--------------**********------------------' . "\n");
            header ("HTTP/1.1 406 Not Acceptable");
            header ("Status: 406 Not Acceptable");
            header ("Connection: Close");
            wp_die (__ ('bang bang.'));
            }
        }
    }

add_action ('init', 'drop_bad_comments');

/**
 * Archive que nos permite enlace directo de titular externo Prensa
 *
 */
function prensa_title ()
    {
    global $post;
    $thePostID = $post->ID;
    $post_id   = get_post ($thePostID);
    $title     = $post_id->post_title;
    $perm      = get_permalink ($post_id);
    $post_keys = array ();
    $post_val  = array ();
    $post_keys = get_post_custom_keys ($thePostID);

    if ( !empty ($post_keys) )
        {
        foreach ( $post_keys as $pkey )
            {
            if ( $pkey == 'url1' || $pkey == 'title_url' || $pkey == 'url_title' )
                {
                $post_val = get_post_custom_values ($pkey);
                }
            }
        if ( empty ($post_val) )
            {
            $link = $perm;
            }
        else
            {
            $link = $post_val[ 0 ];
            }
        }
    else
        {
        $link = $perm;
        }
    echo '<h2 class="enlaceprensa"><a href="' . $link . '" rel="bookmark" title="' . $title . '">' . $title . '</a></h2>';
    }

//Activamos con funcion php print_post_title()


function get_fbimage ()
    {
    $src = wp_get_attachment_image_src (get_post_thumbnail_id ($post->ID), '', '');
    if ( has_post_thumbnail ($post->ID) )
        {
        $fbimage = $src[ 0 ];
        }
    else
        {
        global $post, $posts;
        $fbimage = '';
        $output  = preg_match_all ('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        $fbimage = $matches [ 1 ] [ 0 ];
        }
    if ( empty ($fbimage) )
        {
        $fbimage = "http://nastymondays.com/src/images/logo.gif";
        }
    return $fbimage;
    }

add_theme_support (‘post-formats ’, array ( ‘aside’, ‘ chat’, ‘ gallery’, ‘ image’, ‘ link’, ‘ quote’, ‘ status’, ‘ video’, ‘ audio’ ));
?>