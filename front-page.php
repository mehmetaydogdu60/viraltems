<?php 
$hero_title = get_option('viralay_home_hero_title', 'Videonuzdaki Gelir Potansiyelini Açığa Çıkarın.');
$hero_subtitle = get_option('viralay_home_hero_subtitle', 'Telefonunuzdaki o eşsiz anı, dünya medyasının aradığı lisanslı bir içeriğe dönüştürüyoruz. Videonuzu koruma altına alın, global ağımıza katılın ve izlenmeleri gerçek bir kazanca çevirin.');
$faq_limit = max(1, (int) get_option('viralay_faq_home_limit', 4));
$how_it_works_title = get_option('viralay_how_it_works_title', 'Kazanç Sürecini Başlatın');
$how_it_works_subtitle = get_option('viralay_how_it_works_subtitle', 'Videonuzu profesyonel bir dijital varlığa dönüştürme yolculuğu.');
$about_home_title = get_option('viralay_about_home_title', 'VİRALAY HAKKINDA');
$about_home_subtitle = get_option('viralay_about_home_subtitle', 'Sıradan Anları Küresel Hikayelere Dönüştürüyoruz.');
$about_home_text = get_option('viralay_about_home_text', 'Viralay, kullanıcı kaynaklı içerikleri (UGC) profesyonel medya varlıklarına dönüştüren Türkiye\'nin yeni nesil lisanslama platformudur. Amacımız; içerik üreticilerinin haklarını koruyarak onlara sürdürülebilir bir gelir kapısı açmak, medya kuruluşlarına ise güvenilir ve lisanslı içerik sağlamaktır. Galerinizde bekleyen videolar, sandığınızdan çok daha değerli olabilir.');
?>
<?php get_header(); ?>

<!-- Hero Section -->
<section class="relative px-6 pt-12 pb-20 flex flex-col items-center text-center max-w-4xl mx-auto">
    <div class="mb-8 inline-flex items-center justify-center rounded-full border border-primary/50 bg-primary/10 px-6 py-2">
        <span class="text-xs font-bold uppercase tracking-wide text-primary">
            #1 VİRAL İÇERİK PLATFORMU
        </span>
    </div>
    <h1 class="mb-6 text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight leading-[1.15] text-white">
        <?php echo esc_html($hero_title); ?>
    </h1>
    <p class="mb-10 text-base sm:text-lg md:text-xl text-muted-foreground leading-relaxed max-w-2xl">
        <?php echo esc_html($hero_subtitle); ?>
    </p>
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
        <a href="<?php echo esc_url(home_url('/video-gonder')); ?>" class="h-14 px-4 sm:px-6 md:px-8 rounded-full text-base font-semibold bg-primary hover:bg-primary/90 text-white shadow-[0_0_20px_-5px_hsl(217_91%_60%)] transition-all flex items-center justify-center">
            Video Gönder
        </a>
        <a href="<?php echo esc_url(home_url('/video')); ?>" class="h-14 px-4 sm:px-6 md:px-8 rounded-full text-base font-semibold border-2 video-galeri-btn transition-all flex items-center justify-center">
            Video Galeri
        </a>
    </div>
</section>

<!-- Persona Showcase -->
<section class="py-10 bg-white/[0.02] border-y border-white/5">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <a href="<?php echo esc_url(home_url('/yayincilar')); ?>" class="group block p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-blue-500/50 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg group-hover:shadow-blue-500/50 transition-shadow">
                    <i class="fas fa-newspaper text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Haber ve Yayıncılar</h3>
                <p class="text-muted-foreground">Telif hakları temizlenmiş, kaynağı doğrulanmış ve yayına hazır sıcak içerik akışı.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/markalar-ajanslar')); ?>" class="group block p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-pink-500/50 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg group-hover:shadow-pink-500/50 transition-shadow">
                    <i class="fas fa-briefcase text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Markalar ve Ajanslar</h3>
                <p class="text-muted-foreground">Viral içerikleri kampanyalarınızda kullanın. Telif hakları temizlenmiş, yayına hazır UGC içeriklerle markanızı güçlendirin.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/tv-produksiyon')); ?>" class="group block p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-purple-500/50 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg group-hover:shadow-purple-500/50 transition-shadow">
                    <i class="fas fa-tv text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">TV ve Prodüksiyon</h3>
                <p class="text-muted-foreground">Ana haber bültenlerve TV programları için yüksek çözünürlüklü, logosuz ve ham (Raw) görüntü servisi.</p>
            </a>
            <a href="<?php echo esc_url(home_url('/ai-veri-kullanimi')); ?>" class="group block p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-primary to-cyan-500 rounded-lg flex items-center justify-center text-white mb-4 shadow-lg group-hover:shadow-primary/50 transition-shadow">
                    <i class="fas fa-robot text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">AI ve Veri Eğitimi</h3>
                <p class="text-muted-foreground">Yapay zeka modellerini eğitmek için etik kurallara uygun, izinli ve lisanslı gerçek hayat veri setleri.</p>
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-12 bg-white/[0.02] border-y border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4"><?php echo esc_html($how_it_works_title); ?></h2>
            <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($how_it_works_subtitle); ?></p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-colors">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center text-white mb-3 shadow-lg">
                    <i class="fas fa-cloud-arrow-up text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Videonuzu Yükleyin</h3>
                <p class="text-muted-foreground">Formu doldurun ve videonuzu güvenli sunucularımıza aktarın. Üyelik şartı yok, süreç dakikalar sürer.</p>
            </div>
            <div class="p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-colors">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center text-white mb-3 shadow-lg">
                    <i class="fas fa-scale-balanced text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Profesyonel Doğrulama</h3>
                <p class="text-muted-foreground">Editörlerimiz içeriğin orijinalliğini teyit eder. Onaylanan içerikler için hak yönetimi ve koruma süreci başlar.</p>
            </div>
            <div class="p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-colors">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center text-white mb-3 shadow-lg">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Global Pazarlama</h3>
                <p class="text-muted-foreground">Videonuz; ulusal haber siteleri, TV kanalları ve uluslararası medya partnerlerimizin erişimine açılır.</p>
            </div>
            <div class="p-4 md:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/50 transition-colors">
                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center text-white mb-3 shadow-lg">
                    <i class="fas fa-file-contract text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Gelir Paylaşımı</h3>
                <p class="text-muted-foreground">Satış gerçekleştiğinde, yasal prosedürler tamamlanır ve net kazanç şeffaf bir şekilde hesabınıza yatırılır.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Videos Loop -->
<section class="py-12 px-6 container mx-auto">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-3xl font-bold text-white mb-2">Son Eklenenler</h2>
            <p class="text-muted-foreground">Platformdaki en yeni viral içerikler</p>
        </div>
        <a href="<?php echo home_url('/video'); ?>" class="text-primary hover:text-white transition-colors font-medium">Tümünü Gör →</a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        <?php
        $args = array('post_type' => 'viral_videos', 'posts_per_page' => 8);
        $query = new WP_Query($args);
        if($query->have_posts()):
            while($query->have_posts()): $query->the_post();
                $thumb = get_viral_thumbnail(get_the_ID());
                $duration = get_post_meta(get_the_ID(), '_video_duration_text', true);
        ?>
        <a href="<?php the_permalink(); ?>" class="group block bg-white/5 rounded-xl overflow-hidden border border-white/10 hover:border-primary/50 transition-all">
            <div class="relative aspect-video bg-black/50">
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center shadow-lg transform scale-0 group-hover:scale-100 transition-transform">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <?php if($duration): ?>
                <span class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded font-mono">
                    <?php echo esc_html($duration); ?>
                </span>
                <?php endif; ?>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-white line-clamp-2 group-hover:text-primary transition-colors"><?php the_title(); ?></h3>
            </div>
        </a>
        <?php endwhile; wp_reset_postdata(); else: ?>
            <p class="text-muted-foreground col-span-full text-center py-12">Henüz video eklenmemiş.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Explore Categories -->
<section class="py-12 bg-white/[0.02] border-y border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Kategoriler</h2>
            <p class="text-muted-foreground max-w-2xl mx-auto">Popüler video kategorilerine göz atın</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <?php
            $default_categories = array(
                array('name' => 'Doğa', 'slug' => 'doga', 'icon' => 'fa-tree', 'color' => 'emerald'),
                array('name' => 'Hayvan', 'slug' => 'hayvan', 'icon' => 'fa-paw', 'color' => 'teal'),
                array('name' => 'İnsanlar', 'slug' => 'insanlar', 'icon' => 'fa-user-group', 'color' => 'cyan'),
                array('name' => 'Viral', 'slug' => 'viral', 'icon' => 'fa-fire', 'color' => 'orange'),
            );
            
            foreach ($default_categories as $cat_data):
                $term = get_term_by('slug', $cat_data['slug'], 'video_category');
                $cat_link = $term ? get_term_link($term) : home_url('/video');
                $cat_count = $term ? $term->count : 0;
            ?>
            <a href="<?php echo esc_url($cat_link); ?>" class="category-card category-<?php echo esc_attr($cat_data['color']); ?> group block p-6 rounded-xl border-2 transition-all hover:scale-105 hover:shadow-xl">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 category-icon-bg">
                        <i class="fas <?php echo esc_attr($cat_data['icon']); ?> text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2"><?php echo esc_html($cat_data['name']); ?></h3>
                    <p class="text-sm opacity-80"><?php echo $cat_count; ?> video</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-12 px-6 container mx-auto max-w-4xl">
    <div class="text-center mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4"><?php echo esc_html($about_home_title); ?></h2>
        <p class="text-xl text-primary font-semibold mb-6"><?php echo esc_html($about_home_subtitle); ?></p>
    </div>
    <div class="text-muted-foreground leading-relaxed text-lg text-center">
        <p><?php echo nl2br(esc_html($about_home_text)); ?></p>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-12 bg-white/[0.02] border-t border-white/5">
    <div class="container mx-auto px-6 max-w-6xl">
        <h2 class="text-3xl font-bold text-white mb-12 text-center">Sık Sorulan Sorular</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xl font-bold text-primary mb-6">Satıcı (İçerik Sahibi)</h3>
                <div class="space-y-4">
                    <?php
                    $seller_faqs = get_posts(array(
                        'post_type' => 'faq',
                        'posts_per_page' => $faq_limit,
                        'meta_query' => array(
                            array('key' => '_faq_show_on_home', 'value' => '1'),
                            array('key' => '_faq_home_column', 'value' => 'seller')
                        ),
                        'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
                        'order' => 'ASC'
                    ));
                    if (!empty($seller_faqs)):
                        foreach($seller_faqs as $faq):
                    ?>
                    <div class="border border-white/10 rounded-lg bg-white/5 overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-white hover:text-primary transition-colors">
                                <span><?php echo esc_html(get_the_title($faq)); ?></span>
                                <span class="transition group-open:rotate-180">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </summary>
                            <div class="text-muted-foreground p-4 pt-0 leading-relaxed">
                                <?php echo wp_kses_post($faq->post_content); ?>
                            </div>
                        </details>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <p class="text-muted-foreground text-center py-8 bg-white/5 border border-white/10 rounded-lg">Henüz SSS eklenmemiş.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-primary mb-6">Alıcı</h3>
                <div class="space-y-4">
                    <?php
                    $buyer_faqs = get_posts(array(
                        'post_type' => 'faq',
                        'posts_per_page' => $faq_limit,
                        'meta_query' => array(
                            array('key' => '_faq_show_on_home', 'value' => '1'),
                            array('key' => '_faq_home_column', 'value' => 'buyer')
                        ),
                        'orderby' => array('menu_order' => 'ASC', 'date' => 'DESC'),
                        'order' => 'ASC'
                    ));
                    if (!empty($buyer_faqs)):
                        foreach($buyer_faqs as $faq):
                    ?>
                    <div class="border border-white/10 rounded-lg bg-white/5 overflow-hidden">
                        <details class="group">
                            <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-white hover:text-primary transition-colors">
                                <span><?php echo esc_html(get_the_title($faq)); ?></span>
                                <span class="transition group-open:rotate-180">
                                    <i class="fas fa-chevron-down"></i>
                                </span>
                            </summary>
                            <div class="text-muted-foreground p-4 pt-0 leading-relaxed">
                                <?php echo wp_kses_post($faq->post_content); ?>
                            </div>
                        </details>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <p class="text-muted-foreground text-center py-8 bg-white/5 border border-white/10 rounded-lg">Henüz SSS eklenmemiş.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo esc_url(home_url('/sss')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-white/20 hover:bg-white/5 text-white font-semibold transition-colors">
                Tüm SSS'leri Görüntüle <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
