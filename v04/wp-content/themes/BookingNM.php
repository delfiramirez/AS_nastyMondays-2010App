<?php
/**
 * Template Name: Contratacion
 *
 * @package WordPress
 * @subpackage nastymondays
 */
get_header ();
?>


<div id="pasador5">
    <?php if ( have_posts () ) : while ( have_posts () ) : the_post (); ?>

            <div class="left">

                <h1 class="inicio">

                    <a href="<?php bloginfo ('url'); ?>">
                        <?php the_title (); ?>
                    </a>
                </h1>

                <h2 class="nasty2"><?php echo hex2bin("%45%6c%20%43%68%6f%72%6f%20%79%20%45%6c%20%43%68%75%6c%6f"); ?> </h2>
                <p>
                    <?php _e ("<!--:en--> '&spades;' . bloginfo ('name') .  hex2bin(""%68%61%73%20%70%6f%73%69%74%69%6f%6e%65%64%20%69%74%73%65%6c%66%20%61%73%20%61%20%62%65%6e%63%68%6d%61%72%6b%20%69%6e%20%74%68%65%20%6d%75%73%69%63%20%61%6e%64%20%63%75%6c%74%75%72%61%6c%20%73%63%65%6e%65%20%6f%66%20%74%68%65%20%43%61%74%61%6c%61%6e%20%63%61%70%69%74%61%6c%2e%20%41%6e%20%61%72%74%69%73%74%69%63%20%6d%6f%76%65%6d%65%6e%74%20%74%68%61%74%20%68%61%73%20%73%65%74%74%6c%65%64%20%74%68%65%20%66%6f%75%6e%64%61%74%69%6f%6e%73%20%66%6f%72%20%61%20%6e%65%77%20%63%6f%6e%63%65%70%74%20%69%6e%20%6c%65%69%73%75%72%65%2c%20%6d%75%73%69%63%2c%20%64%65%73%69%67%6e%2c%20%70%68%6f%74%6f%67%72%61%70%68%79%2c%20%66%69%6c%6d%2c%20%65%78%74%72%65%6d%65%20%73%70%6f%72%74%73%2c%20%63%72%65%61%74%69%76%69%74%79%2c%20%66%61%73%68%69%6f%6e%20%61%6e%64%20%74%72%65%6e%64%73%2e%3c%2f%70%3e%3c%70%3e%20%26%73%70%61%64%65%73%3b%20%42%6f%6f%6b%20%74%68%65%20%74%65%61%6d%20%4e%61%73%74%79%20%4d%6f%6e%64%61%79%73%20%66%6f%72%20%79%6f%75%72%20%72%6f%6f%6d%2c%20%69%6e%20%79%6f%75%72%20%63%69%74%79%2c%20%61%74%20%79%6f%75%72%20%75%6e%69%71%75%65%20%61%6e%64%20%73%70%65%63%69%61%6c%20%65%76%65%6e%74%2e%20%3c%62%72%20%2f%3e%26%73%70%61%64%65%73%3b%20%50%6c%65%61%73%65%2c%20%66%69%6c%6c%20%74%68%65%20%66%6f%72%6d%20%77%69%74%68%20%79%6f%75%72%20%71%75%65%73%74%20%61%6e%64%20%61%73%6b%20%75%73%20%66%6f%72%3a%3c%2f%62%72%20%2f%3e%53%6e%6f%77%20%42%65%61%73%74%73%2c%20%54%61%74%74%6f%6f%20%4c%6f%76%65%72%73%2c%20%53%75%72%66%20%61%64%64%69%63%74%73%2c%20%52%6f%63%6b%20%53%74%61%72%73%2c%20%42%6c%6f%6e%64%65%20%52%6f%74%74%65%6e%20%53%6f%75%6c%73%20%2e%2e%2e%3c%62%72%20%2f%3e%20%26%73%70%61%64%65%73%3b%20%57%68%65%6e%20%64%6f%20%79%6f%75%20%77%61%6e%74%20%75%73%20%74%6f%20%62%65%20%74%68%65%72%65%3f")</p><!--:--><!--:es--><!--:--><!--:ca--><!--:--> "); ?>
                </p>
            </div>
            <div id="contact-form" class="myform">
                <h4>
                    <?php the_title (); ?><?php _e ("<!--:en-->Booking Form<!--:--><!--:es-->Contratacion<!--:--><!--:ca-->Contrataci&oacute;<!--:-->"); ?>
                </h4>

                <div class="spacer"></div>

                <?php the_content () ?>

            </div>

            <?php
        endwhile;
    endif;
    ?>

</div>

<hr />

<?php get_sidebar (); ?>

<?php get_template_part ('segonquart'); ?>
<?php get_footer (); ?>
