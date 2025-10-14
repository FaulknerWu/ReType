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

    $enableRemoteFonts = new \Typecho\Widget\Helper\Form\Element\Radio(
        'enableRemoteFonts',
        [1 => _t('启用'), 0 => _t('禁用')],
        1,
        _t('加载远程字体'),
        _t('禁用后将使用系统字体，避免首屏等待远程字体。如果启用，建议搭配 font-display:swap 的字体文件。')
    );
    $form->addInput($enableRemoteFonts);

    $fontStylesheetUrls = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'fontStylesheetUrls',
        null,
        "https://cdn.faulkner.fun/libs/fonts/lxgwwenkai-regular/1.511/result.css\nhttps://fontsapi.zeoseven.com/447/main/result.css",
        _t('字体样式表 URL（每行一个）'),
        _t('用于引入自定义字体的 CSS 文件，可填写多个地址，每行一个。留空或禁用「加载远程字体」将不引入远程字体。')
    );
    $fontStylesheetUrls->input->setAttribute('rows', 3);
    $form->addInput($fontStylesheetUrls);

    $bootstrapCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapCssUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css',
        _t('Bootstrap CSS 地址'),
        _t('用于加载 Bootstrap 样式。建议填写国内可访问的 CDN，留空将使用默认地址。')
    );
    $form->addInput($bootstrapCssUrl);

    $bootstrapJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapJsUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js',
        _t('Bootstrap JS 地址'),
        _t('用于加载 Bootstrap 交互脚本。建议填写国内可访问的 CDN，留空将使用默认地址。')
    );
    $form->addInput($bootstrapJsUrl);

    $bootstrapIconsCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapIconsCssUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css',
        _t('Bootstrap Icons CSS 地址'),
        _t('用于加载 Bootstrap Icons 图标字体。')
    );
    $form->addInput($bootstrapIconsCssUrl);

    $prismCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'prismCssUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/themes/prism.min.css',
        _t('Prism CSS 地址'),
        _t('用于代码高亮的样式文件。')
    );
    $form->addInput($prismCssUrl);

    $prismJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'prismJsUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/prism.min.js',
        _t('Prism JS 地址'),
        _t('用于代码高亮的脚本文件。')
    );
    $form->addInput($prismJsUrl);

    $katexCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexCssUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.css',
        _t('KaTeX CSS 地址'),
        _t('用于渲染 LaTeX 的样式文件。')
    );
    $form->addInput($katexCssUrl);

    $katexJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexJsUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.js',
        _t('KaTeX JS 地址'),
        _t('用于渲染 LaTeX 的核心脚本。')
    );
    $form->addInput($katexJsUrl);

    $katexAutoRenderJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexAutoRenderJsUrl',
        null,
        'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/contrib/auto-render.min.js',
        _t('KaTeX 自动渲染脚本地址'),
        _t('用于扫描页面内容并自动渲染 LaTeX 语法。')
    );
    $form->addInput($katexAutoRenderJsUrl);

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

    $icpNumber = new \Typecho\Widget\Helper\Form\Element\Text(
        'icpNumber',
        null,
        '',
        _t('ICP备案号'),
        _t('在此填写备案号（例如：粤ICP备12345678号）。留空则不显示备案信息。')
    );
    $form->addInput($icpNumber);
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
            0 => _t('自动检测'),
            1 => _t('强制启用'),
            2 => _t('强制关闭')
        ],
        0,
        _t('LaTeX 渲染'),
        _t('默认自动检测文章内容是否包含 LaTeX 语法，也可以根据需要强制启用或关闭。')
    );
    $layout->addItem($isLatex);
}

/**
 * 获取指定的主题配置值，带默认值。
 *
 * @param string $name 配置字段名
 * @param mixed $default 默认值
 * @return mixed
 */
function retypeGetThemeSetting($name, $default = '')
{
    static $options = null;

    if ($options === null) {
        $options = \Typecho_Widget::widget('Widget_Options');
    }

    if (isset($options->{$name}) && $options->{$name} !== '' && $options->{$name} !== null) {
        $value = $options->{$name};
        return is_string($value) ? trim($value) : $value;
    }

    return $default;
}

/**
 * 获取需加载的字体样式表列表。
 *
 * @return array
 */
function retypeGetFontStylesheetList()
{
    if ((int)retypeGetThemeSetting('enableRemoteFonts', 1) !== 1) {
        return [];
    }

    $raw = (string)retypeGetThemeSetting('fontStylesheetUrls');

    if ($raw === '') {
        return [];
    }

    $lines = preg_split("/\r\n|\r|\n/", $raw);
    $urls = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || preg_match('/^#/', $line)) {
            continue;
        }
        $urls[] = $line;
    }

    return $urls;
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
 * Detect whether the current archive likely contains LaTeX syntax.
 *
 * @param \Typecho_Widget_Archive $archive 当前页面对象
 * @return bool
 */
function retypeArchiveContainsLatex($archive)
{
    $candidates = [];

    if (isset($archive->row) && is_array($archive->row) && isset($archive->row['text'])) {
        $candidates[] = (string)$archive->row['text'];
    }

    if (isset($archive->text) && is_string($archive->text)) {
        $candidates[] = (string)$archive->text;
    }

    if (isset($archive->pageRow) && is_array($archive->pageRow) && isset($archive->pageRow['text'])) {
        $candidates[] = (string)$archive->pageRow['text'];
    }

    if (empty($candidates)) {
        ob_start();
        $archive->content();
        $candidates[] = ob_get_clean();
    }

    $patterns = [
        '$$',
        '\\(',
        '\\[',
        '\\begin{',
        '\\frac',
        '\\sum',
        '\\int'
    ];

    foreach ($candidates as $source) {
        if (!is_string($source) || $source === '') {
            continue;
        }

        foreach ($patterns as $pattern) {
            if (strpos($source, $pattern) !== false) {
                return true;
            }
        }
    }

    return false;
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

    if (!($archive->is('post') || $archive->is('page'))) {
        return false;
    }

    $fieldValue = isset($archive->fields->isLatex)
        ? (string)$archive->fields->isLatex
        : null;

    if ($fieldValue === '1') {
        return true;
    }

    if ($fieldValue === '2') {
        return false;
    }

    return retypeArchiveContainsLatex($archive);
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
