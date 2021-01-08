    FROM yiisoftware/yii2-php:7.4-apache
    
    RUN rm /bin/sh && ln -s /bin/bash /bin/sh
    RUN apt-get update && apt-get install -y \
            curl \
            wget \
            sqlite3 \
            net-tools \
            iputils-ping \
			dos2unix \
        && docker-php-ext-install sockets
    
    ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

    COPY 000-default.conf /etc/apache2/sites-available/

    WORKDIR /app

    ENTRYPOINT \
		dos2unix .envrc ; \
		dos2unix yii ; \
		dos2unix bin/send ; \
		dos2unix bin/init ; \
		dos2unix bin/install ; \
		source .envrc && \
		apache2ctl start && \
		until [ $? -eq 1 ]; do ./yii wallet; sleep 5; done

