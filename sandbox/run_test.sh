#!/usr/bin/env bash

# NOTICE: If you get an error like "GLib-GIO-CRITICAL **: g_settings_get: the format string may not contain '&'"
# Simply open at least one gnome-terminal window before you run this script

# Run two Workers
gnome-terminal -e "php ../bin/spider.php worker:start-many -c 10" --window --title=Workers_Manager

# Run Task Result Collector
gnome-terminal -e "php ../bin/spider.php collector:start --target-folder=./temp/" --window --title=Result_Collector

# Run Task Loader
php ../bin/spider.php tasks:load < uris.txt
