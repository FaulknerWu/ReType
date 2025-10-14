<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle([
        'category' => _t('分类 %s 下的文章'),
        'search' => _t('包含关键字 %s 的文章'),
        'tag' => _t('标签 %s 下的文章'),
        'author' => _t('%s 发布的文章')
    ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <?php
    $bootstrapCss = retypeGetThemeSetting('bootstrapCssUrl', 'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css');
    $bootstrapJs = retypeGetThemeSetting('bootstrapJsUrl', 'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.8/js/bootstrap.bundle.min.js');
    $bootstrapIcons = retypeGetThemeSetting('bootstrapIconsCssUrl', 'https://cdn.bootcdn.net/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css');
    $prismCss = retypeGetThemeSetting('prismCssUrl', 'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/themes/prism.min.css');
    $prismJs = retypeGetThemeSetting('prismJsUrl', 'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/prism.min.js');
    $katexCssDefault = 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.css';
    $katexJsDefault = 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.js';
    $katexAutoRenderDefault = 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/contrib/auto-render.min.js';
    $fontStylesheets = retypeGetFontStylesheetList();
    ?>

    <!-- CSS Resources -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('normalize.css'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($bootstrapCss, ENT_QUOTES); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($bootstrapIcons, ENT_QUOTES); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($prismCss, ENT_QUOTES); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">

    <?php foreach ($fontStylesheets as $fontUrl): ?>
        <link rel="preload" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>" as="style">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>" media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>">
        </noscript>
    <?php endforeach; ?>

    <!-- JavaScript Resources -->
    <script defer src="<?php echo htmlspecialchars($prismJs, ENT_QUOTES); ?>"></script>
    <script defer src="<?php echo htmlspecialchars($bootstrapJs, ENT_QUOTES); ?>"></script>

    <!-- LaTeX Support -->
    <?php if (retypeShouldLoadLatex($this)): ?>
        <?php
        $katexCss = retypeGetThemeSetting('katexCssUrl', $katexCssDefault);
        $katexJs = retypeGetThemeSetting('katexJsUrl', $katexJsDefault);
        $katexAutoRenderJs = retypeGetThemeSetting('katexAutoRenderJsUrl', $katexAutoRenderDefault);
        ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($katexCss, ENT_QUOTES); ?>">
        <script defer src="<?php echo htmlspecialchars($katexJs, ENT_QUOTES); ?>"></script>
        <script defer src="<?php echo htmlspecialchars($katexAutoRenderJs, ENT_QUOTES); ?>"></script>
    <?php endif; ?>

    <?php $this->header(); ?>
</head>

<body class="bg-light">
    <div class="site-wrapper d-flex flex-column min-vh-100">
        <header id="header" class="bg-white py-2 mb-4 shadow-sm sticky-top">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo/Title Section -->
                    <div class="col-12 col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                        <?php if ($this->options->logoUrl): ?>
                            <a href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title(); ?>">
                                <img src="<?php $this->options->logoUrl(); ?>" 
                                     alt="<?php $this->options->title(); ?>"
                                     class="img-fluid" 
                                     style="max-height: 60px;">
                            </a>
                        <?php else: ?>
                            <a href="<?php $this->options->siteUrl(); ?>" 
                               class="text-dark fs-1 text-decoration-none"
                               title="<?php $this->options->title(); ?>">
                                <?php $this->options->title(); ?>
                            </a>
                        <?php endif; ?>
                        <p class="text-muted fst-italic fs-6 mb-0"><?php $this->options->description(); ?></p>
                    </div>

                    <!-- Search Section -->
                    <div class="col-12 col-lg-4">
                        <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                            <div class="input-group">
                                <input type="text" 
                                       name="s" 
                                       class="form-control border-dark"
                                       placeholder="<?php _e('输入关键字搜索...'); ?>"
                                       aria-label="<?php _e('搜索'); ?>">
                                <button type="submit" class="btn btn-dark" aria-label="<?php _e('搜索'); ?>">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="navbar navbar-expand-lg mt-1 pb-0" aria-label="<?php _e('主导航'); ?>">
                        <button class="navbar-toggler" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#navbarNav"
                                aria-controls="navbarNav"
                                aria-expanded="false"
                                aria-label="<?php _e('切换导航'); ?>">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link<?php if ($this->is('index')): ?> active fw-bold<?php endif; ?>"
                                       href="<?php $this->options->siteUrl(); ?>"
                                       <?php if ($this->is('index')): ?>aria-current="page"<?php endif; ?>>
                                        <i class="bi bi-house me-1"></i><?php _e('首页'); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?php if ($this->is('page', 'links')): ?> active fw-bold<?php endif; ?>"
                                       href="<?php $this->options->siteUrl('links.html'); ?>"
                                       <?php if ($this->is('page', 'links')): ?>aria-current="page"<?php endif; ?>>
                                        <i class="bi bi-link me-1"></i><?php _e('友情链接'); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?php if ($this->is('archive')): ?> active fw-bold<?php endif; ?>"
                                       href="<?php $this->options->siteUrl('archive.html'); ?>"
                                       <?php if ($this->is('archive')): ?>aria-current="page"<?php endif; ?>>
                                        <i class="bi bi-archive me-1"></i><?php _e('文章归档'); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?php if ($this->is('page', 'start-page')): ?> active fw-bold<?php endif; ?>"
                                       href="<?php $this->options->siteUrl('start-page.html'); ?>"
                                       <?php if ($this->is('page', 'start-page')): ?>aria-current="page"<?php endif; ?>>
                                        <i class="bi bi-info-circle me-1"></i><?php _e('关于本站'); ?>
                                    </a>
                                </li>
                            </ul>
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link<?php if ($this->is('feed')): ?> active fw-bold<?php endif; ?>"
                                       href="<?php $this->options->feedUrl(); ?>"
                                       title="<?php _e('RSS订阅'); ?>"
                                       <?php if ($this->is('feed')): ?>aria-current="page"<?php endif; ?>>
                                        <i class="bi bi-rss"></i> <?php _e('RSS订阅'); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" title="GitHub" aria-label="GitHub">
                                        <i class="bi bi-github"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" title="Twitter" aria-label="Twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <main id="site-main" class="site-main flex-grow-1" role="main">
