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

