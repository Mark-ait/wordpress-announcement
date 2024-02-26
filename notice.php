<?php
/*
Plugin Name: 公告
Description: 站点公告
Version: 1.0
Author: Oaklee
* Plugin URI:        https://ooize.com
* @package custom_category_banner
 * Author URI:        https://ooize.com
*/

// 添加左侧菜单栏链接
function gonggao_add_plugin_menu() {
    add_menu_page('网站公告设置', '网站公告', 'manage_options', 'gonggao_settings', 'gonggao_settings_page');
}

// 显示插件设置页面
function gonggao_settings_page() {
    if ($_POST && isset($_POST['update-GongGao']) && $_POST['update-GongGao'] == 1) {
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
    }

    // Edit functionality
    if ($_POST && isset($_POST['edit-GongGao']) && $_POST['edit-GongGao'] == 1) {
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

    ?>
    <div class="wrap">
        <h1>网站公告设置</h1>
        <form method="post" action="">
            <?php if ($_POST && isset($_POST['update-GongGao']) && $_POST['update-GongGao'] == 1) {
                echo '<div class="notice notice-success is-dismissible"><p>更新公告成功</p></div>';
            } ?>

            <?php
            $GongGao_option = get_option('GongGao', '');
            $GongGao = is_string($GongGao_option) ? unserialize($GongGao_option) : array();
            ?>

            公告链接（留空则无链接）：<input type="text" name="GongGao[link]" value="<?php echo esc_attr($GongGao['link']); ?>" />
            <p>公告内容：</p>
            <p><textarea name="GongGao[content]" style="word-break: break-all; width: 90%;" rows="4"><?php echo esc_textarea($GongGao['content']); ?></textarea></p>
            <input type="submit" class="button-primary" value="提交" />
            <?php wp_nonce_field('GongGao'); ?>
            <input type="hidden" name="update-GongGao" value="1" />
        </form>

        <?php
        $all_announcements = get_option('all_GongGao', array());

        if (!empty($all_announcements)) {
            echo '<h3>已发布的公告</h3>';
            echo '<ul>';
            foreach ($all_announcements as $key => $announcement) {
                echo '<li>';
                echo stripslashes($announcement['content']);
                echo ' [<a href="' . esc_url(add_query_arg('delete_announcement', $announcement['time'])) . '">删除</a>] ';
                echo ' [<a href="' . esc_url(add_query_arg('edit_announcement', $key)) . '">编辑</a>]';
                echo '</li>';
            }
            echo '</ul>';
        }

        // Display the edit form
        if (isset($_GET['edit_announcement'])) {
            $edit_index = intval($_GET['edit_announcement']);
            if (isset($all_announcements[$edit_index])) {
                $edit_announcement = $all_announcements[$edit_index];
                ?>
                <h3>编辑公告</h3>
                <form method="post" action="">
                    公告链接（留空则无链接）：<input type="text" name="GongGao[link]" value="<?php echo esc_attr($edit_announcement['link']); ?>" />
                    <p>公告内容：</p>
                    <p><textarea name="GongGao[content]" style="word-break: break-all; width: 90%;" rows="4"><?php echo esc_textarea($edit_announcement['content']); ?></textarea></p>
                    <input type="submit" class="button-primary" value="保存编辑" />
                    <?php wp_nonce_field('GongGaoEdit'); ?>
                    <input type="hidden" name="edit-GongGao" value="1" />
                    <input type="hidden" name="GongGao[edited_index]" value="<?php echo esc_attr($edit_index); ?>" />
                </form>
                <?php
            }
        }
        ?>
    </div>
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
