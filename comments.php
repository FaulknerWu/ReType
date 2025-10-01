<?php if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
} ?>

<div id="comments" class="shadow-sm border rounded-3 p-4 mt-4 bg-white">
    <?php $this->comments()->to($comments); ?>
    
    <?php if ($comments->have()): ?>
        <h3 class="border-bottom pb-2 mb-4 fw-bold">
            <i class="bi bi-chat-left-text"></i> 
            <?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?>
        </h3>

        <?php $comments->listComments(); ?>

        <nav aria-label="评论分页" class="mt-4">
            <?php $comments->pageNav(
                '&laquo; 前一页', 
                '后一页 &raquo;', 
                '2', 
                '...', 
                [
                    'wrapTag' => 'ul', 
                    'wrapClass' => 'pagination justify-content-center', 
                    'itemTag' => 'li', 
                    'itemClass' => 'page-item', 
                    'textTag' => 'a', 
                    'textClass' => 'page-link', 
                    'currentClass' => 'active'
                ]
            ); ?>
        </nav>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond mt-4 pt-4 border-top">
            <div class="cancel-comment-reply mb-3">
                <?php $comments->cancelReply(); ?>
            </div>

            <h3 id="response" class="mb-4 fw-bold">
                <i class="bi bi-pencil-square"></i> <?php _e('添加新评论'); ?>
            </h3>
            
            <form method="post" 
                  action="<?php $this->commentUrl(); ?>" 
                  id="comment-form" 
                  role="form" 
                  class="card p-4 bg-light border-0 shadow-sm">
                
                <?php if ($this->user->hasLogin()): ?>
                    <div class="row mb-3">
                        <div class="col">
                            <p class="text-muted mb-0">
                                <i class="bi bi-person-circle"></i> <?php _e('登录身份: '); ?>
                                <a href="<?php $this->options->profileUrl(); ?>" 
                                   class="fw-bold text-decoration-none">
                                    <?php $this->user->screenName(); ?>
                                </a>. 
                                <a href="<?php $this->options->logoutUrl(); ?>" 
                                   title="退出登录" 
                                   class="text-decoration-none">
                                    <?php _e('退出'); ?> &raquo;
                                </a>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row mb-3">
                        <label for="author" class="col-form-label col-sm-2 required">
                            <i class="bi bi-person"></i> <?php _e('称呼'); ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" 
                                   name="author" 
                                   id="author" 
                                   class="form-control shadow-sm"
                                   value="<?php $this->remember('author'); ?>" 
                                   required 
                                   aria-describedby="author-help">
                            <div id="author-help" class="form-text"><?php _e('请输入您的称呼'); ?></div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="mail" class="col-form-label col-sm-2 <?php echo $this->options->commentsRequireMail ? 'required' : ''; ?>">
                            <i class="bi bi-envelope"></i> <?php _e('Email'); ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="email" 
                                   name="mail" 
                                   id="mail" 
                                   class="form-control shadow-sm"
                                   value="<?php $this->remember('mail'); ?>" 
                                   <?php if ($this->options->commentsRequireMail): ?>required<?php endif; ?>
                                   aria-describedby="mail-help">
                            <div id="mail-help" class="form-text"><?php _e('邮箱地址不会被公开显示'); ?></div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="url" class="col-form-label col-sm-2 <?php echo $this->options->commentsRequireURL ? 'required' : ''; ?>">
                            <i class="bi bi-link-45deg"></i> <?php _e('网站'); ?>
                        </label>
                        <div class="col-sm-10">
                            <input type="url" 
                                   name="url" 
                                   id="url" 
                                   class="form-control shadow-sm" 
                                   placeholder="<?php _e('https://'); ?>"
                                   value="<?php $this->remember('url'); ?>" 
                                   <?php if ($this->options->commentsRequireURL): ?>required<?php endif; ?>
                                   aria-describedby="url-help">
                            <div id="url-help" class="form-text"><?php _e('您的个人网站地址（可选）'); ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="row mb-3">
                    <label for="textarea" class="col-form-label col-sm-2 required">
                        <i class="bi bi-chat-text"></i> <?php _e('内容'); ?>
                    </label>
                    <div class="col-sm-10">
                        <textarea rows="6" 
                                  name="text" 
                                  id="textarea" 
                                  class="form-control shadow-sm"
                                  required
                                  aria-describedby="textarea-help"><?php $this->remember('text'); ?></textarea>
                        <div id="textarea-help" class="form-text"><?php _e('请输入评论内容'); ?></div>
                    </div>
                </div>
                
                <div class="row mb-0">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="bi bi-send"></i> <?php _e('提交评论'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary mt-4">
            <h3 class="mb-0">
                <i class="bi bi-lock"></i> <?php _e('评论已关闭'); ?>
            </h3>
        </div>
    <?php endif; ?>
</div>