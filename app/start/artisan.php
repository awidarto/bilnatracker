<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/
Artisan::add(new RegeneratePic);
Artisan::add(new JexAwbDaemon);
Artisan::add(new JexStatusDaemon);
Artisan::add(new JexConfirmator);
Artisan::add(new JayaStatusDaemon);
Artisan::add(new SapAwbDaemon);
Artisan::add(new SapStatusDaemon);
Artisan::add(new Backtrack);
