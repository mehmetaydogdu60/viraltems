<?php
/*
Template Name: SSS
*/

get_header();

$section_title   = get_option('viralay_faq_page_title', 'SIKÇA SORULAN SORULAR (SSS)');
$section_excerpt = get_option('viralay_faq_page_excerpt', 'Aklınızdaki tüm soruların net, şeffaf ve hukuki yanıtları.');
$preferred_slugs = array('genel', 'baslangic', 'satici', 'alici', 'odemeler');
$ordered_terms   = array();
$seen_ids        = array();

foreach ($preferred_slugs as $slug) {
    $term = get_term_by('slug', $slug, 'faq_category');
    if ($term && !is_wp_error($term)) {
        $ordered_terms[] = $term;
        $seen_ids[]      = intval($term->term_id);
    }
}

$additional_terms = get_terms(array(
    'taxonomy'   => 'faq_category',
    'hide_empty' => false,
    'exclude'    => $seen_ids,
    'orderby'    => 'name',
    'order'      => 'ASC'
));

if (!is_wp_error($additional_terms) && !empty($additional_terms)) {
    $ordered_terms = array_merge($ordered_terms, $additional_terms);
}
?>

<section class="py-10 bg-gradient-to-b from-primary/10 to-background border-b border-white/5">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?php echo esc_html($section_title); ?></h1>
        <p class="text-muted-foreground text-lg max-w-2xl mx-auto"><?php echo esc_html($section_excerpt); ?></p>
    </div>
</section>

<section class="py-12 px-6 container mx-auto max-w-5xl">
    <?php
    $rendered = false;

    foreach ($ordered_terms as $term) {
        $faq_query = new WP_Query(array(
            'post_type'      => 'faq',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'faq_category',
                    'field'    => 'term_id',
                    'terms'    => intval($term->term_id)
                )
            )
        ));

        if (!$faq_query->have_posts()) {
            wp_reset_postdata();
            continue;
        }

        $rendered = true;
        ?>
        <div class="mb-16">
            <div class="mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-primary mb-2"><?php echo esc_html($term->name); ?></h2>
                <?php if (!empty($term->description)) : ?>
                    <p class="text-muted-foreground"><?php echo esc_html($term->description); ?></p>
                <?php endif; ?>
            </div>
            <div class="space-y-4">
                <?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
                    <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden hover:border-primary/50 transition-colors">
                        <details class="group">
                            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-white hover:text-primary transition-colors">
                                <span class="flex-1"><?php the_title(); ?></span>
                                <span class="transition-transform group-open:rotate-180 ml-4">
                                    <i class="fas fa-chevron-down text-primary"></i>
                                </span>
                            </summary>
                            <div class="text-muted-foreground px-5 pb-5 leading-relaxed">
                                <?php echo wp_kses_post(apply_filters('the_content', get_the_content(null, false, get_the_ID()))); ?>
                            </div>
                        </details>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    }

    if (!$rendered) {
        $fallback = new WP_Query(array(
            'post_type'      => 'faq',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC')
        ));

        if ($fallback->have_posts()) {
            ?>
            <div class="space-y-4">
                <?php while ($fallback->have_posts()) : $fallback->the_post(); ?>
                    <div class="border border-white/10 rounded-xl bg-white/5 overflow-hidden hover:border-primary/50 transition-colors">
                        <details class="group">
                            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-white hover:text-primary transition-colors">
                                <span class="flex-1"><?php the_title(); ?></span>
                                <span class="transition-transform group-open:rotate-180 ml-4">
                                    <i class="fas fa-chevron-down text-primary"></i>
                                </span>
                            </summary>
                            <div class="text-muted-foreground px-5 pb-5 leading-relaxed">
                                <?php echo wp_kses_post(apply_filters('the_content', get_the_content(null, false, get_the_ID()))); ?>
                            </div>
                        </details>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php
            wp_reset_postdata();
        } else {
            echo '<div class="text-center py-20">';
            echo '<p class="text-muted-foreground text-lg">' . esc_html__('Henüz SSS içeriği eklenmemiş.', 'viralay') . '</p>';
            echo '</div>';
        }
    }
    ?>
</section>

<?php get_footer(); ?>
