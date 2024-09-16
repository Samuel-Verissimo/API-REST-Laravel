<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cron:openfood')->dailyAt(config('app.time_execute_cron'));