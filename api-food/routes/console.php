<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cron:openfood')->dailyAt(env('TIME_EXECUTE_CRON', '12:05'));
