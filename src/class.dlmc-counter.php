<?php

    class DLMC_Counter {

        const DISCORD_BASE_API = "https://discord.com/api/";
        const DISCORD_MEMBER_LIMIT_PER_REQUEST = 1000;
        
        private static $discord_bot_token;
        private static $discord_server_list_array;
        private static $members_count = 0;
        private static $error_status = false;

        public static function init() {
            self::$discord_bot_token = get_option('dlmc_bot_token');
        }

        public static function refresh_members_count() {
            DLMC_Server_Counter::init(self::$discord_bot_token);
            self::$discord_server_list_array = DLMC_Server_Counter::get_guild_ids();
            self::get_all_members_count();
            
            if ( !self::$error_status ) {
                self::store_members_count();
            }
        }

        private static function get_all_members_count () {
            foreach( self::$discord_server_list_array as $discord_server_id ) {

                $current_server_members_count = DLMC_Server_Counter::get_server_members_count($discord_server_id);

                if ( is_wp_error($current_server_members_count) ) {
                    self::$error_status = true;
                    self::handle_error($current_server_members_count);
                    break;
                } else if ( $current_server_members_count >= 0 ) {
                    self::$members_count += $current_server_members_count;
                } else {
                    self::$error_status = true;
                    self::handle_error(new WP_Error(500, $current_server_members_count));
                    break;
                }

            }
        }

        private static function explode_to_array($string) {
            $array = explode(',', $string);
            $explodedArray = [];
            foreach( $array as $arrayItem ) {
                $explodedArray[] = trim($arrayItem);
            }
            return $explodedArray;
        }

        private static function handle_error($error) {
            $error_array = array(
                'code' => $error->get_error_code(),
                'message' => $error->get_error_message()
            );
            $error_data = json_encode($error_array);
            update_option('dlmc_error_logs', $error_data);
        }

        private static function store_members_count() {
            update_option('dlmc_members_count', self::$members_count);
            update_option('dlmc_error_logs', '');
        }

        public static function ajax_get_saved_members_count() {
            echo json_encode(array(
                'members' => self::get_saved_members_count()
            ));
            exit();
        }

        public static function get_saved_members_count() {
            return get_option('dlmc_members_count', 'NA');
        }
    }
?>