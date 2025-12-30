<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
    // Preconnect to optimize external resources
    $site_title_font = get_option('viralay_site_title_font', 'Inter');
    $fonts_to_load = array('Inter'); // Default body font
    if (!in_array($site_title_font, $fonts_to_load)) {
        $fonts_to_load[] = $site_title_font;
    }
    $font_families = implode('|', array_map(function($font) {
        return str_replace(' ', '+', $font) . ':wght@400;600;700';
    }, $fonts_to_load));
    ?>
    
    <!-- Preconnect for faster font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Optimized font loading with display=swap -->
    <link href="https://fonts.googleapis.com/css2?family=<?php echo $font_families; ?>&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=<?php echo $font_families; ?>&display=swap" rel="stylesheet"></noscript>
    
    <?php wp_head(); ?>
    
    <script>
        // Ultra-fast theme initialization (before page render)
        (function() {
            const savedTheme = localStorage.getItem('viralay-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        /* CSS Variables for Theme */
        :root[data-theme="light"] {
            --color-background: 255 255 255;
            --color-foreground: 15 23 42;
            --color-primary: 174 72% 56%;
            --color-primary-dark: 174 82% 36%;
            --color-primary-foreground: 255 255 255;
            --color-muted: 248 250 252;
            --color-muted-foreground: 71 85 105;
            --color-border: 226 232 240;
            --color-card: 255 255 255;
            --color-accent: 174 62% 46%;
        }
        
        :root[data-theme="dark"], :root {
            --color-background: 15 23 42;
            --color-foreground: 248 250 252;
            --color-primary: 174 72% 56%;
            --color-primary-light: 174 62% 66%;
            --color-primary-foreground: 15 23 42;
            --color-muted: 30 41 59;
            --color-muted-foreground: 148 163 184;
            --color-border: 51 65 85;
            --color-card: 30 41 59;
            --color-accent: 174 82% 46%;
        }
        
        body { 
            font-family: 'Inter', sans-serif;
        }
        
        /* Light Mode Styles */
        [data-theme="light"] body {
            background-color: rgb(255, 255, 255);
            color: rgb(15, 23, 42);
        }
        
        [data-theme="light"] .bg-background {
            background-color: rgb(255, 255, 255) !important;
        }
        
        [data-theme="light"] .text-foreground {
            color: rgb(15, 23, 42) !important;
        }
        
        [data-theme="light"] .text-white {
            color: rgb(15, 23, 42) !important;
        }
        
        [data-theme="light"] .text-muted-foreground {
            color: rgb(71, 85, 105) !important;
        }
        
        [data-theme="light"] .bg-muted {
            background-color: rgb(248, 250, 252) !important;
        }
        
        [data-theme="light"] .border-white\/5,
        [data-theme="light"] .border-white\/10 {
            border-color: rgb(226, 232, 240) !important;
        }
        
        [data-theme="light"] header {
            background-color: rgba(255, 255, 255, 0.98) !important;
            border-bottom: 1px solid rgb(226, 232, 240) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="light"] #mobile-menu {
            background-color: rgba(255, 255, 255, 0.98) !important;
        }
        
        [data-theme="light"] .bg-black\/95 {
            background-color: rgba(255, 255, 255, 0.98) !important;
        }
        
        /* Dark Mode Styles (default) */
        [data-theme="dark"] body {
            background-color: rgb(15, 23, 42);
            color: rgb(248, 250, 252);
        }
        
        .animate-scroll { animation: scroll 20s linear infinite; }
        @keyframes scroll { from { transform: translateX(0); } to { transform: translateX(-100%); } }
        html { margin-top: 0 !important; }
        #wpadminbar { top: auto !important; bottom: 0; opacity: 0.8; }
        
        /* Custom Site Title Styling */
        .site-title-custom {
            font-family: '<?php echo esc_attr(get_option('viralay_site_title_font', 'Inter')); ?>', sans-serif;
            text-transform: <?php echo esc_attr(get_option('viralay_site_title_case', 'uppercase')); ?>;
        }
        
        [data-theme="dark"] .site-title-custom {
            color: <?php echo esc_attr(get_option('viralay_site_title_color_dark', '#FFFFFF')); ?> !important;
        }
        
        [data-theme="light"] .site-title-custom {
            color: <?php echo esc_attr(get_option('viralay_site_title_color_light', '#0F172A')); ?> !important;
        }
        
        /* Mobile Menu Transition */
        #mobile-menu {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            transform: translateY(-20px);
            opacity: 0;
            pointer-events: none;
        }
        #mobile-menu.open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        
        /* Select dropdown - Theme aware */
        [data-theme="dark"] select {
            color: white !important;
        }
        [data-theme="dark"] select option {
            background-color: #1e293b !important;
            color: white !important;
            padding: 8px !important;
        }
        
        [data-theme="light"] select {
            color: #0f172a !important;
            background-color: white !important;
        }
        [data-theme="light"] select option {
            background-color: white !important;
            color: #0f172a !important;
            padding: 8px !important;
        }
        
        /* Input fields - Theme aware */
        [data-theme="dark"] input[type="text"], 
        [data-theme="dark"] input[type="email"], 
        [data-theme="dark"] input[type="url"], 
        [data-theme="dark"] input[type="number"], 
        [data-theme="dark"] textarea {
            color: white !important;
        }
        [data-theme="dark"] input::placeholder, 
        [data-theme="dark"] textarea::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }
        
        [data-theme="light"] input[type="text"], 
        [data-theme="light"] input[type="email"], 
        [data-theme="light"] input[type="url"], 
        [data-theme="light"] input[type="number"], 
        [data-theme="light"] textarea {
            color: #0f172a !important;
            background-color: white !important;
            border-color: hsl(226 232 240) !important;
        }
        [data-theme="light"] input::placeholder, 
        [data-theme="light"] textarea::placeholder {
            color: rgba(15, 23, 42, 0.4) !important;
        }
        
        /* COMPLETE AUTOFILL OVERRIDE - Remove yellow background and border */
        /* Universal autofill fix for all input types */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        textarea:-webkit-autofill:active,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus,
        select:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px white inset !important;
            box-shadow: 0 0 0px 9999px white inset !important;
            -webkit-text-fill-color: #000000 !important;
            background-color: transparent !important;
            background-image: none !important;
            color: #000000 !important;
            border: 1px solid #e2e8f0 !important;
            transition: background-color 5000s ease-in-out 0s, border-color 0.2s !important;
        }
        
        /* Dark mode autofill - STRONGER override */
        [data-theme="dark"] input:-webkit-autofill,
        [data-theme="dark"] input:-webkit-autofill:hover,
        [data-theme="dark"] input:-webkit-autofill:focus,
        [data-theme="dark"] input:-webkit-autofill:active,
        [data-theme="dark"] textarea:-webkit-autofill,
        [data-theme="dark"] textarea:-webkit-autofill:hover,
        [data-theme="dark"] textarea:-webkit-autofill:focus,
        [data-theme="dark"] textarea:-webkit-autofill:active,
        [data-theme="dark"] select:-webkit-autofill,
        [data-theme="dark"] select:-webkit-autofill:hover,
        [data-theme="dark"] select:-webkit-autofill:focus,
        [data-theme="dark"] select:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px #1e293b inset !important;
            box-shadow: 0 0 0px 9999px #1e293b inset !important;
            -webkit-text-fill-color: white !important;
            background-color: #1e293b !important;
            background-image: none !important;
            color: white !important;
            border: 1px solid rgb(51, 65, 85) !important;
            transition: background-color 5000s ease-in-out 0s, border-color 0.2s !important;
        }
        
        /* Light mode autofill - STRONGER override */
        [data-theme="light"] input:-webkit-autofill,
        [data-theme="light"] input:-webkit-autofill:hover,
        [data-theme="light"] input:-webkit-autofill:focus,
        [data-theme="light"] input:-webkit-autofill:active,
        [data-theme="light"] textarea:-webkit-autofill,
        [data-theme="light"] textarea:-webkit-autofill:hover,
        [data-theme="light"] textarea:-webkit-autofill:focus,
        [data-theme="light"] textarea:-webkit-autofill:active,
        [data-theme="light"] select:-webkit-autofill,
        [data-theme="light"] select:-webkit-autofill:hover,
        [data-theme="light"] select:-webkit-autofill:focus,
        [data-theme="light"] select:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px white inset !important;
            box-shadow: 0 0 0px 9999px white inset !important;
            -webkit-text-fill-color: #0f172a !important;
            background-color: white !important;
            background-image: none !important;
            color: #0f172a !important;
            border: 1px solid #e2e8f0 !important;
            transition: background-color 5000s ease-in-out 0s, border-color 0.2s !important;
        }
        
        /* Theme Toggle Button */
        .theme-toggle {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="light"] .theme-toggle {
            border-color: rgba(15, 23, 42, 0.1);
        }
        
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        [data-theme="light"] .theme-toggle:hover {
            background: rgba(15, 23, 42, 0.05);
        }
        
        .theme-toggle svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }
        
        .theme-toggle .sun-icon,
        .theme-toggle .moon-icon {
            position: absolute;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        [data-theme="dark"] .sun-icon {
            opacity: 0;
            transform: rotate(90deg) scale(0);
        }
        
        [data-theme="dark"] .moon-icon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        
        [data-theme="light"] .sun-icon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
        
        [data-theme="light"] .moon-icon {
            opacity: 0;
            transform: rotate(-90deg) scale(0);
        }
        
        /* Additional Light Mode Compatibility */
        [data-theme="light"] .bg-white\/5,
        [data-theme="light"] .bg-white\\/\\[0\\.02\\],
        [data-theme="light"] .bg-white\\/\\[0\\.05\\] {
            background-color: rgba(15, 23, 42, 0.05) !important;
        }
        
        /* Primary/Turkuaz Color System - Professional */
        [data-theme="dark"] .bg-primary {
            background-color: #14B8A6 !important;
        }
        
        [data-theme="light"] .bg-primary {
            background-color: #0F766E !important;
        }
        
        [data-theme="dark"] .text-primary {
            color: #14B8A6 !important;
        }
        
        [data-theme="light"] .text-primary {
            color: #0F766E !important;
        }
        
        [data-theme="dark"] .border-primary {
            border-color: #14B8A6 !important;
        }
        
        [data-theme="light"] .border-primary {
            border-color: #0F766E !important;
        }
        
        [data-theme="dark"] .hover\:bg-primary:hover,
        [data-theme="dark"] .hover\:bg-primary\\/90:hover {
            background-color: #2DD4BF !important;
        }
        
        [data-theme="light"] .hover\:bg-primary:hover,
        [data-theme="light"] .hover\:bg-primary\\/90:hover,
        [data-theme="light"] .bg-primary:hover {
            background-color: #0D9488 !important;
            color: white !important;
        }
        
        [data-theme="dark"] .hover\:text-primary:hover {
            color: #2DD4BF !important;
        }
        
        [data-theme="light"] .hover\:text-primary:hover {
            color: #0D9488 !important;
        }
        
        [data-theme="light"] .border-primary\\/50 {
            border-color: rgba(15, 118, 110, 0.5) !important;
        }
        
        [data-theme="dark"] .border-primary\\/50 {
            border-color: rgba(20, 184, 166, 0.5) !important;
        }
        
        [data-theme="light"] .bg-primary\\/10 {
            background-color: rgba(15, 118, 110, 0.1) !important;
        }
        
        [data-theme="dark"] .bg-primary\\/10 {
            background-color: rgba(20, 184, 166, 0.1) !important;
        }
        
        [data-theme="light"] .from-primary\\/10 {
            --tw-gradient-from: rgba(15, 118, 110, 0.15) !important;
        }
        
        [data-theme="dark"] .from-primary\\/10 {
            --tw-gradient-from: rgba(20, 184, 166, 0.15) !important;
        }
        
        [data-theme="light"] .shadow-primary,
        .shadow-\\[0_0_20px_-5px_hsl\\(217_91\\%_60\\%\\)\\] {
            box-shadow: 0 0 20px -5px rgba(15, 118, 110, 0.4) !important;
        }
        
        [data-theme="dark"] .shadow-primary,
        [data-theme="dark"] .shadow-\\[0_0_20px_-5px_hsl\\(217_91\\%_60\\%\\)\\] {
            box-shadow: 0 0 25px -5px rgba(20, 184, 166, 0.5) !important;
        }
        
        [data-theme="light"] .bg-black {
            background-color: hsl(226 232 240) !important;
        }
        
        [data-theme="light"] .hover\:bg-white\/5:hover,
        [data-theme="light"] .hover\:bg-white\\/5:hover {
            background-color: rgba(15, 23, 42, 0.05) !important;
        }
        
        [data-theme="light"] .hover\:text-white:hover {
            color: hsl(15 23 42) !important;
        }
        
        /* Gradient Colors - Professional Turkuaz Theme */
        [data-theme="dark"] .from-blue-500,
        [data-theme="dark"] .from-teal-500,
        [data-theme="dark"] .from-primary { 
            --tw-gradient-from: #14B8A6 !important;
            --tw-gradient-to: rgba(20, 184, 166, 0) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }
        
        [data-theme="light"] .from-blue-500,
        [data-theme="light"] .from-teal-500,
        [data-theme="light"] .from-primary { 
            --tw-gradient-from: #0F766E !important;
            --tw-gradient-to: rgba(15, 118, 110, 0) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }
        
        [data-theme="dark"] .to-blue-600,
        [data-theme="dark"] .to-teal-600,
        [data-theme="dark"] .to-primary { 
            --tw-gradient-to: #0D9488 !important; 
        }
        
        [data-theme="light"] .to-blue-600,
        [data-theme="light"] .to-teal-600,
        [data-theme="light"] .to-primary { 
            --tw-gradient-to: #115E59 !important; 
        }
        
        [data-theme="dark"] .from-pink-500,
        [data-theme="dark"] .from-cyan-400,
        [data-theme="dark"] .to-cyan-500 { 
            --tw-gradient-from: #06B6D4 !important;
            --tw-gradient-to: #0891B2 !important;
        }
        
        [data-theme="light"] .from-pink-500,
        [data-theme="light"] .from-cyan-400,
        [data-theme="light"] .to-cyan-500 { 
            --tw-gradient-from: #0E7490 !important;
            --tw-gradient-to: #155E75 !important;
        }
        
        [data-theme="dark"] .from-purple-500,
        [data-theme="dark"] .to-purple-600 { 
            --tw-gradient-from: #10B981 !important;
            --tw-gradient-to: #059669 !important;
        }
        
        [data-theme="light"] .from-purple-500,
        [data-theme="light"] .to-purple-600 { 
            --tw-gradient-from: #047857 !important;
            --tw-gradient-to: #065F46 !important;
        }
        
        /* Light Mode for Gradients */
        [data-theme="light"] .from-muted {
            --tw-gradient-from: rgb(248, 250, 252) !important;
        }
        
        [data-theme="light"] .to-background {
            --tw-gradient-to: rgb(255, 255, 255) !important;
        }
        
        /* Card Hover Effects - Professional */
        [data-theme="light"] .group:hover .shadow-blue-500\\/50,
        [data-theme="light"] .group:hover .shadow-teal-500\\/50,
        [data-theme="light"] .group:hover .shadow-cyan-500\\/50,
        [data-theme="light"] .group:hover .shadow-pink-500\\/50,
        [data-theme="light"] .group:hover .shadow-purple-500\\/50,
        [data-theme="light"] .group:hover .shadow-primary\\/50 {
            box-shadow: 0 8px 20px -5px rgba(15, 118, 110, 0.3) !important;
        }
        
        [data-theme="dark"] .group:hover .shadow-blue-500\\/50,
        [data-theme="dark"] .group:hover .shadow-teal-500\\/50,
        [data-theme="dark"] .group:hover .shadow-cyan-500\\/50,
        [data-theme="dark"] .group:hover .shadow-pink-500\\/50,
        [data-theme="dark"] .group:hover .shadow-purple-500\\/50,
        [data-theme="dark"] .group:hover .shadow-primary\\/50 {
            box-shadow: 0 10px 25px -5px rgba(20, 184, 166, 0.4) !important;
        }
        
        /* Card Hover Border Colors */
        [data-theme="dark"] .hover\:border-blue-500\\/50:hover,
        [data-theme="dark"] .hover\:border-teal-500\\/50:hover,
        [data-theme="dark"] .hover\:border-cyan-500\\/50:hover,
        [data-theme="dark"] .hover\:border-pink-500\\/50:hover,
        [data-theme="dark"] .hover\:border-purple-500\\/50:hover,
        [data-theme="dark"] .hover\:border-primary\\/50:hover {
            border-color: rgba(20, 184, 166, 0.5) !important;
        }
        
        [data-theme="light"] .hover\:border-blue-500\\/50:hover,
        [data-theme="light"] .hover\:border-teal-500\\/50:hover,
        [data-theme="light"] .hover\:border-cyan-500\\/50:hover,
        [data-theme="light"] .hover\:border-pink-500\\/50:hover,
        [data-theme="light"] .hover\:border-purple-500\\/50:hover,
        [data-theme="light"] .hover\:border-primary\\/50:hover {
            border-color: rgba(15, 118, 110, 0.5) !important;
        }
        
        /* PROFESSIONAL COLOR SYSTEM - DARK MODE */
        [data-theme="dark"] {
            --teal-primary: #14B8A6;
            --teal-hover: #2DD4BF;
            --emerald: #10B981;
            --cyan: #06B6D4;
            --orange: #F97316;
            --bg-primary: #0F172A;
            --text-primary: #F8FAFC;
        }
        
        /* PROFESSIONAL COLOR SYSTEM - LIGHT MODE */
        [data-theme="light"] {
            --teal-primary: #0F766E;
            --teal-hover: #0D9488;
            --emerald: #047857;
            --cyan: #0E7490;
            --orange: #EA580C;
            --bg-primary: #FFFFFF;
            --text-primary: #0F172A;
        }
        
        /* Category Cards - Professional Design */
        .category-card {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="light"] .category-card {
            background: rgba(15, 23, 42, 0.03);
            border-color: rgba(15, 23, 42, 0.1);
        }
        
        /* Category Emerald (Doğa) */
        [data-theme="dark"] .category-emerald {
            border-color: #10B981;
        }
        [data-theme="dark"] .category-emerald .category-icon-bg {
            background-color: #10B981;
            color: white;
        }
        [data-theme="dark"] .category-emerald h3 {
            color: #10B981;
        }
        [data-theme="dark"] .category-emerald:hover {
            border-color: #34D399;
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.4);
        }
        
        [data-theme="light"] .category-emerald {
            border-color: #047857;
        }
        [data-theme="light"] .category-emerald .category-icon-bg {
            background-color: #047857;
            color: white;
        }
        [data-theme="light"] .category-emerald h3 {
            color: #047857;
        }
        [data-theme="light"] .category-emerald:hover {
            border-color: #059669;
            box-shadow: 0 10px 30px -5px rgba(4, 120, 87, 0.3);
        }
        
        /* Category Teal (Hayvan) */
        [data-theme="dark"] .category-teal {
            border-color: #14B8A6;
        }
        [data-theme="dark"] .category-teal .category-icon-bg {
            background-color: #14B8A6;
            color: white;
        }
        [data-theme="dark"] .category-teal h3 {
            color: #14B8A6;
        }
        [data-theme="dark"] .category-teal:hover {
            border-color: #2DD4BF;
            box-shadow: 0 10px 30px -5px rgba(20, 184, 166, 0.4);
        }
        
        [data-theme="light"] .category-teal {
            border-color: #0F766E;
        }
        [data-theme="light"] .category-teal .category-icon-bg {
            background-color: #0F766E;
            color: white;
        }
        [data-theme="light"] .category-teal h3 {
            color: #0F766E;
        }
        [data-theme="light"] .category-teal:hover {
            border-color: #0D9488;
            box-shadow: 0 10px 30px -5px rgba(15, 118, 110, 0.3);
        }
        
        /* Category Cyan (İnsanlar) */
        [data-theme="dark"] .category-cyan {
            border-color: #06B6D4;
        }
        [data-theme="dark"] .category-cyan .category-icon-bg {
            background-color: #06B6D4;
            color: white;
        }
        [data-theme="dark"] .category-cyan h3 {
            color: #06B6D4;
        }
        [data-theme="dark"] .category-cyan:hover {
            border-color: #22D3EE;
            box-shadow: 0 10px 30px -5px rgba(6, 182, 212, 0.4);
        }
        
        [data-theme="light"] .category-cyan {
            border-color: #0E7490;
        }
        [data-theme="light"] .category-cyan .category-icon-bg {
            background-color: #0E7490;
            color: white;
        }
        [data-theme="light"] .category-cyan h3 {
            color: #0E7490;
        }
        [data-theme="light"] .category-cyan:hover {
            border-color: #0891B2;
            box-shadow: 0 10px 30px -5px rgba(14, 116, 144, 0.3);
        }
        
        /* Category Orange (Viral) */
        [data-theme="dark"] .category-orange {
            border-color: #F97316;
        }
        [data-theme="dark"] .category-orange .category-icon-bg {
            background-color: #F97316;
            color: white;
        }
        [data-theme="dark"] .category-orange h3 {
            color: #F97316;
        }
        [data-theme="dark"] .category-orange:hover {
            border-color: #FB923C;
            box-shadow: 0 10px 30px -5px rgba(249, 115, 22, 0.4);
        }
        
        [data-theme="light"] .category-orange {
            border-color: #EA580C;
        }
        [data-theme="light"] .category-orange .category-icon-bg {
            background-color: #EA580C;
            color: white;
        }
        [data-theme="light"] .category-orange h3 {
            color: #EA580C;
        }
        [data-theme="light"] .category-orange:hover {
            border-color: #F97316;
            box-shadow: 0 10px 30px -5px rgba(234, 88, 12, 0.3);
        }
        
        /* Category Card Text */
        [data-theme="dark"] .category-card h3 {
            color: inherit;
        }
        [data-theme="dark"] .category-card p {
            color: rgba(248, 250, 252, 0.7);
        }
        
        [data-theme="light"] .category-card h3 {
            color: inherit;
        }
        [data-theme="light"] .category-card p {
            color: rgba(15, 23, 42, 0.6);
        }
        
        /* Focus states - Turkuaz */
        [data-theme="light"] *:focus-visible {
            outline: 2px solid #0F766E;
            outline-offset: 2px;
        }
        
        [data-theme="dark"] *:focus-visible {
            outline: 2px solid #14B8A6;
            outline-offset: 2px;
        }
        
        /* Navigation Links - Professional Teal */
        [data-theme="light"] nav a:hover {
            color: #0F766E !important;
        }
        
        [data-theme="dark"] nav a:hover {
            color: #2DD4BF !important;
        }
        
        /* Light mode text visibility improvements */
        [data-theme="light"] h1,
        [data-theme="light"] h2,
        [data-theme="light"] h3,
        [data-theme="light"] h4,
        [data-theme="light"] h5,
        [data-theme="light"] h6 {
            color: rgb(15, 23, 42) !important;
        }
        
        /* Button hover states - Professional */
        [data-theme="light"] .bg-primary:hover {
            background-color: #0D9488 !important;
        }
        
        [data-theme="dark"] .bg-primary:hover {
            background-color: #2DD4BF !important;
        }
        
        /* Ensure button text is white on primary background */
        .bg-primary,
        .bg-primary *,
        .bg-primary a,
        [data-theme="light"] .bg-primary,
        [data-theme="light"] .bg-primary *,
        [data-theme="light"] .bg-primary a,
        [data-theme="dark"] .bg-primary,
        [data-theme="dark"] .bg-primary *,
        [data-theme="dark"] .bg-primary a {
            color: white !important;
        }
        
        /* Video Gönder button - always white text on primary background */
        a.bg-primary,
        button.bg-primary,
        [href*="video-gonder"].bg-primary {
            color: white !important;
        }
        
        [href*="video-gonder"].bg-primary {
            background-color: #14B8A6 !important;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }
        
        a.bg-primary:hover,
        button.bg-primary:hover,
        [href*="video-gonder"].bg-primary:hover,
        a.bg-primary:hover *,
        button.bg-primary:hover *,
        [href*="video-gonder"].bg-primary:hover * {
            color: white !important;
        }
        
        [href*="video-gonder"].bg-primary:hover {
            background-color: #14B8A6 !important;
            box-shadow: 0 12px 30px -12px rgba(20, 184, 166, 0.45);
        }
        
        /* Video Galeri button - Dark and Light mode */
        [data-theme="dark"] .video-galeri-btn {
            border-color: rgba(255, 255, 255, 0.2) !important;
            background-color: transparent !important;
            color: white !important;
        }
        
        [data-theme="dark"] .video-galeri-btn:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        
        [data-theme="light"] .video-galeri-btn {
            border-color: #0F766E !important;
            background-color: transparent !important;
            color: #0F766E !important;
        }
        
        [data-theme="light"] .video-galeri-btn:hover {
            background-color: rgba(15, 118, 110, 0.1) !important;
            border-color: #0D9488 !important;
            color: #0D9488 !important;
        }
        
        /* All icons - Professional Teal Colors */
        [data-theme="dark"] i.fas,
        [data-theme="dark"] i.fa,
        [data-theme="dark"] .fas,
        [data-theme="dark"] .fa {
            color: inherit;
        }
        
        [data-theme="light"] i.fas,
        [data-theme="light"] i.fa,
        [data-theme="light"] .fas,
        [data-theme="light"] .fa {
            color: inherit;
        }
        
        [data-theme="dark"] .text-primary i,
        [data-theme="dark"] i.text-primary {
            color: #14B8A6 !important;
        }
        
        [data-theme="light"] .text-primary i,
        [data-theme="light"] i.text-primary {
            color: #0F766E !important;
        }
        
        /* GRAVITY FORMS & ALL FORM PLUGINS - Autofill Override */
        .gform_wrapper input:-webkit-autofill,
        .gform_wrapper textarea:-webkit-autofill,
        .wpcf7 input:-webkit-autofill,
        .wpcf7 textarea:-webkit-autofill,
        form input:-webkit-autofill,
        form textarea:-webkit-autofill,
        .gform_wrapper input:-webkit-autofill:hover,
        .gform_wrapper input:-webkit-autofill:focus,
        .gform_wrapper input:-webkit-autofill:active,
        .gform_wrapper textarea:-webkit-autofill:hover,
        .gform_wrapper textarea:-webkit-autofill:focus,
        .gform_wrapper textarea:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px white inset !important;
            box-shadow: 0 0 0px 9999px white inset !important;
            -webkit-text-fill-color: #000000 !important;
            background-color: white !important;
            background-image: none !important;
            border: 1px solid #e2e8f0 !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        
        /* Dark mode for form plugins */
        [data-theme="dark"] .gform_wrapper input:-webkit-autofill,
        [data-theme="dark"] .gform_wrapper textarea:-webkit-autofill,
        [data-theme="dark"] .wpcf7 input:-webkit-autofill,
        [data-theme="dark"] .wpcf7 textarea:-webkit-autofill,
        [data-theme="dark"] form input:-webkit-autofill,
        [data-theme="dark"] form textarea:-webkit-autofill,
        [data-theme="dark"] .gform_wrapper input:-webkit-autofill:hover,
        [data-theme="dark"] .gform_wrapper input:-webkit-autofill:focus,
        [data-theme="dark"] .gform_wrapper input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px #1e293b inset !important;
            box-shadow: 0 0 0px 9999px #1e293b inset !important;
            -webkit-text-fill-color: white !important;
            background-color: #1e293b !important;
            border: 1px solid rgb(51, 65, 85) !important;
        }
        
        /* Light mode for form plugins */
        [data-theme="light"] .gform_wrapper input:-webkit-autofill,
        [data-theme="light"] .gform_wrapper textarea:-webkit-autofill,
        [data-theme="light"] .wpcf7 input:-webkit-autofill,
        [data-theme="light"] .wpcf7 textarea:-webkit-autofill,
        [data-theme="light"] form input:-webkit-autofill,
        [data-theme="light"] form textarea:-webkit-autofill,
        [data-theme="light"] .gform_wrapper input:-webkit-autofill:hover,
        [data-theme="light"] .gform_wrapper input:-webkit-autofill:focus,
        [data-theme="light"] .gform_wrapper input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 9999px white inset !important;
            box-shadow: 0 0 0px 9999px white inset !important;
            -webkit-text-fill-color: #0f172a !important;
            background-color: white !important;
            border: 1px solid #e2e8f0 !important;
        }
        
        /* Focus border for inputs */
        [data-theme="light"] input:focus,
        [data-theme="light"] select:focus,
        [data-theme="light"] textarea:focus {
            border-color: #0F766E !important;
            outline: none;
        }
        
        [data-theme="dark"] input:focus,
        [data-theme="dark"] select:focus,
        [data-theme="dark"] textarea:focus {
            border-color: #14B8A6 !important;
            outline: none;
        }
        
        /* Page Headers - Professional Colors */
        [data-theme="light"] .bg-gradient-to-b h1,
        [data-theme="light"] section h1 {
            color: #0F172A !important;
        }
        
        [data-theme="light"] .bg-gradient-to-b p,
        [data-theme="light"] section .text-muted-foreground {
            color: #475569 !important;
        }
        
        [data-theme="dark"] .bg-gradient-to-b h1,
        [data-theme="dark"] section h1 {
            color: #F8FAFC !important;
        }
        
        [data-theme="dark"] .bg-gradient-to-b p {
            color: #94A3B8 !important;
        }
        
        /* Icon Colors in Cards and Sections */
        [data-theme="dark"] .bg-gradient-to-br i.fas,
        [data-theme="dark"] .rounded-lg i.fas {
            color: white !important;
        }
        
        [data-theme="light"] .bg-gradient-to-br i.fas,
        [data-theme="light"] .rounded-lg i.fas {
            color: white !important;
        }
        
        /* Removed gradient icons, now using solid primary color */
        
        /* Prose content - hide duplicate h1 if exists */
        .prose h1:first-child {
            display: none;
        }
    </style>
</head>
<body <?php body_class('bg-background text-foreground flex flex-col min-h-screen'); ?>>

    <!-- Header -->
    <header class="w-full py-4 px-6 flex items-center justify-between sticky top-0 z-50 bg-background/95 backdrop-blur border-b border-white/5 relative">
        <div class="font-bold text-2xl tracking-tighter text-white flex items-center gap-2 z-[60]">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-custom no-underline flex items-center gap-2">
                <?php echo esc_html(get_option('viralay_site_title_text', 'VIRALAY')); ?>
            </a>
        </div>
        
        <!-- Desktop Nav -->
        <nav class="hidden md:flex gap-6 text-sm font-medium text-muted-foreground items-center">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-white transition-colors">Ana Sayfa</a>
            <a href="<?php echo esc_url(home_url('/video')); ?>" class="hover:text-white transition-colors">Videolar</a>
            <a href="<?php echo esc_url(home_url('/yayincilar')); ?>" class="hover:text-white transition-colors">Yayıncılar</a>
            <a href="<?php echo esc_url(home_url('/markalar-ajanslar')); ?>" class="hover:text-white transition-colors">Markalar</a>
            <a href="<?php echo esc_url(home_url('/hakkimizda')); ?>" class="hover:text-white transition-colors">Hakkımızda</a>
            <a href="<?php echo esc_url(home_url('/sss')); ?>" class="hover:text-white transition-colors">SSS</a>
            <?php 
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex gap-6',
                'fallback_cb' => false
            ));
            ?>
            
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="theme-toggle" aria-label="Tema Değiştir" title="Tema Değiştir">
                <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>
            
            <?php if (is_user_logged_in()) :
                $current_user = wp_get_current_user();
                $is_lisans_alicisi = in_array('lisans_alicisi', (array) $current_user->roles, true);
                $is_video_client = in_array('video_client', (array) $current_user->roles, true);
            ?>
                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="text-sm font-medium text-white hover:text-primary transition-colors">Çıkış Yap</a>
                <?php if ($is_lisans_alicisi) : ?>
                    <a href="<?php echo esc_url(home_url('/lisans-paneli')); ?>" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors">
                        Lisans Paneli
                    </a>
                <?php elseif ($is_video_client) : ?>
                    <a href="<?php echo esc_url(viral_ay_get_link('user_panel')); ?>" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors">
                        Panel
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/wp-admin')); ?>" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors">
                        Yönetim
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <a href="<?php echo esc_url(viral_ay_get_link('login')); ?>" class="text-sm font-medium text-white hover:text-primary transition-colors">Giriş Yap</a>
                <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="video-galeri-btn px-4 py-2 rounded-full text-sm font-semibold border transition-colors">
                    Lisans Al
                </a>
                <a href="<?php echo esc_url(viral_ay_get_link('video_submit')); ?>" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-full text-sm font-semibold transition-colors">
                    Video Gönder
                </a>
            <?php endif; ?>
        </nav>

        <!-- Mobile Theme Toggle & Menu Button -->
        <div class="md:hidden flex items-center gap-2 z-[60]">
            <button id="theme-toggle-mobile" class="theme-toggle" aria-label="Tema Değiştir" title="Tema Değiştir">
                <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>
            <button id="mobile-menu-btn" class="text-white p-2 relative">
                <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                <svg id="close-icon" class="hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="fixed inset-0 bg-black/95 backdrop-blur-xl z-50 flex flex-col pt-24 px-8 md:hidden h-screen w-screen overflow-y-auto">
            <nav class="flex flex-col gap-6 text-lg font-medium text-muted-foreground w-full">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-white text-2xl font-bold mb-4">Ana Sayfa</a>
                
                <a href="<?php echo esc_url(home_url('/video')); ?>" class="text-white hover:text-primary border-b border-white/10 pb-4 transition-colors">Videolar</a>
                <a href="<?php echo esc_url(home_url('/yayincilar')); ?>" class="text-white hover:text-primary border-b border-white/10 pb-4 transition-colors">Yayıncılar</a>
                <a href="<?php echo esc_url(home_url('/markalar-ajanslar')); ?>" class="text-white hover:text-primary border-b border-white/10 pb-4 transition-colors">Markalar</a>
                <a href="<?php echo esc_url(home_url('/hakkimizda')); ?>" class="text-white hover:text-primary border-b border-white/10 pb-4 transition-colors">Hakkımızda</a>
                <a href="<?php echo esc_url(home_url('/sss')); ?>" class="text-white hover:text-primary border-b border-white/10 pb-4 transition-colors">SSS</a>
                
                <?php 
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'flex flex-col gap-6 border-b border-white/5 pb-4',
                    'fallback_cb' => false
                ));
                ?>
                
                <div class="flex flex-col gap-4 mt-4">
                    <?php if (is_user_logged_in()) :
                        $mobile_user = wp_get_current_user();
                        $mobile_is_lisans_alicisi = in_array('lisans_alicisi', (array) $mobile_user->roles, true);
                        $mobile_is_video_client = in_array('video_client', (array) $mobile_user->roles, true);
                    ?>
                        <?php if ($mobile_is_lisans_alicisi) : ?>
                            <a href="<?php echo esc_url(home_url('/lisans-paneli')); ?>" class="w-full text-center py-3 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold">Lisans Paneli</a>
                        <?php elseif ($mobile_is_video_client) : ?>
                            <a href="<?php echo esc_url(viral_ay_get_link('user_panel')); ?>" class="w-full text-center py-3 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold">Panel</a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/wp-admin')); ?>" class="w-full text-center py-3 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold">Yönetim</a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="w-full text-center py-3 rounded-lg border border-white/10 text-white hover:bg-white/5">Çıkış Yap</a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(viral_ay_get_link('login')); ?>" class="w-full text-center py-3 rounded-lg border border-white/10 text-white hover:bg-white/5">Giriş Yap</a>
                        <a href="<?php echo esc_url(home_url('/lisans-al')); ?>" class="w-full text-center py-3 rounded-lg border border-teal-500/50 text-teal-400 hover:bg-teal-500/10 font-semibold">Lisans Al</a>
                        <a href="<?php echo esc_url(viral_ay_get_link('video_submit')); ?>" class="w-full text-center py-3 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold">Video Gönder</a>
                    <?php endif; ?>
                </div>

                <!-- Extra Pages -->
                <div class="grid grid-cols-2 gap-4 mt-4 text-sm text-muted-foreground">
                    <a href="<?php echo esc_url(home_url('/hizmet-sartlari')); ?>">Hizmet Şartları</a>
                    <a href="<?php echo esc_url(home_url('/gizlilik-politikasi')); ?>">Gizlilik</a>
                    <a href="<?php echo esc_url(home_url('/kvkk-aydinlatma-metni')); ?>">KVKK</a>
                    <a href="<?php echo esc_url(home_url('/cerez-politikasi')); ?>">Çerez</a>
                </div>
            </nav>
        </div>
    </header>

    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        let isOpen = false;

        menuBtn.addEventListener('click', function() {
            isOpen = !isOpen;
            if (isOpen) {
                mobileMenu.classList.add('open');
                document.body.style.overflow = 'hidden';
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            } else {
                mobileMenu.classList.remove('open');
                document.body.style.overflow = '';
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
        
        // Theme Toggle Function
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            
            if (newTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            
            localStorage.setItem('viralay-theme', newTheme);
        }
        
        // Desktop Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }
        
        // Mobile Theme Toggle
        const themeToggleMobile = document.getElementById('theme-toggle-mobile');
        if (themeToggleMobile) {
            themeToggleMobile.addEventListener('click', toggleTheme);
        }
    </script>
    
    <main id="main" class="flex-1">
