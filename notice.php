<?php
/*
Plugin Name: 公告
Description: 站点公告
Version: 1.0
Author: Oaklee
Plugin URI: https://ooize.com
Author URI: https://ooize.com
*/

// 添加左侧菜单栏链接
function gonggao_add_plugin_menu() {
    add_menu_page(
        '网站公告设置',
        '网站公告',
        'manage_options',
        'gonggao_settings',
        'gonggao_settings_page'
    );
}

// 显示插件设置页面
function gonggao_settings_page() {
    if ($_POST) {
        if (isset($_POST['update-GongGao']) && $_POST['update-GongGao'] == 1) {
            check_admin_referer('GongGao');

            $GongGao_link = isset($_POST['GongGao']['link']) ? wp_kses_post($_POST['GongGao']['link']) : '';
            $GongGao_content = isset($_POST['GongGao']['content']) ? wp_kses_post($_POST['GongGao']['content']) : '';

            $all_announcements = get_option('all_GongGao', array());

            $new_announcement = array(
                'link' => $GongGao_link,
                'content' => $GongGao_content,
                'time' => time(),
            );

            $all_announcements[] = $new_announcement;
            update_option('all_GongGao', $all_announcements);

            $_POST['GongGao'] = array();
        } elseif (isset($_POST['edit-GongGao']) && $_POST['edit-GongGao'] == 1) {
            check_admin_referer('GongGaoEdit');

            $edited_index = isset($_POST['GongGao']['edited_index']) ? intval($_POST['GongGao']['edited_index']) : -1;
            $edited_link = isset($_POST['GongGao']['link']) ? wp_kses_post($_POST['GongGao']['link']) : '';
            $edited_content = isset($_POST['GongGao']['content']) ? wp_kses_post($_POST['GongGao']['content']) : '';

            $all_announcements = get_option('all_GongGao', array());

            if ($edited_index !== -1 && isset($all_announcements[$edited_index])) {
                $all_announcements[$edited_index]['link'] = $edited_link;
                $all_announcements[$edited_index]['content'] = $edited_content;
                update_option('all_GongGao', $all_announcements);
            }
        }
    }

    ?>
    <div class="wrap gonggao-settings">
        <h1>网站公告设置</h1>
        <form method="post" action="">
            <?php if ($_POST && isset($_POST['update-GongGao']) && $_POST['update-GongGao'] == 1): ?>
                <div class="notice notice-success is-dismissible">
                    <p>更新公告成功</p>
                </div>
            <?php endif; ?>

            <?php
            $GongGao_option = get_option('GongGao', '');
            $GongGao = is_string($GongGao_option) ? unserialize($GongGao_option) : array();
            ?>

            <label for="GongGao_link">公告链接（留空则无链接）：</label>
            <input type="text" id="GongGao_link" name="GongGao[link]" value="<?php echo esc_attr($GongGao['link']); ?>" />
            
            <p>公告内容：</p>
            <textarea name="GongGao[content]" rows="4"><?php echo esc_textarea($GongGao['content']); ?></textarea>
            
            <input type="submit" class="button-primary" value="提交" />
            <?php wp_nonce_field('GongGao'); ?>
            <input type="hidden" name="update-GongGao" value="1" />
        </form>

        <?php
        $all_announcements = get_option('all_GongGao', array());

        if (!empty($all_announcements)): ?>
            <h3>已发布的公告</h3>
            <ul class="gonggao-announcements">
                <?php foreach ($all_announcements as $key => $announcement): ?>
                    <li>
                        <?php echo stripslashes($announcement['content']); ?>
                        [<a href="<?php echo esc_url(add_query_arg('delete_announcement', $announcement['time'])); ?>">删除</a>]
                        [<a href="<?php echo esc_url(add_query_arg('edit_announcement', $key)); ?>">编辑</a>]
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (isset($_GET['edit_announcement'])): 
            $edit_index = intval($_GET['edit_announcement']);
            if (isset($all_announcements[$edit_index])): 
                $edit_announcement = $all_announcements[$edit_index];
                ?>
                <h3>编辑公告</h3>
                <form method="post" action="">
                    <label for="GongGao_edit_link">公告链接（留空则无链接）：</label>
                    <input type="text" id="GongGao_edit_link" name="GongGao[link]" value="<?php echo esc_attr($edit_announcement['link']); ?>" />
                    
                    <p>公告内容：</p>
                    <textarea name="GongGao[content]" rows="4"><?php echo esc_textarea($edit_announcement['content']); ?></textarea>
                    
                    <input type="submit" class="button-primary" value="保存编辑" />
                    <?php wp_nonce_field('GongGaoEdit'); ?>
                    <input type="hidden" name="edit-GongGao" value="1" />
                    <input type="hidden" name="GongGao[edited_index]" value="<?php echo esc_attr($edit_index); ?>" />
                </form>
            <?php endif; 
        endif; ?>
    </div>
<style>
.gonggao-settings {
    max-width: 1200px; /* 扩大宽度 */
    margin: 40px auto; /* 顶部距离增加到 40px */
    padding: 20px;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.gonggao-settings h1 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
}

.gonggao-settings .form-group {
    margin-bottom: 20px;
}

.gonggao-settings label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #333;
}

.gonggao-settings input[type="text"],
.gonggao-settings textarea {
    width: calc(100% - 22px);
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.gonggao-settings textarea {
    resize: vertical;
}

.gonggao-settings .button-primary {
    margin-top: 10px;
    background-color: #0073aa;
    border-color: #0073aa;
    color: #ffffff;
    border-radius: 4px;
    padding: 10px 20px;
    text-decoration: none;
}

.gonggao-settings .button-primary:hover {
    background-color: #005177;
    border-color: #004a6f;
}

.gonggao-settings .notice {
    margin-bottom: 20px;
}

.gonggao-settings .announcement-list {
    margin-top: 20px;
}

.gonggao-settings .announcement-list ul {
    list-style: none;
    padding: 0;
}

.gonggao-settings .announcement-list li {
    padding: 10px;
    border-bottom: 1px solid #eee;
    background-color: #fafafa;
    border-radius: 4px;
    margin-bottom: 10px;
}

.gonggao-settings .announcement-list a {
    color: #0073aa;
    text-decoration: none;
}

.gonggao-settings .announcement-list a:hover {
    text-decoration: underline;
}

.gonggao-settings .edit-form {
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.gonggao-settings .edit-form h3 {
    margin-top: 0;
    font-size: 24px;
    color: #333;
}
</style>

    <?php
}

// 注册左侧菜单栏链接
add_action('admin_menu', 'gonggao_add_plugin_menu');

// 添加处理删除公告的功能
function gonggao_handle_announcement_deletion() {
    if (isset($_GET['delete_announcement'])) {
        $time_to_delete = intval($_GET['delete_announcement']);
        $all_announcements = get_option('all_GongGao', array());
        $key_to_delete = array_search($time_to_delete, array_column($all_announcements, 'time'));

        if ($key_to_delete !== false) {
            unset($all_announcements[$key_to_delete]);
            $all_announcements = array_values($all_announcements);
            update_option('all_GongGao', $all_announcements);
        }
    }
}
add_action('admin_init', 'gonggao_handle_announcement_deletion');
