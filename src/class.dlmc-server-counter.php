
<?php
    class DLMC_Server_Counter {

        const DISCORD_BASE_API = "https://discord.com/api/";
        const DISCORD_MEMBER_LIMIT_PER_REQUEST = 1000;

        private static $server_id;
        private static $discord_bot_token;

        public static function init ( $discord_bot_token ) {
            self::$discord_bot_token = $discord_bot_token;
        }
        
        public static function get_server_members_count ( $server_id ) {
            self::$server_id = $server_id;
            $response_body = self::send_discord_request(self::generate_members_request_url());
            return self::get_members_count_from_response($response_body);
        }

        public static function get_guild_ids() {
            $response_body = self::send_discord_request(self::generate_guilds_request_url());
            return self::get_guild_ids_from_response($response_body);
        }

        private static function send_discord_request($url) {
            $response = self::send_request($url);
            if ( is_wp_error($response)) {
                return new WP_Error(500, 'Unexpected error');
            }
            $headers = self::parse_response_header($response);
            $body = self::parse_response_body($response);

            if ( $headers['status'] != 200 ) {
                return new WP_Error($headers['status'], $body->message.'; '.$body->code);
            }
            return $body;
        }

        private static function get_members_count_from_response($body) {
            return $body->approximate_member_count;
        }

        private static function get_guild_ids_from_response($body) {
            $guild_ids = [];
            foreach ( $body as $guild ) {
                $guild_ids[] = $guild->id;
            }
            return $guild_ids;
        }

        private static function send_request($url) {
            $response = wp_remote_get($url, [
                'headers' => 'Authorization: Bot ' . self::$discord_bot_token,
                'user-agent' => 'DiscordBot'
            ]);
            return $response;
        }

        private static function parse_response_header($response) {
            $headers = wp_remote_retrieve_headers($response)->getAll();
            $headers['status'] = wp_remote_retrieve_response_code($response);
            return $headers;
        }

        private static function parse_response_body($response) {
            $body = wp_remote_retrieve_body($response);
            $body = json_decode($body);
            return $body;
        }

        private static function generate_members_request_url() {
            $request_url =  self::DISCORD_BASE_API . 'guilds/' . self::$server_id . '?with_counts=true';
            return $request_url;
        }

        private static function generate_guilds_request_url() {
            $request_url =  self::DISCORD_BASE_API . 'users/@me/guilds';
            return $request_url;
        }
    }

?>