<?php get_header(); ?>

<div class="py-20 px-6">
    <div class="container mx-auto max-w-4xl">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="bg-white/5 border border-white/10 rounded-2xl p-8">
            <h1 class="text-4xl font-bold text-white mb-6"><?php the_title(); ?></h1>
            <div class="prose prose-invert max-w-none">
                <?php the_content(); ?>
            </div>
        </article>
        <?php endwhile; endif; ?>
    </div>
</div>

<?php get_footer(); ?>
