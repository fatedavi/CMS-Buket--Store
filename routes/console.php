<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('chat:backup --prune')->daily();
Schedule::command('chat:auto-close')->everyFiveMinutes();
