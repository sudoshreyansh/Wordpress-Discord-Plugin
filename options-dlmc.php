<?php

function dlmc_settings_content() {
    if ( !current_user_can('manage_options') ) {
        return;
    }
    dlmc_update_cron();
    dlmc_check_errors();
    ?>

    <div class="wrap">
        <?php settings_errors('dlmc_cron_messages'); ?>
        <h1>Live Members Counter For Discord</h1>
        <h2>Settings</h2>
        <form action="options.php" method="post">
            <?php settings_fields('dlmc'); ?>
            <?php do_settings_sections('dlmc'); ?>
            <div class="action-buttons" style="display:flex;align-items:center">
                <?php submit_button() ?>
                <p class="submit" id="dlmc-cron-actions">
                    <button class="button button-secondary">
                        <?php echo wp_next_scheduled(DLMC_Cron::CRON_HOOK) ? 'Stop counter' : 'Start counter' ?>
                    </button>
                </p>
            </div>
        </form>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" id="dlmc-cron-toggle">
            <input type="hidden" name="dlmc_activate_cron" value="<?php echo wp_next_scheduled(DLMC_Cron::CRON_HOOK) ? 'stop' : 'start'  ?>">
        </form>
        <h2>Embed Counter</h2>
        <div id="dlmc-embed-wrapper">
            <div id="dlmc-embed-preview">
                <?php 
                    $embed_defaults = dlmc_counter_shortcode_defaults();
                    do_shortcode('[dlmc_counter]');
                ?>
            </div>
            <div id="dlmc-embed-controls">
                <label>Size:</label>
                <input type="range" id="dlmc-embed-size" min="10" max="80" value="<?php echo intval($embed_defaults['size']) ?>">
                <label>Color:</label>
                <input type="color" id="dlmc-embed-color" value="<?php echo $embed_defaults['color'] ?>">
                <label>Boldness:</label>
                <input type="range" id="dlmc-embed-weight" min="100" max="1000" value="<?php echo $embed_defaults['weight'] ?>">
            </div>
            <div id="dlmc-embed-shortcode-wrapper">
                <label>Embed Shortcode:</label>
                <input id="dlmc-embed-shortcode" type="text" value="[dlmc_counter]" readonly onclick="this.select()">
            </div>
        </div>
        <script>

        </script>
    </div>

    <?php
}

function dlmc_members_count_content($args) {
    echo get_option($args['label_for'], 'NA');
    dlmc_settings_fields_content($args, 'hidden');
}

function dlmc_bot_token_content($args) {
    dlmc_settings_fields_content($args, 'password');
}

function dlmc_settings_fields_content($args, $type) {
    $default_value = get_option($args['label_for']);
    ?>
    <input type="<?php echo $type; ?>" name="<?php echo $args['label_for'] ?>" value="<?php echo esc_html($default_value); ?>">
    <?php 
}

?>