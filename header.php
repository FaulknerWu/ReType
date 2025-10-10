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

    <!-- CSS Resources -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('normalize.css'); ?>">
    <link rel="stylesheet" href="https://cdn.faulkner.fun/libs/bootstrap/5.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.faulkner.fun/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.faulkner.fun/libs/prism/prism.css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">

    <!-- JavaScript Resources -->
    <script defer src="https://cdn.faulkner.fun/libs/prism/prism.js"></script>
    <script defer src="https://cdn.faulkner.fun/libs/bootstrap/5.3.5/js/bootstrap.bundle.min.js"></script>

    <!-- LaTeX Support -->
    <?php if (retypeShouldLoadLatex($this)): ?>
        <link rel="stylesheet" href="https://cdn.faulkner.fun/libs/katex/0.16.22/katex.min.css">
        <script defer src="https://cdn.faulkner.fun/libs/katex/0.16.22/katex.min.js"></script>
        <script defer src="https://cdn.faulkner.fun/libs/katex/0.16.22/contrib/auto-render.min.js"></script>
    <?php endif; ?>

    <?php $this->header(); ?>
</head>

<body class="bg-light">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add line numbers to code blocks
            document.querySelectorAll('pre:not(.line-numbers):not(.no-line-numbers)').forEach(pre => {
                if (!pre.closest('.no-line-numbers')) {
                    pre.classList.add('line-numbers');
                }
            });

            // Initialize LaTeX rendering if enabled
            <?php if (retypeShouldLoadLatex($this)): ?>
                if (typeof renderMathInElement !== 'undefined') {
                    renderMathInElement(document.body, {
                        delimiters: [
                            { left: "$$", right: "$$", display: true },
                            { left: "$", right: "$", display: false },
                            { left: "\\(", right: "\\)", display: false },
                            { left: "\\[", right: "\\]", display: true }
                        ],
                        throwOnError: false
                    });
                }
            <?php endif; ?>
        });
    </script>
