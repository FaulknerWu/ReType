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

/**
 * Render a unified post list/card item to avoid repeated markup in templates.
 *
 * @param \Typecho_Widget_Archive $archive 当前文章对象
 * @param array $options 输出配置
 * @return void
 */
function retypeRenderPostCard($archive, array $options = [])
{
    $defaults = [
        'wrapperClass'     => 'post-card mb-4 shadow-sm border rounded-3 p-4',
        'schemaType'       => 'http://schema.org/BlogPosting',
        'headingTag'       => 'h2',
        'titleClass'       => 'post-title fw-bold mb-3',
        'contentClass'     => 'post-content',
        'excerptClass'     => 'post-excerpt mb-3',
        'contentMode'      => 'content', // content|excerpt
        'excerptLength'    => 150,
        'excerptSuffix'    => '...',
        'titleLinkClass'   => 'text-decoration-none text-dark',
        'showTags'         => false,
        'tagsWrapperClass' => 'tags',
        'tagsIconClass'    => 'bi bi-tags',
        'tagBadgeClass'    => 'badge bg-light text-dark me-1',
        'emptyTagText'     => _t('暂无标签'),
        'metaOptions'      => [],
        'readMore'         => [
            'enabled'      => false,
            'wrapperClass' => 'text-end mt-3',
            'buttonClass'  => 'btn btn-outline-primary btn-sm',
            'label'        => _t('阅读全文'),
            'iconHtml'     => '<i class="bi bi-arrow-right"></i>',
        ],
    ];

    $config = array_merge($defaults, $options);
    $config['readMore'] = array_merge($defaults['readMore'], $config['readMore']);
    $metaOptions = isset($config['metaOptions']) && is_array($config['metaOptions'])
        ? $config['metaOptions']
        : [];

    $wrapperClass = htmlspecialchars($config['wrapperClass'], ENT_QUOTES);
    $schemaType = htmlspecialchars($config['schemaType'], ENT_QUOTES);
    $headingTag = preg_replace('/[^a-z0-9]/i', '', (string)$config['headingTag']);
    $headingTag = $headingTag !== '' ? strtolower($headingTag) : 'h2';
    $titleClass = htmlspecialchars($config['titleClass'], ENT_QUOTES);
    $contentClass = htmlspecialchars($config['contentClass'], ENT_QUOTES);
    $excerptClass = htmlspecialchars($config['excerptClass'], ENT_QUOTES);
    $titleLinkClass = htmlspecialchars($config['titleLinkClass'], ENT_QUOTES);
    $excerptSuffix = htmlspecialchars((string)$config['excerptSuffix'], ENT_QUOTES);

    $thumbnailUrl = retypeGetThumbnailUrl($archive);

    echo '<article class="' . $wrapperClass . '" itemscope itemtype="' . $schemaType . '">';

    if ($thumbnailUrl) {
        echo '<div class="post-thumbnail mb-3">';
        echo '<a href="';
        $archive->permalink();
        echo '" class="d-block">';
        echo '<img src="' . $thumbnailUrl . '" alt="';
        $archive->title();
        echo '" class="img-fluid rounded w-100" loading="lazy" itemprop="image">';
        echo '</a>';
        echo '</div>';
    }

    echo '<' . $headingTag . ' class="' . $titleClass . '" itemprop="headline">';
    echo '<a href="';
    $archive->permalink();
    echo '" class="' . $titleLinkClass . '" itemprop="url">';
    $archive->title();
    echo '</a>';
    echo '</' . $headingTag . '>';

    retypeRenderPostMeta($archive, $metaOptions);

    $contentAttr = $config['contentMode'] === 'excerpt' ? 'description' : 'articleBody';
    $contentWrapperClass = $config['contentMode'] === 'excerpt' ? $excerptClass : $contentClass;
    echo '<div class="' . $contentWrapperClass . '" itemprop="' . $contentAttr . '">';
    if ($config['contentMode'] === 'excerpt') {
        $archive->excerpt((int)$config['excerptLength'], $excerptSuffix);
    } else {
        $archive->content('');
    }
    echo '</div>';

    if ($config['showTags'] && $archive->tags) {
        $tagsWrapperClass = htmlspecialchars($config['tagsWrapperClass'], ENT_QUOTES);
        $tagsIconClass = htmlspecialchars($config['tagsIconClass'], ENT_QUOTES);
        $tagBadgeClass = htmlspecialchars($config['tagBadgeClass'], ENT_QUOTES);
        echo '<div class="' . $tagsWrapperClass . '">';
        if ($tagsIconClass !== '') {
            echo '<i class="' . $tagsIconClass . '"></i> ';
        }
        $archive->tags(
            '<span class="' . $tagBadgeClass . '">',
            '</span>',
            htmlspecialchars($config['emptyTagText'], ENT_QUOTES)
        );
        echo '</div>';
    }

    if ($config['readMore']['enabled']) {
        $readMoreWrapper = htmlspecialchars($config['readMore']['wrapperClass'], ENT_QUOTES);
        $readMoreBtn = htmlspecialchars($config['readMore']['buttonClass'], ENT_QUOTES);
        $readMoreLabel = htmlspecialchars($config['readMore']['label'], ENT_QUOTES);
        echo '<div class="' . $readMoreWrapper . '">';
        echo '<a href="';
        $archive->permalink();
        echo '" class="' . $readMoreBtn . '">';
        echo $readMoreLabel;
        if ($config['readMore']['iconHtml'] !== '') {
            echo ' ' . $config['readMore']['iconHtml'];
        }
        echo '</a>';
        echo '</div>';
    }

    echo '</article>';
}
