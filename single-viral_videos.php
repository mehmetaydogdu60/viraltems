<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <?php
    $submitter_name = get_post_meta(get_the_ID(), '_submitter_name', true);
    $video_url = get_post_meta(get_the_ID(), '_video_url', true);
    $video_embed = get_post_meta(get_the_ID(), '_video_embed', true);
    $video_duration = get_post_meta(get_the_ID(), '_video_duration', true);
    $video_duration_text = get_post_meta(get_the_ID(), '_video_duration_text', true);
    $video_price = get_post_meta(get_the_ID(), '_video_price', true);
    $license_type = get_post_meta(get_the_ID(), '_video_license_type', true);
    $video_location = get_post_meta(get_the_ID(), '_video_location', true);
    $video_shot_date = get_post_meta(get_the_ID(), '_video_shot_date', true);
    $video_source_credit = get_post_meta(get_the_ID(), '_video_source_credit', true);
    $video_asset_id = get_post_meta(get_the_ID(), '_video_asset_id', true);
    $video_rating = get_post_meta(get_the_ID(), '_video_rating', true);
    $video_keywords = get_post_meta(get_the_ID(), '_video_keywords', true);
    $categories = get_the_terms(get_the_ID(), 'video_category');

    // Video meta for copy buttons
    $video_title = get_the_title();
    $video_description = get_the_content();
    $video_id_display = $video_asset_id ?: 'VL-' . get_the_ID();

    $product_id = get_post_meta(get_the_ID(), '_video_product_id', true);
    $has_woocommerce = class_exists('WooCommerce') && $product_id;
    $current_user_id = get_current_user_id();
    $user_has_license = false;

    if ($current_user_id && class_exists('Viralay_WooCommerce_Integration')) {
        $wc_integration = Viralay_WooCommerce_Integration::get_instance();
        $user_has_license = $wc_integration->user_has_license($current_user_id, get_the_ID());
    }

    $r2_object_key = get_post_meta(get_the_ID(), '_r2_object_key', true);

    // Lisans Alıcısı kontrolü - yeni sistem
    $is_lisans_alicisi = false;
    $can_download = false;
    if ($current_user_id && class_exists('Viralay_Abone_Manager')) {
        $is_lisans_alicisi = Viralay_Abone_Manager::is_abone($current_user_id);
        $can_download = Viralay_Abone_Manager::can_download_video($current_user_id);
    }

    // Admin de indirebilir
    if (current_user_can('manage_options')) {
        $can_download = true;
    }

    // R2 Stream URL - lisans alıcısı veya admin için
    $r2_stream_url = '';
    if (($is_lisans_alicisi || current_user_can('manage_options')) && !empty($r2_object_key) && class_exists('Viralay_R2_Storage')) {
        $r2 = Viralay_R2_Storage::get_instance();
        if ($r2->is_configured()) {
            $r2_stream_url = $r2->get_signed_url_playback($r2_object_key, 1);
        }
    }
    ?>

    <div class="py-12 bg-gradient-to-b from-muted to-background">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <!-- Video Player Section -->
                    <div class="bg-black rounded-2xl overflow-hidden aspect-video mb-6 relative" id="video-player-container">
                        <?php
                        $youtube_id = '';
                        if ($video_url) {
                            $youtube_id = get_youtube_id($video_url);
                        }

                        // 1. YouTube veya Embed varsa önce onu göster
                        if ($video_embed) : ?>
                            <div class="w-full h-full">
                                <?php echo wp_kses_post($video_embed); ?>
                            </div>
                        <?php elseif ($youtube_id) : ?>
                            <!-- Viralay Modern YouTube Player -->
                            <div class="viralay-player" id="viralay-yt-container" data-video-id="<?php echo esc_attr($youtube_id); ?>">
                                <!-- Thumbnail Poster -->
                                <div class="viralay-poster" id="viralay-poster">
                                    <img src="https://i.ytimg.com/vi/<?php echo esc_attr($youtube_id); ?>/hqdefault.jpg"
                                         alt="<?php echo esc_attr($video_title); ?>"
                                         onerror="this.onerror=null;this.src='https://img.youtube.com/vi/<?php echo esc_attr($youtube_id); ?>/0.jpg'">
                                    <button class="viralay-play-btn" id="viralay-play-btn" aria-label="Video Oynat">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </button>
                                </div>

                                <!-- Video Container -->
                                <div class="viralay-video-wrap" id="viralay-video-wrap">
                                    <div id="viralay-yt-player"></div>
                                </div>

                                <!-- Custom Controls -->
                                <div class="viralay-controls" id="viralay-controls">
                                    <div class="viralay-progress-wrap" id="viralay-progress-wrap">
                                        <div class="viralay-progress-bg"></div>
                                        <div class="viralay-progress-buffer" id="viralay-buffer"></div>
                                        <div class="viralay-progress-bar" id="viralay-progress"></div>
                                        <div class="viralay-progress-thumb" id="viralay-thumb"></div>
                                    </div>
                                    <div class="viralay-controls-row">
                                        <div class="viralay-controls-left">
                                            <button class="viralay-btn" id="btn-playpause" aria-label="Oynat/Duraklat">
                                                <svg class="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                <svg class="icon-pause" viewBox="0 0 24 24" style="display:none"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>
                                            </button>
                                            <button class="viralay-btn" id="btn-mute" aria-label="Ses">
                                                <svg class="icon-vol" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                                                <svg class="icon-mute" viewBox="0 0 24 24" style="display:none"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                                            </button>
                                            <span class="viralay-time" id="viralay-time">0:00 / 0:00</span>
                                        </div>
                                        <div class="viralay-controls-right">
                                            <button class="viralay-btn" id="btn-fullscreen" aria-label="Tam Ekran">
                                                <svg viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <style>
                            /* === VIRALAY MODERN PLAYER === */
                            .viralay-player {
                                position: relative;
                                width: 100%;
                                height: 100%;
                                background: #000;
                                border-radius: 16px;
                                overflow: hidden;
                            }

                            /* Poster */
                            .viralay-poster {
                                position: absolute;
                                inset: 0;
                                z-index: 20;
                                cursor: pointer;
                            }
                            .viralay-poster.hidden { display: none; }
                            .viralay-poster img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            }
                            .viralay-play-btn {
                                position: absolute;
                                top: 50%;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                width: 72px;
                                height: 72px;
                                background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
                                border: none;
                                border-radius: 50%;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: #fff;
                                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                                box-shadow: 0 8px 30px rgba(20, 184, 166, 0.5);
                            }
                            .viralay-play-btn:hover {
                                transform: translate(-50%, -50%) scale(1.08);
                                box-shadow: 0 12px 40px rgba(20, 184, 166, 0.6);
                            }
                            .viralay-play-btn svg {
                                width: 28px;
                                height: 28px;
                                margin-left: 3px;
                                fill: currentColor;
                            }

                            /* Video */
                            .viralay-video-wrap {
                                position: absolute;
                                inset: 0;
                            }
                            .viralay-video-wrap iframe {
                                width: 100%;
                                height: 100%;
                                border: 0;
                            }

                            /* Controls */
                            .viralay-controls {
                                position: absolute;
                                bottom: 0;
                                left: 0;
                                right: 0;
                                padding: 0 16px 12px;
                                background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
                                opacity: 0;
                                transition: opacity 0.25s ease;
                                z-index: 30;
                            }
                            .viralay-player:hover .viralay-controls,
                            .viralay-player.show-controls .viralay-controls {
                                opacity: 1;
                            }

                            /* Progress Bar */
                            .viralay-progress-wrap {
                                position: relative;
                                height: 5px;
                                margin-bottom: 10px;
                                cursor: pointer;
                                border-radius: 3px;
                                transition: height 0.15s ease;
                            }
                            .viralay-progress-wrap:hover {
                                height: 7px;
                            }
                            .viralay-progress-bg {
                                position: absolute;
                                inset: 0;
                                background: rgba(255,255,255,0.25);
                                border-radius: 3px;
                            }
                            .viralay-progress-buffer {
                                position: absolute;
                                top: 0;
                                left: 0;
                                height: 100%;
                                background: rgba(255,255,255,0.4);
                                border-radius: 3px;
                                width: 0;
                                transition: width 0.2s ease;
                            }
                            .viralay-progress-bar {
                                position: absolute;
                                top: 0;
                                left: 0;
                                height: 100%;
                                background: linear-gradient(90deg, #14b8a6, #0d9488);
                                border-radius: 3px;
                                width: 0;
                            }
                            .viralay-progress-thumb {
                                position: absolute;
                                top: 50%;
                                transform: translate(-50%, -50%) scale(0);
                                width: 14px;
                                height: 14px;
                                background: #fff;
                                border-radius: 50%;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                                transition: transform 0.15s ease;
                                left: 0;
                            }
                            .viralay-progress-wrap:hover .viralay-progress-thumb {
                                transform: translate(-50%, -50%) scale(1);
                            }

                            /* Controls Row */
                            .viralay-controls-row {
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                            }
                            .viralay-controls-left,
                            .viralay-controls-right {
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            }
                            .viralay-btn {
                                background: transparent;
                                border: none;
                                color: #fff;
                                cursor: pointer;
                                padding: 8px;
                                border-radius: 8px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background 0.2s ease;
                            }
                            .viralay-btn:hover {
                                background: rgba(255,255,255,0.15);
                            }
                            .viralay-btn svg {
                                width: 22px;
                                height: 22px;
                                fill: currentColor;
                            }
                            .viralay-time {
                                color: rgba(255,255,255,0.9);
                                font-size: 13px;
                                font-weight: 500;
                                font-variant-numeric: tabular-nums;
                                margin-left: 8px;
                            }

                            /* Fullscreen */
                            .viralay-player.fullscreen {
                                position: fixed;
                                inset: 0;
                                z-index: 99999;
                                border-radius: 0;
                            }
                            </style>
                            <script>
                            (function(){
                                var container = document.getElementById('viralay-yt-container');
                                var poster = document.getElementById('viralay-poster');
                                var videoWrap = document.getElementById('viralay-video-wrap');
                                var controls = document.getElementById('viralay-controls');
                                var playBtn = document.getElementById('viralay-play-btn');
                                var btnPlayPause = document.getElementById('btn-playpause');
                                var btnMute = document.getElementById('btn-mute');
                                var btnFullscreen = document.getElementById('btn-fullscreen');
                                var progressWrap = document.getElementById('viralay-progress-wrap');
                                var progressBar = document.getElementById('viralay-progress');
                                var progressBuffer = document.getElementById('viralay-buffer');
                                var progressThumb = document.getElementById('viralay-thumb');
                                var timeDisplay = document.getElementById('viralay-time');
                                var videoId = container.getAttribute('data-video-id');
                                var player = null;
                                var isPlaying = false;
                                var updateInterval = null;
                                var hideTimeout = null;

                                function loadYTAPI() {
                                    if (window.YT && window.YT.Player) { initPlayer(); return; }
                                    if (!document.getElementById('yt-api-script')) {
                                        var tag = document.createElement('script');
                                        tag.id = 'yt-api-script';
                                        tag.src = 'https://www.youtube.com/iframe_api';
                                        document.head.appendChild(tag);
                                    }
                                    window.onYouTubeIframeAPIReady = initPlayer;
                                }

                                function initPlayer() {
                                    player = new YT.Player('viralay-yt-player', {
                                        videoId: videoId,
                                        playerVars: {
                                            autoplay: 1,
                                            controls: 0,
                                            disablekb: 1,
                                            fs: 0,
                                            iv_load_policy: 3,
                                            modestbranding: 1,
                                            playsinline: 1,
                                            rel: 0,
                                            showinfo: 0,
                                            origin: window.location.origin
                                        },
                                        events: {
                                            onReady: onPlayerReady,
                                            onStateChange: onPlayerStateChange
                                        }
                                    });
                                }

                                function onPlayerReady(e) {
                                    poster.classList.add('hidden');
                                    container.classList.add('show-controls');
                                    e.target.playVideo();
                                    startUpdate();
                                    scheduleHide();
                                }

                                function onPlayerStateChange(e) {
                                    isPlaying = (e.data === YT.PlayerState.PLAYING);
                                    updatePlayIcon();
                                    if (isPlaying) startUpdate(); else stopUpdate();
                                }

                                function updatePlayIcon() {
                                    var playIcon = btnPlayPause.querySelector('.icon-play');
                                    var pauseIcon = btnPlayPause.querySelector('.icon-pause');
                                    playIcon.style.display = isPlaying ? 'none' : 'block';
                                    pauseIcon.style.display = isPlaying ? 'block' : 'none';
                                }

                                function formatTime(sec) {
                                    var m = Math.floor(sec / 60);
                                    var s = Math.floor(sec % 60);
                                    return m + ':' + (s < 10 ? '0' : '') + s;
                                }

                                function startUpdate() {
                                    if (updateInterval) clearInterval(updateInterval);
                                    updateInterval = setInterval(function() {
                                        if (!player || !player.getCurrentTime) return;
                                        var current = player.getCurrentTime();
                                        var duration = player.getDuration();
                                        var buffered = player.getVideoLoadedFraction() * 100;
                                        var percent = (current / duration * 100);
                                        progressBar.style.width = percent + '%';
                                        progressThumb.style.left = percent + '%';
                                        progressBuffer.style.width = buffered + '%';
                                        timeDisplay.textContent = formatTime(current) + ' / ' + formatTime(duration);
                                    }, 200);
                                }

                                function stopUpdate() {
                                    if (updateInterval) { clearInterval(updateInterval); updateInterval = null; }
                                }

                                function scheduleHide() {
                                    if (hideTimeout) clearTimeout(hideTimeout);
                                    hideTimeout = setTimeout(function() {
                                        if (isPlaying) container.classList.remove('show-controls');
                                    }, 3000);
                                }

                                function togglePlay() {
                                    if (!player) return;
                                    if (isPlaying) player.pauseVideo();
                                    else player.playVideo();
                                }

                                // Events
                                playBtn.addEventListener('click', function(e) { e.stopPropagation(); loadYTAPI(); });
                                poster.addEventListener('click', function(e) { e.stopPropagation(); loadYTAPI(); });

                                videoWrap.addEventListener('click', function(e) {
                                    if (e.target.tagName === 'IFRAME') return;
                                    togglePlay();
                                });

                                container.addEventListener('mousemove', function() {
                                    container.classList.add('show-controls');
                                    scheduleHide();
                                });

                                btnPlayPause.addEventListener('click', function(e) { e.stopPropagation(); togglePlay(); });

                                btnMute.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    if (!player) return;
                                    var vol = btnMute.querySelector('.icon-vol');
                                    var mute = btnMute.querySelector('.icon-mute');
                                    if (player.isMuted()) {
                                        player.unMute();
                                        vol.style.display = 'block';
                                        mute.style.display = 'none';
                                    } else {
                                        player.mute();
                                        vol.style.display = 'none';
                                        mute.style.display = 'block';
                                    }
                                });

                                progressWrap.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    if (!player) return;
                                    var rect = progressWrap.getBoundingClientRect();
                                    var pct = (e.clientX - rect.left) / rect.width;
                                    player.seekTo(pct * player.getDuration(), true);
                                });

                                btnFullscreen.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    if (document.fullscreenElement) {
                                        document.exitFullscreen();
                                        container.classList.remove('fullscreen');
                                    } else {
                                        container.requestFullscreen();
                                        container.classList.add('fullscreen');
                                    }
                                });

                                document.addEventListener('fullscreenchange', function() {
                                    if (!document.fullscreenElement) container.classList.remove('fullscreen');
                                });

                                // Keyboard
                                document.addEventListener('keydown', function(e) {
                                    if (!player) return;
                                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                                    switch(e.key) {
                                        case ' ':
                                        case 'k': e.preventDefault(); togglePlay(); break;
                                        case 'm': e.preventDefault(); btnMute.click(); break;
                                        case 'f': e.preventDefault(); btnFullscreen.click(); break;
                                        case 'ArrowLeft': e.preventDefault(); player.seekTo(player.getCurrentTime() - 5, true); break;
                                        case 'ArrowRight': e.preventDefault(); player.seekTo(player.getCurrentTime() + 5, true); break;
                                    }
                                });
                            })();
                            </script>
                        <?php elseif (($is_lisans_alicisi || current_user_can('manage_options')) && !empty($r2_stream_url)) : ?>
                            <!-- YouTube yoksa ve lisans alıcısı/admin ise R2'den oynat -->
                            <div class="viralay-player-wrapper relative w-full h-full bg-black">
                                <video
                                    id="viralay-video-player"
                                    class="w-full h-full"
                                    controls
                                    playsinline
                                    poster="<?php echo esc_url(get_video_thumbnail(get_the_ID())); ?>"
                                    preload="metadata"
                                    style="background: #000;"
                                >
                                    <source src="<?php echo esc_url($r2_stream_url); ?>" type="video/mp4">
                                    Tarayıcınız video etiketini desteklemiyor.
                                </video>
                            </div>
                            <style>
                                .viralay-player-wrapper video {
                                    max-height: 100%;
                                    object-fit: contain;
                                }
                                .viralay-player-wrapper video::-webkit-media-controls {
                                    background: linear-gradient(transparent, rgba(0,0,0,0.7));
                                }
                            </style>
                        <?php elseif (!empty($r2_object_key)) : ?>
                            <!-- R2 video var - Thumbnail göster -->
                            <div class="relative w-full h-full">
                                <img src="<?php echo esc_url(get_video_thumbnail(get_the_ID())); ?>" alt="<?php echo esc_attr($video_title); ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3 backdrop-blur-sm">
                                            <i class="fas fa-play text-3xl"></i>
                                        </div>
                                        <p class="text-white/80 text-sm">Video önizlemesi</p>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($video_url) : ?>
                            <div class="flex items-center justify-center h-full bg-muted">
                                <div class="text-center text-white">
                                    <i class="fas fa-play-circle text-6xl mb-4 opacity-50"></i>
                                    <p class="text-muted-foreground">Video önizlemesi mevcut değil</p>
                                    <a href="<?php echo esc_url($video_url); ?>" target="_blank" class="inline-flex items-center gap-2 mt-4 px-6 py-3 rounded-full bg-primary hover:bg-primary/90 text-white transition-colors">
                                        <i class="fas fa-external-link-alt"></i>
                                        Orijinal Linki Aç
                                    </a>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex items-center justify-center h-full bg-muted">
                                <div class="text-center text-white">
                                    <i class="fas fa-video-slash text-6xl mb-4 opacity-50"></i>
                                    <p class="text-muted-foreground">Video mevcut değil</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center gap-4 flex-wrap text-sm text-muted-foreground mb-6">
                        <?php if ($video_duration || $video_duration_text) : ?>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-primary"></i>
                                <span><?php echo esc_html($video_duration_text ?: $video_duration . ' dk'); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar text-primary"></i>
                            <span><?php echo get_the_date('d F Y'); ?></span>
                        </div>
                        <?php if ($submitter_name) : ?>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user text-primary"></i>
                                <span><?php echo esc_html($submitter_name); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 sticky top-6">

                        <?php if ($user_has_license) : ?>
                            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl">
                                <h3 class="text-lg font-bold text-green-400 mb-2 flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    Lisans Sahibisiniz
                                </h3>
                                <p class="text-green-300 text-sm mb-4">Bu videoyu satın aldınız. İndirme hakkınız bulunmaktadır.</p>
                                <?php if ($r2_object_key && class_exists('Viralay_R2_Storage')) : 
                                    $r2 = Viralay_R2_Storage::get_instance();
                                    $download_url = $r2->get_signed_url_download($r2_object_key, 30, sanitize_file_name(get_the_title()) . '.mp4');
                                ?>
                                <a href="<?php echo esc_url($download_url); ?>" class="block w-full px-6 py-3 rounded-full bg-green-500 hover:bg-green-600 text-white font-semibold text-center transition-colors">
                                    <i class="fas fa-download mr-2"></i>
                                    Videoyu İndir
                                </a>
                                <?php else : ?>
                                <a href="<?php echo esc_url(home_url('/panel')); ?>" class="block w-full px-6 py-3 rounded-full bg-green-500 hover:bg-green-600 text-white font-semibold text-center transition-colors">
                                    <i class="fas fa-folder-open mr-2"></i>
                                    Panelden İndir
                                </a>
                                <?php endif; ?>
                            </div>
                        
                        <?php elseif ($is_lisans_alicisi || current_user_can('manage_options')) : ?>
                            <div class="mb-6 p-4 bg-teal-500/20 border border-teal-500/30 rounded-xl">
                                <h3 class="text-lg font-bold text-teal-400 mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?php echo current_user_can('manage_options') ? 'Admin Yetkisi' : 'Lisans Sahibisiniz'; ?>
                                </h3>
                                <p class="text-teal-300 text-sm mb-4">Bu videoyu izleyebilir ve indirebilirsiniz.</p>
                                <?php if (!empty($r2_object_key)) : ?>
                                    <?php do_action('viralay_video_download_button', get_the_ID()); ?>
                                <?php else : ?>
                                <div class="p-3 bg-amber-500/20 border border-amber-500/30 rounded-lg">
                                    <p class="text-amber-300 text-sm">Bu video için indirme linki henüz mevcut değil.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        
                        <?php elseif (is_user_logged_in() && $has_woocommerce) : ?>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                Lisans Seçenekleri
                            </h3>
                            
                            <?php
                            $product = wc_get_product($product_id);
                            if ($product && $product->is_type('variable')) :
                                $variations = $product->get_available_variations();
                            ?>
                            <div class="space-y-3 mb-6" id="license-options">
                                <?php foreach ($variations as $var) : 
                                    $variation = wc_get_product($var['variation_id']);
                                    $attr_name = $var['attributes']['attribute_pa_lisans-turu'] ?? '';
                                    $price = $variation->get_price();
                                    $is_request = ($price == 0);
                                    
                                    $term = get_term_by('slug', $attr_name, 'pa_lisans-turu');
                                    $label = $term ? $term->name : $attr_name;
                                ?>
                                <div class="license-option p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 hover:border-primary/50 transition-all cursor-pointer <?php echo $is_request ? 'bg-amber-500/10' : 'bg-gray-100 dark:bg-white/5'; ?>"
                                     data-variation-id="<?php echo esc_attr($var['variation_id']); ?>"
                                     data-video-id="<?php echo esc_attr(get_the_ID()); ?>"
                                     data-is-request="<?php echo $is_request ? '1' : '0'; ?>">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm"><?php echo esc_html($label); ?></h4>
                                        </div>
                                        <div class="text-right">
                                            <?php if ($is_request) : ?>
                                                <span class="text-amber-400 font-bold">Teklif Al</span>
                                            <?php else : ?>
                                                <span class="text-primary font-bold text-lg"><?php echo wc_price($price); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div id="cart-message" class="hidden mb-4 p-3 rounded-lg text-center text-sm"></div>
                            
                            <?php endif; ?>
                        
                        <?php elseif (!is_user_logged_in()) : ?>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-lock text-primary"></i>
                                Giriş Yapın
                            </h3>

                            <div class="mb-6">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Bu videoyu lisanslamak veya video göndermek için giriş yapın.
                                </p>
                            </div>

                            <div class="space-y-3">
                                <!-- Lisans Alıcı Girişi -->
                                <a href="<?php echo esc_url(home_url('/giris-yap?type=lisans')); ?>" class="block w-full px-6 py-3 rounded-full bg-primary hover:bg-primary/90 text-white font-semibold text-center transition-colors">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Lisans Alıcı Giriş
                                </a>
                                <!-- Video Gönderen Girişi -->
                                <a href="<?php echo esc_url(home_url('/giris-yap?type=client')); ?>" class="block w-full px-6 py-3 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white font-semibold text-center transition-colors">
                                    <i class="fas fa-video mr-2"></i>
                                    Video Gönderen Giriş
                                </a>
                                <button type="button" id="show-license-request-form" class="block w-full px-6 py-3 rounded-full border border-gray-300 dark:border-white/20 hover:bg-gray-100 dark:hover:bg-white/5 text-gray-900 dark:text-white font-semibold transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Lisans Talep Et
                                </button>
                            </div>

                            <div id="license-request-form-container" class="hidden mt-6">
                                <p class="text-gray-600 dark:text-gray-400 text-sm">Lisans talebi için iletişime geçin.</p>
                            </div>

                        <?php else : ?>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                Video Lisansı
                            </h3>

                            <div class="mb-6">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Bu video için lisans satışı yapılandırılmamış. Özel teklif için iletişime geçin.
                                </p>
                            </div>
                            
                            <div class="space-y-3">
                                <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="block w-full px-6 py-3 rounded-full bg-primary hover:bg-primary/90 text-white font-semibold text-center transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>
                                    İletişime Geç
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-white/10">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Lisans Hakları:</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    Ticari kullanım hakkı
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    Dijital medya kullanımı
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check text-green-500"></i>
                                    Sosyal medya paylaşımı
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 min-w-0 overflow-hidden">
                    <!-- Newsflare Style: Asset ID & Copy Buttons -->
                    <div class="flex items-center gap-3 mb-4 flex-wrap">
                        <span class="bg-primary/20 text-primary px-3 py-1 rounded-full text-sm font-mono font-semibold">
                            <?php echo esc_html($video_id_display); ?>
                        </span>
                        <button type="button" class="copy-btn text-xs px-3 py-1 rounded-full border border-gray-300 dark:border-white/20 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-1" data-copy="<?php echo esc_attr($video_id_display); ?>" title="ID Kopyala">
                            <i class="fas fa-copy"></i> ID
                        </button>
                        <button type="button" class="copy-btn text-xs px-3 py-1 rounded-full border border-gray-300 dark:border-white/20 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-1" data-copy="<?php echo esc_attr($video_title); ?>" title="Başlık Kopyala">
                            <i class="fas fa-heading"></i> Başlık
                        </button>
                        <?php if ($video_keywords) : ?>
                        <button type="button" class="copy-btn text-xs px-3 py-1 rounded-full border border-gray-300 dark:border-white/20 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-1" data-copy="<?php echo esc_attr($video_keywords); ?>" title="Anahtar Kelimeleri Kopyala">
                            <i class="fas fa-tags"></i> Keywords
                        </button>
                        <?php endif; ?>
                        <button type="button" class="copy-btn text-xs px-3 py-1 rounded-full border border-gray-300 dark:border-white/20 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-1" data-copy="<?php echo esc_url(get_permalink()); ?>" title="Linki Kopyala">
                            <i class="fas fa-link"></i> Link
                        </button>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6 break-words"><?php the_title(); ?></h1>

                    <?php if ($categories && !is_wp_error($categories)) : ?>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <?php foreach ($categories as $category) : ?>
                                <a href="<?php echo get_term_link($category); ?>" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-primary/20 text-primary hover:bg-primary hover:text-white transition-colors text-sm font-medium">
                                    <i class="fas fa-tag text-xs"></i>
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="prose dark:prose-invert max-w-none mb-8 text-gray-700 dark:text-gray-300">
                        <?php the_content(); ?>
                    </div>

                    <?php if ($video_keywords) : ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Anahtar Kelimeler</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach (array_filter(array_map('trim', explode(',', $video_keywords))) as $kw) : ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-sm text-gray-600 dark:text-gray-400">
                                        #<?php echo esc_html($kw); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="lg:col-span-1">
                    <?php if ($video_location || $video_shot_date || $video_source_credit) : ?>
                        <div class="bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-primary"></i>
                                Video Detayları
                            </h3>
                            <dl class="space-y-3 text-sm">
                                <?php if ($video_location) : ?>
                                    <div>
                                        <dt class="font-semibold text-gray-900 dark:text-white">Lokasyon</dt>
                                        <dd class="text-gray-600 dark:text-gray-400"><?php echo esc_html($video_location); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($video_shot_date) : ?>
                                    <div>
                                        <dt class="font-semibold text-gray-900 dark:text-white">Çekim Tarihi</dt>
                                        <dd class="text-gray-600 dark:text-gray-400"><?php echo esc_html($video_shot_date); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($video_source_credit) : ?>
                                    <div>
                                        <dt class="font-semibold text-gray-900 dark:text-white">Kaynak</dt>
                                        <dd class="text-gray-600 dark:text-gray-400"><?php echo esc_html($video_source_credit); ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if ($video_rating) : ?>
                                    <div>
                                        <dt class="font-semibold text-gray-900 dark:text-white">Derecelendirme</dt>
                                        <dd class="text-gray-600 dark:text-gray-400">
                                            <?php 
                                            echo $video_rating === 'brand_safe' ? 'Marka için güvenli' : 
                                                 ($video_rating === 'editorial' ? 'Sadece editoryal' : 'Sosyal için güvenli'); 
                                            ?>
                                        </dd>
                                    </div>
                                <?php endif; ?>
                            </dl>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>

<?php endwhile; ?>

<script>
jQuery(function($){
    // Copy to clipboard functionality (Newsflare style)
    $('.copy-btn').on('click', function(){
        var $btn = $(this);
        var text = $btn.data('copy');
        var originalHtml = $btn.html();

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                $btn.html('<i class="fas fa-check"></i> Kopyalandı!');
                $btn.addClass('bg-green-500/20 border-green-500/50 text-green-400');
                setTimeout(function() {
                    $btn.html(originalHtml);
                    $btn.removeClass('bg-green-500/20 border-green-500/50 text-green-400');
                }, 2000);
            }).catch(function(err) {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(text, $btn, originalHtml);
            });
        } else {
            fallbackCopyTextToClipboard(text, $btn, originalHtml);
        }
    });

    function fallbackCopyTextToClipboard(text, $btn, originalHtml) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            $btn.html('<i class="fas fa-check"></i> Kopyalandı!');
            $btn.addClass('bg-green-500/20 border-green-500/50 text-green-400');
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('bg-green-500/20 border-green-500/50 text-green-400');
            }, 2000);
        } catch (err) {
            $btn.html('<i class="fas fa-times"></i> Hata!');
            setTimeout(function() {
                $btn.html(originalHtml);
            }, 2000);
        }
        document.body.removeChild(textArea);
    }

    $('#show-license-request-form').on('click', function(){
        $('#license-request-form-container').slideToggle();
    });
    
    $('.license-option').on('click', function(){
        var $this = $(this);
        var variationId = $this.data('variation-id');
        var videoId = $this.data('video-id');
        var isRequest = $this.data('is-request');
        
        if (isRequest == '1') {
            $('#license-request-form-container').slideDown();
            $('html, body').animate({
                scrollTop: $('#license-request-form-container').offset().top - 100
            }, 500);
            return;
        }
        
        $this.addClass('opacity-50').css('pointer-events', 'none');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'viralay_add_license_to_cart',
                nonce: '<?php echo wp_create_nonce('viralay_nonce'); ?>',
                variation_id: variationId,
                video_post_id: videoId
            },
            success: function(response) {
                if (response.success) {
                    $('#cart-message')
                        .removeClass('hidden bg-red-500/20 text-red-300')
                        .addClass('bg-green-500/20 text-green-300')
                        .html('<i class="fas fa-check-circle mr-2"></i>' + response.data.message + ' <a href="' + response.data.cart_url + '" class="underline">Sepete Git</a>')
                        .show();
                } else {
                    if (response.data && response.data.redirect === 'license_request') {
                        $('#license-request-form-container').slideDown();
                    } else {
                        $('#cart-message')
                            .removeClass('hidden bg-green-500/20 text-green-300')
                            .addClass('bg-red-500/20 text-red-300')
                            .text(response.data || 'Hata oluştu')
                            .show();
                    }
                }
            },
            error: function() {
                $('#cart-message')
                    .removeClass('hidden bg-green-500/20 text-green-300')
                    .addClass('bg-red-500/20 text-red-300')
                    .text('Sunucu hatası')
                    .show();
            },
            complete: function() {
                $this.removeClass('opacity-50').css('pointer-events', 'auto');
            }
        });
    });
    
    $('#subscriber-download-btn').on('click', function(){
        var $btn = $(this);
        var videoId = $btn.data('video-id');
        var originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>İndiriliyor...');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'viralay_subscriber_video_download',
                nonce: '<?php echo wp_create_nonce('viralay_subscriber_download_nonce'); ?>',
                video_id: videoId
            },
            success: function(response) {
                if (response.success && response.data.download_url) {
                    window.location.href = response.data.download_url;
                    setTimeout(function() {
                        $btn.html('<i class="fas fa-check mr-2"></i>İndirildi!');
                        if (response.data.credits_remaining !== undefined) {
                            $btn.closest('.rounded-xl').find('strong').text(response.data.credits_remaining);
                        }
                        setTimeout(function() {
                            $btn.prop('disabled', false).html(originalHtml);
                        }, 3000);
                    }, 1000);
                } else {
                    alert(response.data.message || 'İndirme hatası');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                alert('Sunucu hatası');
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
