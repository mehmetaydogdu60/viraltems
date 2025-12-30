<?php
/*
Template Name: Hakkımızda
*/
get_header();
?>

<section class="py-10 bg-gradient-to-b from-primary/10 to-background border-b border-white/5">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?php the_title(); ?></h1>
    </div>
</section>

<section class="py-12 px-6 container mx-auto max-w-4xl">
    <div class="prose prose-invert max-w-none">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>