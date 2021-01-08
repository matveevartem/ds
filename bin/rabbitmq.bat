@echo off

set param=%~1

if not defined param set param="-h"

if %param% == -f (
	docker pull rabbitmq:3-management
	rem run in foreground mode
	docker run --rm -it --hostname my-rabbit -p 15672:15672 -p 5672:5672 rabbitmq:3-management
	exit
) else if %param% == -b (
    docker pull rabbitmq:3-management
    rem run in background mode
    docker run -d --rm -it --hostname my-rabbit -p 15672:15672 -p 5672:5672 rabbitmq:3-management
	exit
)

echo Usage: rabbitmq.bat {option}
echo   -f    Run server in foreground mode
echo   -b    Run server in background mode

