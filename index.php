<?php get_header(); ?>

<div class="py-20 px-6">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="max-w-4xl mx-auto">
            <?php if (get_post_type() === 'viral_videos'): ?>
                <!-- Video Player Section -->
                <div class="bg-black rounded-xl overflow-hidden aspect-video mb-8 border border-white/10 shadow-2xl">
                     <?php 
                     $embed = get_post_meta(get_the_ID(), '_video_embed', true);
                     $url = get_post_meta(get_the_ID(), '_video_url', true);
                     if ($embed) {
                         echo '<div class="w-full h-full flex items-center justify-center">' . $embed . '</div>';
                     } elseif ($url) {
                         // Simple embed fallback
                         echo '<iframe src="' . str_replace('watch?v=', 'embed/', $url) . '" class="w-full h-full" frameborder="0" allowfullscreen></iframe>';
                     } else {
                         the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']);
                     }
                     ?>
                </div>
                
                <div class="flex flex-wrap gap-4 mb-6">
                    <?php 
                    $cats = get_the_terms(get_the_ID(), 'video_category');
                    if ($cats) foreach($cats as $c) echo '<span class="bg-primary/20 text-primary px-3 py-1 rounded-full text-sm font-medium">'.$c->name.'</span>';
                    ?>
                </div>
            <?php endif; ?>

            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6"><?php the_title(); ?></h1>
            
            <div class="prose prose-invert max-w-none text-muted-foreground">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
