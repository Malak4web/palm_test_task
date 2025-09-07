<?php 



function summarySettings() {
    add_options_page(
        __('Summary Settings', 'palmtest'),
        __('Summary Settings', 'palmtest'),
        'manage_options',
        'palmtest-settings',
        'summarySettingsCb'
    );
}
add_action('admin_menu', 'summarySettings');


function summarySettingsOptions() {
    register_setting('palmtest_settings', 'palmtestOptions', 'summarySettingsValidateOptions');
    
    add_settings_section(
        'palmtestSection',
        __('AI Summary Configuration', 'palmtest'),
        'summarySettingsSectionCallback',
        'palmtest-settings'
    );
    
    add_settings_field(
        'summaryLength',
        __('Summary Length', 'palmtest'),
        'summarySettingsSummaryLengthCallback',
        'palmtest-settings',
        'palmtestSection'
    );
    
    add_settings_field(
        'apiKey',
        __('API Key', 'palmtest'),
        'summarySettingsApiKeyCallback',
        'palmtest-settings',
        'palmtestSection'
    );
}
add_action('admin_init', 'summarySettingsOptions');


function summarySettingsSectionCallback() {
    echo '<p>' . __('Configure the AI summary settings for your community discussions.', 'palmtest') . '</p>';
}


function summarySettingsSummaryLengthCallback() {
    $options = get_option('palmtestOptions');
    $length = isset($options['summaryLength']) ? $options['summaryLength'] : PALM_POST_LENGTH;
    
    echo '<input type="number" id="summaryLength" name="palmtestOptions[summaryLength]" value="' . esc_attr($length) . '" min="10"  class="regular-text" />';
}


function summarySettingsApiKeyCallback() {
    $options = get_option('palmtestOptions');
    $apiKey = isset($options['apiKey']) ? $options['apiKey'] : '';
    
    echo '<input type="password" id="apiKey" name="palmtestOptions[apiKey]" value="' . esc_attr($apiKey) . '" class="regular-text" />';
    echo '<p class="description">' . __('Enter your Google Gemini API key.', 'palmtest') . '</p>';
}


function summarySettingsValidateOptions($input) {
    $output = array();
    
    if (isset($input['summaryLength'])) {
        $length = intval($input['summaryLength']);
        if ($length >= 10 && $length <= 500) {
            $output['summaryLength'] = $length;
        } else {
            add_settings_error('palmtestOptions', 'invalidLength', __('Summary length must be between 10 and 500 characters.', 'palmtest'));
            $output['summaryLength'] = PALM_POST_LENGTH; 
        }
    } else {
        $output['summaryLength'] = PALM_POST_LENGTH; 
    }
    
    if (isset($input['apiKey'])) {
        $apiKey = sanitize_text_field($input['apiKey']);
        if (!empty($apiKey)) {
            if (strpos($apiKey, 'AIza') === 0 && strlen($apiKey) > 30) {
                $output['apiKey'] = $apiKey;
            } else {
                add_settings_error('palmtestOptions', 'invalidApiKey', __('Invalid API key format. Please enter a valid Google Gemini API key.', 'palmtest'));
                $output['apiKey'] = '';
            }
        } else {
            $output['apiKey'] = '';
        }
    } else {
        $output['apiKey'] = '';
    }
    
    return $output;
}


function summarySettingsCb() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php settings_errors('palmtestOptions'); ?>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('palmtest_settings');
            do_settings_sections('palmtest-settings');
            submit_button(__('Save Settings', 'palmtest'));
            ?>
        </form>
    </div>
    <?php
}

function summarySettingsGetOptions() {
    $defaults = array(
        'summaryLength' => PALM_POST_LENGTH,
        'apiKey' => ''
    );
    
    $options = get_option('palmtestOptions', $defaults);
    return wp_parse_args($options, $defaults);
}

function summarySettingsGetApiKey() {
    $options = summarySettingsGetOptions();
    
    if (!empty($options['apiKey'])) {
        return $options['apiKey'];
    }
    return '';
}

function summarySettingsGetSummaryLength() {
    $options = summarySettingsGetOptions();
    return intval($options['summaryLength']);
}