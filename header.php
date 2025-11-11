<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

$options = retypeGetOptions(isset($this) ? $this : null);
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="<?php $options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle([
        'category' => _t('分类 %s 下的文章'),
        'search' => _t('包含关键字 %s 的文章'),
        'tag' => _t('标签 %s 下的文章'),
        'author' => _t('%s 发布的文章')
    ], '', ' - '); ?><?php $options->title(); ?></title>

    <?php
    $externalAssets = retypeGetExternalAssets();
    $fontStylesheets = retypeGetFontStylesheetList();
    $navGroups = retypeGetNavigationGroups($this);
    ?>

    <!-- CSS Resources -->
    <link rel="stylesheet" href="<?php $options->themeUrl('normalize.css'); ?>">
    <?php foreach ($externalAssets['styles'] as $style): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($style['url'], ENT_QUOTES); ?>">
    <?php endforeach; ?>
    <link rel="stylesheet" href="<?php $options->themeUrl('style.css'); ?>">

    <?php foreach ($fontStylesheets as $fontUrl): ?>
        <link rel="preload" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>" as="style">
        <link rel="stylesheet" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>" media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($fontUrl, ENT_QUOTES); ?>">
        </noscript>
    <?php endforeach; ?>

    <!-- JavaScript Resources -->
    <?php foreach ($externalAssets['scripts'] as $script): ?>
        <script <?php echo !empty($script['defer']) ? 'defer ' : ''; ?>src="<?php echo htmlspecialchars($script['url'], ENT_QUOTES); ?>"></script>
    <?php endforeach; ?>

    <!-- LaTeX Support -->
    <?php if (retypeShouldLoadLatex($this)): ?>
        <?php
        $katexCss = retypeGetThemeSetting('katexCssUrl', retypeGetAssetDefault('katexCssUrl'));
        $katexJs = retypeGetThemeSetting('katexJsUrl', retypeGetAssetDefault('katexJsUrl'));
        $katexAutoRenderJs = retypeGetThemeSetting('katexAutoRenderJsUrl', retypeGetAssetDefault('katexAutoRenderJsUrl'));
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
                        <?php if ($options->logoUrl): ?>
                            <a href="<?php $options->siteUrl(); ?>" title="<?php $options->title(); ?>">
                                <img src="<?php $options->logoUrl(); ?>" 
                                     alt="<?php $options->title(); ?>"
                                     class="img-fluid" 
                                     style="max-height: 60px;">
                            </a>
                        <?php else: ?>
                            <a href="<?php $options->siteUrl(); ?>" 
                               class="text-dark fs-1 text-decoration-none"
                               title="<?php $options->title(); ?>">
                                <?php $options->title(); ?>
                            </a>
                        <?php endif; ?>
                        <p class="text-muted fst-italic fs-6 mb-0"><?php $options->description(); ?></p>
                    </div>

                    <!-- Search Section -->
                    <div class="col-12 col-lg-4">
                        <form method="post"
                              action="<?php $options->siteUrl(); ?>"
                              role="search"
                              class="retype-search-form">
                            <label for="site-search-input" class="visually-hidden">
                                <?php _e('搜索'); ?>
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       id="site-search-input"
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
                    <nav class="navbar navbar-expand-lg mt-1 pb-0 retype-navbar" aria-label="<?php _e('主导航'); ?>">
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
                            <ul class="navbar-nav retype-nav retype-nav--primary">
                                <?php retypeRenderNavItems($navGroups['primary']); ?>
                            </ul>
                            <ul class="navbar-nav ms-auto retype-nav retype-nav--secondary">
                                <?php retypeRenderNavItems($navGroups['secondary']); ?>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <main id="site-main" class="site-main flex-grow-1" role="main">
