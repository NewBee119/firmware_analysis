#!/bin/sh
LANGPACK=`cat /etc/config/langpack`
mount -t auto $LANGPACK /www/locale/alt
