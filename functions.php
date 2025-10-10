<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 主题配置
 * 
 * @param \Typecho\Widget\Helper\Form $form 表单对象
 */
function themeConfig($form)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点 LOGO 地址'),
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 LOGO')
    );
    $form->addInput($logoUrl);

    $sidebarBlock = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sidebarBlock',
        [
            'ShowRecentPosts'    => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory'       => _t('显示分类'),
            'ShowArchive'        => _t('显示归档'),
            'ShowOther'          => _t('显示其它杂项')
        ],
        ['ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'],
        _t('侧边栏显示')
    );
    $form->addInput($sidebarBlock->multiMode());
}

/**
 * 文章字段配置
 * 
 * @param \Typecho\Widget\Helper\Layout $layout 布局对象
 */
function themeFields($layout)
{
    $isLatex = new \Typecho\Widget\Helper\Form\Element\Radio(
        'isLatex',
        [
            1 => _t('启用'),
            0 => _t('关闭')
        ],
        0,
        _t('LaTeX 渲染'),
        _t('默认关闭增加网页访问速度，如文章内存在LaTeX语法则需要启用')
    );
    $layout->addItem($isLatex);
}

/**
 * Return sanitized thumbnail URL for the current post if available.
 *
 * @param \Typecho_Widget_Archive $archive 当前文章对象
 * @return string|null
 */
function retypeGetThumbnailUrl($archive)
{
    if (!isset($archive->fields->thumbnail) || empty($archive->fields->thumbnail)) {
        return null;
    }

    return htmlspecialchars((string)$archive->fields->thumbnail, ENT_QUOTES);
}

/**
 * Determine whether KaTeX assets should be loaded for the current request.
 *
 * @param \Typecho_Widget_Archive $archive 当前页面对象
 * @return bool
 */
function retypeShouldLoadLatex($archive)
{
    if ($archive->is('index')) {
        return true;
    }

    if (!$archive->is('post')) {
        return false;
    }

    $isLatexEnabled = isset($archive->fields->isLatex)
        ? (int)$archive->fields->isLatex === 1
        : false;

    return $isLatexEnabled;
}

/**
 * 输出文章的元信息（作者 / 日期 / 分类 / 评论数）。
 *
 * @param \Typecho_Widget_Archive $archive 当前文章对象
 * @param array $options 输出配置
 * @return void
 */
function retypeRenderPostMeta($archive, array $options = [])
{
    $defaults = [
        'containerClass'   => 'post-meta d-flex flex-wrap mb-3 text-muted small',
        'showCategories'   => true,
        'showComments'     => true,
        'authorIcon'       => 'bi-person-circle',
        'dateIcon'         => 'bi-calendar3',
        'categoryIcon'     => 'bi-folder2',
        'commentsIcon'     => 'bi-chat-left',
        'commentsAnchor'   => '#comments',
        'authorItemprop'   => true,
        'dateItemprop'     => true,
        'outputAriaLabels' => true,
    ];

    $config = array_merge($defaults, $options);
    $containerClass = htmlspecialchars($config['containerClass'], ENT_QUOTES);
    $ariaLabels = $config['outputAriaLabels'];
    $commentsAnchor = htmlspecialchars((string)$config['commentsAnchor'], ENT_QUOTES);

    $rawAuthorName = isset($archive->author->screenName)
        ? (string)$archive->author->screenName
        : '';

    ob_start();
    $archive->author->permalink();
    $authorPermalink = ob_get_clean();

    ob_start();
    $archive->permalink();
    $postPermalink = ob_get_clean();

    echo '<div class="' . $containerClass . '">';

    echo '<span class="me-3">';
    echo '<i class="bi ' . htmlspecialchars($config['authorIcon'], ENT_QUOTES) . '"></i> ';
    echo '<a href="' . $authorPermalink . '" class="text-reset text-decoration-none"';
    if ($ariaLabels) {
        echo ' aria-label="' . htmlspecialchars(_t('作者: %s', $rawAuthorName), ENT_QUOTES) . '"';
    }
    if ($config['authorItemprop']) {
        echo ' itemprop="author"';
    }
    echo '>';
    $archive->author();
    echo '</a>';
    echo '</span>';

    echo '<span class="me-3">';
    echo '<i class="bi ' . htmlspecialchars($config['dateIcon'], ENT_QUOTES) . '"></i> ';
    echo '<time datetime="';
    $archive->date('c');
    echo '"';
    if ($config['dateItemprop']) {
        echo ' itemprop="datePublished"';
    }
    echo '>';
    $archive->date();
    echo '</time>';
    echo '</span>';

    if ($config['showCategories']) {
        echo '<span class="me-3">';
        echo '<i class="bi ' . htmlspecialchars($config['categoryIcon'], ENT_QUOTES) . '"></i> ';
        $archive->category(',');
        echo '</span>';
    }

    if ($config['showComments']) {
        echo '<span>';
        echo '<i class="bi ' . htmlspecialchars($config['commentsIcon'], ENT_QUOTES) . '"></i> ';
        echo '<a href="' . $postPermalink . $commentsAnchor . '" class="text-reset text-decoration-none">';
        $archive->commentsNum(_t('评论'), _t('1 条评论'), _t('%d 条评论'));
        echo '</a>';
        echo '</span>';
    }

    echo '</div>';
}
