<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

if (!defined('RETYPE_DEFAULT_PRIMARY_NAV')) {
    define('RETYPE_DEFAULT_PRIMARY_NAV', <<<'TEXT'
label=首页;href=/;icon=bi bi-house;match=index
label=友情链接;href=/links.html;icon=bi bi-link;match=page:links
label=文章归档;href=/archive.html;icon=bi bi-archive;match=archive
label=关于本站;href=/start-page.html;icon=bi bi-info-circle;match=page:start-page
TEXT);
}

if (!defined('RETYPE_DEFAULT_SECONDARY_NAV')) {
    define('RETYPE_DEFAULT_SECONDARY_NAV', <<<'TEXT'
label=RSS订阅;href={feed};icon=bi bi-rss;match=feed;title=RSS订阅
label=GitHub;href=https://github.com/your-id;icon=bi bi-github;target=_blank;rel=noreferrer noopener
label=Twitter;href=https://twitter.com/your-id;icon=bi bi-twitter;target=_blank;rel=noreferrer noopener
TEXT);
}

if (!defined('RETYPE_ASSET_DEFAULTS')) {
    define('RETYPE_ASSET_DEFAULTS', [
        'bootstrapCssUrl'        => 'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css',
        'bootstrapJsUrl'         => 'https://cdn.bootcdn.net/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js',
        'bootstrapIconsCssUrl'   => 'https://cdn.bootcdn.net/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css',
        'prismCssUrl'            => 'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/themes/prism.min.css',
        'prismJsUrl'             => 'https://cdn.bootcdn.net/ajax/libs/prism/1.30.0/prism.min.js',
        'katexCssUrl'            => 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.css',
        'katexJsUrl'             => 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/katex.min.js',
        'katexAutoRenderJsUrl'   => 'https://cdn.bootcdn.net/ajax/libs/KaTeX/0.16.9/contrib/auto-render.min.js',
    ]);
}

/**
 * 将主题设置字段标记为可被导入导出脚本识别。
 *
 * @param \Typecho\Widget\Helper\Form\Element $element
 * @return void
 */
function retypeMarkThemeSettingElement(\Typecho\Widget\Helper\Form\Element $element)
{
    if (empty($element->name)) {
        return;
    }

    $element->setAttribute('data-retype-setting-name', $element->name);
}

/**
 * 将主题设置字段添加到表单并自动注入标记。
 *
 * @param \Typecho\Widget\Helper\Form $form
 * @param \Typecho\Widget\Helper\Form\Element $element
 * @return void
 */
function retypeAddThemeSettingInput(\Typecho\Widget\Helper\Form $form, \Typecho\Widget\Helper\Form\Element $element)
{
    retypeMarkThemeSettingElement($element);
    $form->addInput($element);
}

/**
 * 注入主题配置导入/导出工具栏。
 *
 * @param \Typecho\Widget\Helper\Form $form
 * @return void
 */
function retypeAttachSettingsTransferToolbar(\Typecho\Widget\Helper\Form $form)
{
    static $toolbarInjected = false;

    if ($toolbarInjected) {
        return;
    }

    $toolbarInjected = true;

    $layout = new \Typecho\Widget\Helper\Layout('ul', [
        'class' => 'typecho-option retype-config-transfer',
        'id'    => 'retype-config-transfer',
    ]);

    $title = htmlspecialchars(_t('主题设置导入/导出'), ENT_QUOTES);
    $desc = htmlspecialchars(_t('备份当前配置或在不同站点之间同步主题设置。'), ENT_QUOTES);
    $note = htmlspecialchars(_t('导出会自动下载 JSON 文件，导入将读取本地 JSON 并填入表单，提交前请再次保存。'), ENT_QUOTES);
    $exportText = htmlspecialchars(_t('导出 JSON 文件'), ENT_QUOTES);
    $importText = htmlspecialchars(_t('导入 JSON 文件'), ENT_QUOTES);

    $layout->html(<<<HTML
<li>
    <label class="typecho-label">{$title}</label>
    <div class="typecho-option-description">
        <p>{$desc}</p>
        <p>{$note}</p>
    </div>
    <p class="typecho-option-actions">
        <button type="button" class="btn primary" id="retype-export-settings">{$exportText}</button>
        <button type="button" class="btn" id="retype-import-settings">{$importText}</button>
    </p>
    <input type="file" id="retype-import-file" accept="application/json" hidden>
    <script>
(function () {
    function cssEscape(value) {
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(value);
        }
        return String(value).replace(/([ !\"#\$%&'\\(\\)\\*\\+,\\.\\/\\:;<=>\\?@\\[\\]\\^`\\{\\|\\}~\\\\])/g, '\\\\$1');
    }

    function getForm() {
        return document.getElementById('retype-theme-config-form') || document.querySelector('.typecho-page-main form');
    }

    function getTrackedNames(form) {
        return Array.from(form.querySelectorAll('[data-retype-setting-name]'))
            .map(function (node) {
                return node.getAttribute('data-retype-setting-name');
            })
            .filter(Boolean);
    }

    function getFields(form, name) {
        return form.querySelectorAll('[name=\"' + cssEscape(name) + '\"]');
    }

    function readValue(fields) {
        if (!fields.length) {
            return null;
        }
        const first = fields[0];
        const type = (first.type || '').toLowerCase();

        if (type === 'radio') {
            for (const field of fields) {
                if (field.checked) {
                    return field.value;
                }
            }
            return null;
        }

        if (type === 'checkbox') {
            const values = [];
            for (const field of fields) {
                if (field.checked) {
                    values.push(field.value);
                }
            }
            return values;
        }

        if (first.tagName === 'SELECT' && first.multiple) {
            return Array.from(first.options)
                .filter(function (option) {
                    return option.selected;
                })
                .map(function (option) {
                    return option.value;
                });
        }

        return first.value;
    }

    function collectSettings(form, names) {
        const result = {};
        names.forEach(function (name) {
            const fields = getFields(form, name);
            if (!fields.length) {
                return;
            }
            result[name] = readValue(fields);
        });
        return result;
    }

    function applySettings(form, names, data) {
        const tracked = new Set(names);
        Object.keys(data || {}).forEach(function (name) {
            if (!tracked.has(name)) {
                return;
            }

            const fields = getFields(form, name);
            if (!fields.length) {
                return;
            }

            const first = fields[0];
            const type = (first.type || '').toLowerCase();
            const value = data[name];

            if (type === 'radio') {
                fields.forEach(function (field) {
                    field.checked = String(field.value) === String(value);
                });
                return;
            }

            if (type === 'checkbox') {
                const values = Array.isArray(value) ? value.map(String) : [String(value)];
                fields.forEach(function (field) {
                    field.checked = values.includes(String(field.value));
                });
                return;
            }

            if (first.tagName === 'SELECT' && first.multiple) {
                const values = Array.isArray(value) ? value.map(String) : [String(value)];
                Array.from(first.options).forEach(function (option) {
                    option.selected = values.includes(String(option.value));
                });
                return;
            }

            first.value = value == null ? '' : value;
        });
    }

    function init() {
        const form = getForm();
        if (!form) {
            return;
        }

        const names = getTrackedNames(form);
        if (!names.length) {
            return;
        }

        const exportBtn = document.getElementById('retype-export-settings');
        const importBtn = document.getElementById('retype-import-settings');
        const fileInput = document.getElementById('retype-import-file');

        if (!exportBtn || !importBtn || !fileInput) {
            return;
        }

        function downloadJson(content) {
            const blob = new Blob([content], { type: 'application/json' });
            const link = document.createElement('a');
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            link.href = URL.createObjectURL(blob);
            link.download = 'retype-settings-' + timestamp + '.json';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }

        exportBtn.addEventListener('click', function () {
            const json = JSON.stringify(collectSettings(form, names), null, 2);
            downloadJson(json);
        });

        importBtn.addEventListener('click', function () {
            fileInput.value = '';
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            const file = fileInput.files && fileInput.files[0];
            if (!file) {
                return;
            }

            const fileName = (file.name || '').toLowerCase();
            const isJson = fileName.endsWith('.json') || file.type === 'application/json';
            if (!isJson) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function () {
                let data;
                try {
                    data = JSON.parse(reader.result);
                } catch (error) {
                    console.error('Typecho ReType: JSON parse error', error);
                    return;
                }
                applySettings(form, names, data);
            };
            reader.onerror = function () {
                console.error('Typecho ReType: File read error', reader.error);
            };
            reader.readAsText(file);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
</li>
HTML);

    $form->addItem($layout);
}

/**
 * 主题配置
 * 
 * @param \Typecho\Widget\Helper\Form $form 表单对象
 */
function themeConfig($form)
{
    $form->setAttribute('id', 'retype-theme-config-form');
    retypeAttachSettingsTransferToolbar($form);

    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点 LOGO 地址'),
        _t('在这里填入一个图片 URL 地址, 以在网站标题前加上一个 LOGO')
    );
    retypeAddThemeSettingInput($form, $logoUrl);

    $enableRemoteFonts = new \Typecho\Widget\Helper\Form\Element\Radio(
        'enableRemoteFonts',
        [1 => _t('启用'), 0 => _t('禁用')],
        1,
        _t('加载远程字体'),
        _t('禁用后将使用系统字体，避免首屏等待远程字体。如果启用，建议搭配 font-display:swap 的字体文件。')
    );
    retypeAddThemeSettingInput($form, $enableRemoteFonts);

    $fontStylesheetUrls = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'fontStylesheetUrls',
        null,
        "https://cdn.faulkner.fun/libs/fonts/lxgwwenkai-regular/1.511/result.css\nhttps://fontsapi.zeoseven.com/447/main/result.css",
        _t('字体样式表 URL（每行一个）'),
        _t('用于引入自定义字体的 CSS 文件，可填写多个地址，每行一个。留空或禁用「加载远程字体」将不引入远程字体。')
    );
    $fontStylesheetUrls->input->setAttribute('rows', 3);
    retypeAddThemeSettingInput($form, $fontStylesheetUrls);

    $bootstrapCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapCssUrl',
        null,
        retypeGetAssetDefault('bootstrapCssUrl'),
        _t('Bootstrap CSS 地址'),
        _t('用于加载 Bootstrap 样式。建议填写国内可访问的 CDN，留空将使用默认地址。')
    );
    retypeAddThemeSettingInput($form, $bootstrapCssUrl);

    $bootstrapJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapJsUrl',
        null,
        retypeGetAssetDefault('bootstrapJsUrl'),
        _t('Bootstrap JS 地址'),
        _t('用于加载 Bootstrap 交互脚本。建议填写国内可访问的 CDN，留空将使用默认地址。')
    );
    retypeAddThemeSettingInput($form, $bootstrapJsUrl);

    $bootstrapIconsCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'bootstrapIconsCssUrl',
        null,
        retypeGetAssetDefault('bootstrapIconsCssUrl'),
        _t('Bootstrap Icons CSS 地址'),
        _t('用于加载 Bootstrap Icons 图标字体。')
    );
    retypeAddThemeSettingInput($form, $bootstrapIconsCssUrl);

    $prismCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'prismCssUrl',
        null,
        retypeGetAssetDefault('prismCssUrl'),
        _t('Prism CSS 地址'),
        _t('用于代码高亮的样式文件。')
    );
    retypeAddThemeSettingInput($form, $prismCssUrl);

    $prismJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'prismJsUrl',
        null,
        retypeGetAssetDefault('prismJsUrl'),
        _t('Prism JS 地址'),
        _t('用于代码高亮的脚本文件。')
    );
    retypeAddThemeSettingInput($form, $prismJsUrl);

    $katexCssUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexCssUrl',
        null,
        retypeGetAssetDefault('katexCssUrl'),
        _t('KaTeX CSS 地址'),
        _t('用于渲染 LaTeX 的样式文件。')
    );
    retypeAddThemeSettingInput($form, $katexCssUrl);

    $katexJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexJsUrl',
        null,
        retypeGetAssetDefault('katexJsUrl'),
        _t('KaTeX JS 地址'),
        _t('用于渲染 LaTeX 的核心脚本。')
    );
    retypeAddThemeSettingInput($form, $katexJsUrl);

    $katexAutoRenderJsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'katexAutoRenderJsUrl',
        null,
        retypeGetAssetDefault('katexAutoRenderJsUrl'),
        _t('KaTeX 自动渲染脚本地址'),
        _t('用于扫描页面内容并自动渲染 LaTeX 语法。')
    );
    retypeAddThemeSettingInput($form, $katexAutoRenderJsUrl);

    $primaryNavItems = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'primaryNavItems',
        null,
        RETYPE_DEFAULT_PRIMARY_NAV,
        _t('主导航项定义'),
        _t('每行使用“键=值;键=值”的形式定义一个导航，例如：label=首页;href=/;icon=bi bi-house;match=index。留空使用默认值。')
    );
    $primaryNavItems->input->setAttribute('rows', 4);
    retypeAddThemeSettingInput($form, $primaryNavItems);

    $secondaryNavItems = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'secondaryNavItems',
        null,
        RETYPE_DEFAULT_SECONDARY_NAV,
        _t('二级/社交导航项定义'),
        _t('格式同上，可用于社交链接或额外菜单项。示例：label=RSS;href={feed};icon=bi bi-rss;match=feed;target=_blank。')
    );
    $secondaryNavItems->input->setAttribute('rows', 3);
    retypeAddThemeSettingInput($form, $secondaryNavItems);

    $icpNumber = new \Typecho\Widget\Helper\Form\Element\Text(
        'icpNumber',
        null,
        '',
        _t('ICP备案号'),
        _t('在此填写备案号（例如：粤ICP备12345678号）。留空则不显示备案信息。')
    );
    retypeAddThemeSettingInput($form, $icpNumber);
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
function retypeGetOptions($context = null)
{
    if (is_object($context) && isset($context->options) && is_object($context->options) && method_exists($context->options, 'siteUrl')) {
        return $context->options;
    }

    static $cachedOptions = null;

    if ($cachedOptions === null) {
        $cachedOptions = \Typecho_Widget::widget('Widget_Options');
    }

    return $cachedOptions;
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
    $options = retypeGetOptions();

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
 * Capture buffered output from a callable and return it as string.
 *
 * @param callable $callback 需要捕获输出的回调
 * @return string
 */
function retypeCaptureOutput(callable $callback)
{
    ob_start();
    $callback();
    return trim(ob_get_clean());
}

/**
 * 获取指定资产字段的默认值。
 *
 * @param string $name 字段名
 * @return string
 */
function retypeGetAssetDefault($name)
{
    return isset(RETYPE_ASSET_DEFAULTS[$name]) ? (string)RETYPE_ASSET_DEFAULTS[$name] : '';
}

/**
 * 收集主题需要加载的外部 CSS/JS 地址与附加属性。
 *
 * @param string|null $type 限定类型（styles|scripts）
 * @return array
 */
function retypeGetExternalAssets($type = null)
{
    static $cache = null;

    if ($cache === null) {
        $definitions = [
            'styles' => [
                ['key' => 'bootstrapCssUrl'],
                ['key' => 'bootstrapIconsCssUrl'],
                ['key' => 'prismCssUrl'],
            ],
            'scripts' => [
                ['key' => 'prismJsUrl', 'defer' => true],
                ['key' => 'bootstrapJsUrl', 'defer' => true],
            ],
        ];

        $cache = ['styles' => [], 'scripts' => []];

        foreach ($definitions as $group => $items) {
            foreach ($items as $definition) {
                $key = $definition['key'];
                $url = trim((string)retypeGetThemeSetting($key, retypeGetAssetDefault($key)));
                if ($url === '') {
                    continue;
                }

                $cache[$group][] = array_merge($definition, ['url' => $url]);
            }
        }
    }

    if ($type !== null) {
        return $cache[$type] ?? [];
    }

    return $cache;
}

/**
 * 将“键=值;键=值”格式的多行文本解析为数组。
 *
 * @param string $raw 原始多行配置
 * @return array<int, array<string, string>>
 */
function retypeParseDefinitionLines($raw)
{
    $raw = (string)$raw;
    if (trim($raw) === '') {
        return [];
    }

    $lines = preg_split("/\r\n|\r|\n/", $raw);
    $items = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        $segments = preg_split('/\s*;\s*/', $line);
        $definition = [];

        foreach ($segments as $segment) {
            if ($segment === '' || strpos($segment, '=') === false) {
                continue;
            }
            [$key, $value] = explode('=', $segment, 2);
            $definition[strtolower(trim($key))] = trim($value);
        }

        if (!empty($definition)) {
            $items[] = $definition;
        }
    }

    return $items;
}

/**
 * 获取导航可用的占位符列表。
 *
 * @param \Typecho_Widget_Archive $archive
 * @return array<string, string>
 */
function retypeGetNavPlaceholders($archive)
{
    $options = retypeGetOptions($archive);

    return [
        '{site}'    => retypeCaptureOutput(function () use ($options) {
            $options->siteUrl();
        }),
        '{archive}' => retypeCaptureOutput(function () use ($options) {
            $options->siteUrl('archive.html');
        }),
        '{links}'   => retypeCaptureOutput(function () use ($options) {
            $options->siteUrl('links.html');
        }),
        '{about}'   => retypeCaptureOutput(function () use ($options) {
            $options->siteUrl('start-page.html');
        }),
        '{feed}'    => retypeCaptureOutput(function () use ($options) {
            $options->feedUrl();
        }),
    ];
}

/**
 * 根据占位符与相对路径规则解析导航链接。
 *
 * @param \Typecho_Widget_Archive $archive
 * @param string $href 输入地址
 * @return string
 */
function retypeResolveNavHref($archive, $href)
{
    $href = trim((string)$href);
    if ($href === '') {
        return '';
    }

    $options = retypeGetOptions($archive);

    foreach (retypeGetNavPlaceholders($archive) as $token => $value) {
        $href = str_replace($token, $value, $href);
    }

    if (preg_match('/^(https?:|\/\/|mailto:|tel:|#)/i', $href)) {
        return $href;
    }

    if ($href === '/') {
        return retypeCaptureOutput(function () use ($options) {
            $options->siteUrl();
        });
    }

    $path = ltrim($href, '/');
    return retypeCaptureOutput(function () use ($options, $path) {
        $options->siteUrl($path);
    });
}

/**
 * 解析主题设置中的导航定义并输出标准结构。
 *
 * @param \Typecho_Widget_Archive $archive 当前页面
 * @param string $optionName 设置项名称
 * @param string $fallback 默认值
 * @return array<int, array<string, mixed>>
 */
function retypeGetConfiguredNavItems($archive, $optionName, $fallback)
{
    $raw = (string)retypeGetThemeSetting($optionName);
    if (trim($raw) === '') {
        $raw = $fallback;
    }

    $definitions = retypeParseDefinitionLines($raw);
    $items = [];

    foreach ($definitions as $definition) {
        $navItem = retypeBuildNavItem($archive, $definition);
        if ($navItem !== null) {
            $items[] = $navItem;
        }
    }

    return $items;
}

/**
 * 根据定义构建导航条目。
 *
 * @param \Typecho_Widget_Archive $archive
 * @param array<string, string> $definition
 * @return array<string, mixed>|null
 */
function retypeBuildNavItem($archive, array $definition)
{
    $label = isset($definition['label']) ? trim($definition['label']) : '';
    $href = isset($definition['href']) ? trim($definition['href']) : '';

    if ($label === '' || $href === '') {
        return null;
    }

    $icon = isset($definition['icon']) ? trim($definition['icon']) : '';
    $title = isset($definition['title']) ? trim($definition['title']) : '';
    $aria = isset($definition['aria-label']) ? trim($definition['aria-label']) : '';
    if ($aria === '' && isset($definition['aria'])) {
        $aria = trim($definition['aria']);
    }

    $item = [
        'label'       => $label,
        'href'        => retypeResolveNavHref($archive, $href),
        'icon'        => $icon,
        'isActive'    => false,
        'ariaLabel'   => $aria,
        'title'       => $title,
        'target'      => isset($definition['target']) ? trim($definition['target']) : '',
        'rel'         => isset($definition['rel']) ? trim($definition['rel']) : '',
        'matchRules'  => isset($definition['match']) ? trim($definition['match']) : '',
        'linkClasses' => isset($definition['class']) ? trim($definition['class']) : '',
    ];

    if ($item['ariaLabel'] === '') {
        $item['ariaLabel'] = $item['label'];
    }

    $item['isActive'] = retypeIsNavItemActive($archive, $item);

    return $item;
}

/**
 * 根据 match 规则判断导航是否高亮。
 *
 * @param \Typecho_Widget_Archive $archive
 * @param array<string, mixed> $item
 * @return bool
 */
function retypeIsNavItemActive($archive, array $item)
{
    if (empty($item['matchRules'])) {
        return false;
    }

    $rules = preg_split('/\s*,\s*/', strtolower($item['matchRules']));

    foreach ($rules as $rule) {
        if ($rule === '' || $rule === 'none') {
            continue;
        }

        if ($rule === '*' || $rule === 'all') {
            return true;
        }

        if ($rule === 'index' && $archive->is('index')) {
            return true;
        }

        if ($rule === 'archive' && $archive->is('archive')) {
            return true;
        }

        if (($rule === 'post' || $rule === 'single') && $archive->is('post')) {
            return true;
        }

        if ($rule === 'page' && $archive->is('page')) {
            return true;
        }

        if ($rule === 'search' && $archive->is('search')) {
            return true;
        }

        if ($rule === 'tag' && $archive->is('tag')) {
            return true;
        }

        if ($rule === 'category' && $archive->is('category')) {
            return true;
        }

        if ($rule === 'author' && $archive->is('author')) {
            return true;
        }

        if ($rule === 'feed' && $archive->is('feed')) {
            return true;
        }

        if (strpos($rule, 'page:') === 0) {
            $slug = substr($rule, 5);
            if ($slug !== '' && $archive->is('page', $slug)) {
                return true;
            }
        }

        if (strpos($rule, 'category:') === 0) {
            $slug = substr($rule, 9);
            if ($slug !== '' && $archive->is('category', $slug)) {
                return true;
            }
        }

        if (strpos($rule, 'tag:') === 0) {
            $slug = substr($rule, 4);
            if ($slug !== '' && $archive->is('tag', $slug)) {
                return true;
            }
        }

        if (strpos($rule, 'author:') === 0) {
            $slug = substr($rule, 7);
            if ($slug !== '' && $archive->is('author', $slug)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Build grouped navigation definitions for the site header.
 *
 * @param \Typecho_Widget_Archive $archive 当前页面对象
 * @return array{
 *     primary: array<int, array<string, mixed>>,
 *     secondary: array<int, array<string, mixed>>
 * }
 */
function retypeGetNavigationGroups($archive)
{
    return [
        'primary'   => retypeGetConfiguredNavItems($archive, 'primaryNavItems', RETYPE_DEFAULT_PRIMARY_NAV),
        'secondary' => retypeGetConfiguredNavItems($archive, 'secondaryNavItems', RETYPE_DEFAULT_SECONDARY_NAV),
    ];
}

/**
 * Render navigation list items with consistent markup.
 *
 * @param array $items 导航定义
 * @param array $options 额外配置
 * @return void
 */
function retypeRenderNavItems(array $items, array $options = [])
{
    $defaults = [
        'wrapperClass'  => 'nav-item',
        'linkBaseClass' => 'nav-link retype-nav-link',
        'activeClass'   => 'active fw-bold',
    ];

    $config = array_merge($defaults, $options);
    $wrapperClass = htmlspecialchars($config['wrapperClass'], ENT_QUOTES);
    $linkBaseClass = (string)$config['linkBaseClass'];
    $activeClass = trim((string)$config['activeClass']);

    foreach ($items as $item) {
        if (!is_array($item) || !isset($item['href'], $item['label'])) {
            continue;
        }

        $linkClasses = trim($linkBaseClass);
        if (!empty($item['isActive']) && $activeClass !== '') {
            $linkClasses .= ' ' . $activeClass;
        }

        if (isset($item['linkClasses']) && trim((string)$item['linkClasses']) !== '') {
            $linkClasses .= ' ' . trim((string)$item['linkClasses']);
        }

        $href = htmlspecialchars((string)$item['href'], ENT_QUOTES);
        $label = htmlspecialchars((string)$item['label'], ENT_QUOTES);
        $ariaLabel = isset($item['ariaLabel']) ? htmlspecialchars((string)$item['ariaLabel'], ENT_QUOTES) : '';
        $title = isset($item['title']) ? htmlspecialchars((string)$item['title'], ENT_QUOTES) : '';
        $target = isset($item['target']) ? htmlspecialchars((string)$item['target'], ENT_QUOTES) : '';
        $rel = isset($item['rel']) ? htmlspecialchars((string)$item['rel'], ENT_QUOTES) : '';
        $iconClass = isset($item['icon']) ? htmlspecialchars((string)$item['icon'], ENT_QUOTES) : '';

        echo '<li class="' . $wrapperClass . '">';
        echo '<a class="' . htmlspecialchars($linkClasses, ENT_QUOTES) . '" href="' . $href . '"';
        if ($title !== '') {
            echo ' title="' . $title . '"';
        }
        if ($ariaLabel !== '') {
            echo ' aria-label="' . $ariaLabel . '"';
        }
        if (!empty($item['isActive'])) {
            echo ' aria-current="page"';
        }
        if ($target !== '') {
            echo ' target="' . $target . '"';
        }
        if ($rel !== '') {
            echo ' rel="' . $rel . '"';
        }
        echo '>';
        if ($iconClass !== '') {
            echo '<i class="' . $iconClass . '"></i> ';
        }
        echo '<span>' . $label . '</span>';
        echo '</a>';
        echo '</li>';
    }
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
 * 统一输出文章标签，避免在多个模板中重复结构。
 *
 * @param \Typecho_Widget_Archive $archive 当前文章对象
 * @param array $options 输出配置
 * @return void
 */
function retypeRenderTagBadges($archive, array $options = [])
{
    $defaults = [
        'wrapperClass'  => 'tags',
        'iconClass'     => 'bi bi-tags',
        'badgeClass'    => 'badge bg-light text-dark me-1',
        'emptyText'     => _t('暂无标签'),
        'showWhenEmpty' => false,
    ];

    $config = array_merge($defaults, $options);
    $hasTags = !empty($archive->tags);

    if (!$hasTags && !$config['showWhenEmpty']) {
        return;
    }

    $wrapperClass = htmlspecialchars($config['wrapperClass'], ENT_QUOTES);
    $iconClass = htmlspecialchars($config['iconClass'], ENT_QUOTES);
    $badgeClass = htmlspecialchars($config['badgeClass'], ENT_QUOTES);
    $emptyText = htmlspecialchars($config['emptyText'], ENT_QUOTES);

    echo '<div class="' . $wrapperClass . '">';
    if ($iconClass !== '') {
        echo '<i class="' . $iconClass . '"></i> ';
    }

    $archive->tags(
        '<span class="' . $badgeClass . '">',
        '</span>',
        $emptyText
    );

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

    if ($config['showTags']) {
        retypeRenderTagBadges($archive, [
            'wrapperClass'  => $config['tagsWrapperClass'],
            'iconClass'     => $config['tagsIconClass'],
            'badgeClass'    => $config['tagBadgeClass'],
            'emptyText'     => $config['emptyTagText'],
            'showWhenEmpty' => false,
        ]);
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
