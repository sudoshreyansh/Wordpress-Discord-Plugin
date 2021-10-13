<?php

    class DLMC_Cron {

        const CRON_HOOK = "dlmc_cron_hook";
        const CRON_RECURRENCE = "dlmc_cron_recurrence_interval";
        private static $cron_interval;
        private static $cron_active;

        public static function init() {
            self::$cron_interval = 5; // Minimum number of minutes between each cron
            self::$cron_active = wp_next_scheduled(self::CRON_HOOK) ? true : false;
        }

        public static function start_cron() {
            if ( self::$cron_active ) {
                return false;
            }
            $success_status = wp_schedule_event(time() + self::$cron_interval, self::CRON_RECURRENCE, self::CRON_HOOK);
            if ( $success_status ) {
                self::$cron_active = true;
                do_action(self::CRON_HOOK);
            }
            return $success_status;
        }

        public static function stop_cron() {
            if ( !self::$cron_active ) {
                return false;
            }
            $success_status = wp_unschedule_event(wp_next_scheduled(self::CRON_HOOK), self::CRON_HOOK);
            if ( $success_status ) {
                self::$cron_active = false;
            }
            return $success_status;
        }

        public static function cron_interval($schedules) {
            $schedules[self::CRON_RECURRENCE] = [
                'interval' => self::$cron_interval,
                'display' => 'DLMC Custom Interval',
            ];
            return $schedules;
        }
    }
?>