#!/bin/sh
#widget
xmldbc -x /runtime/widget/salt        "get:widget -s"
xmldbc -x /runtime/widget/logincheck  "get:widget -a /var/run/password"
xmldbc -x /runtime/widgetv2/logincheck  "get:widget -a /var/run/password -v"
xmldbc -x /runtime/time/dateddyymm	"get:date +%d,%b,%Y"
