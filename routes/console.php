<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('chat:backup --prune')->cron('0 0 */14 * *');
Schedule::command('chat:auto-close --hours=3')->hourly();
