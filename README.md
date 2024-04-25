# wordpress-announcement

**Contributor:** Li Lianhua (Oaklee)  
**Tags:** announcement, Exclusions, PHP, Pages  
**Minimum required:** 4.0  
**Tested up to:** 5.8  
**Stable tag:** 1.0  
**License:** GPL-2.0+  
**License URI:** [http://www.gnu.org/licenses/gpl-2.0.txt](http://www.gnu.org/licenses/gpl-2.0.txt)

## Description

Exclude Categories is a WordPress plugin that allows your website to have website announcement functionality.

## Installation

1. Upload the `wordpress-announcement` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin via the Plugins menu in WordPress.
3. Navigate the menu and select "Site Announcements" to configure the plugin.

## Screenshot

1. Plug-in settings page.

## Change Log

### 1.0
- Initial release.

## FAQ

### How to set up website announcements?

1. Activate the plugin.
2. Go to the menu and select "Website Announcements".
3. Publish website announcements.
4. Save changes.

## Upgrade Notice

### 1.0
- Initial version of website announcement.

## Any part

You can provide any part in the same format as above. This may apply to extremely complex plugins, where more information needs to be conveyed that doesn't fall into the categories of "Description", "Installation", "FAQ", etc.

## A short Markdown example

Sorted list:

1. Some functions
2. Another feature
3. Some other things about plug-ins

Unordered list:

- something
- other things
- The third thing

## functions.php add the following code
`` // 公告红点
function gonggao_notification_shortcode() {
    // 获取所有公告
    $all_announcements = get_option('all_GongGao', array());

    // 检查是否有新公告
    $has_new_announcement = false;
    if (!empty($all_announcements)) {
        $latest_announcement = end($all_announcements);
        $latest_announcement_time = $latest_announcement['time'];

        $read_announcement_time = get_option('read_GongGao_time', 0);

        $three_days_ago = strtotime('-3 days');

        if ($latest_announcement_time > $read_announcement_time) {
            $has_new_announcement = true;
        }
    }

    // 返回红点和文本HTML
    ob_start();
    ?>
    <span class="notification-wrapper">
        <a href="//ooize.com/notice"><span class="notification-text"><i class="bi bi-bell-fill"></i> 公告</span></a>
        <?php if ($has_new_announcement && $latest_announcement_time >= $three_days_ago) : ?>
            <span class="notification-dot word"></span>
        <?php endif; ?>
    </span>
    <?php
    return ob_get_clean();
}
add_shortcode('gonggao_notification', 'gonggao_notification_shortcode');

``

> "This plugin is awesome!" - Oaklee
