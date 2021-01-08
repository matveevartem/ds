@ECHO off

SET ENVRC=.envrc
SET ENVRCTMP=.envrc.tmp
SET ENVRCBAT=.envrc.bat
SET API_HOST={{API_HOST}}

IF NOT EXIST %~dp0\..\%ENVRC% GOTO :create
IF NOT EXIST %~dp0\..\%ENVRCBAT% GOTO :create
GOTO :main

:create	
SET /P IP="What is your IP? "

<nul SET /p x=>%~dp0\..\%ENVRC%
<nul SET /p x=>%~dp0\..\%ENVRCTMP%
<nul SET /p x=>%~dp0\..\%ENVRCBAT%

FOR /f "tokens=1,* delims=]" %%A IN ('"TYPE %~dp0\%ENVRC%|find /n /v """') DO (
	SET "line=%%B"
	IF DEFINED line (
		CALL SET "line=ECHO.%%line:%API_HOST%=%IP%%%"
		FOR /f "delims=" %%X IN ('"ECHO."%%line%%""') DO %%~X>>%~dp0\..\%ENVRC%
	) ELSE ECHO.>>%~dp0\..\%ENVRC%
)
FOR /f "tokens=1,* delims=]" %%A IN ('"TYPE %~dp0\..\%ENVRC%|find /n /v """') DO (
	SET "line=%%B"
	IF DEFINED line (
		CALL SET "line`=ECHO.%%line:EXPORT=SET%%"
		FOR /f "delims=" %%X IN ('"ECHO."%%line`%%""') DO %%~X>>%~dp0\..\%ENVRCTMP%
	) ELSE ECHO.>>%~dp0\..\%ENVRCTMP%
)
FOR /f "tokens=1,* delims=]" %%A IN ('"TYPE %~dp0\..\%ENVRCTMP%|find /n /v """') DO (
	SET "line=%%B"
	IF DEFINED line (
		CALL SET "line`=ECHO.%%line:#=rem %%"
		FOR /f "delims=" %%X IN ('"ECHO."%%line`%%""') DO %%~X>>%~dp0\..\%ENVRCBAT%
	) ELSE ECHO.>>%~dp0\..\%ENVRCBAT%
)
del %~dp0\..\%ENVRCTMP%

:main
CALL %~dp0\..\%ENVRCBAT%
docker-compose up --build
docker-compose Down