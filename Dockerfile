# Use uma imagem base do PHP com Apache
FROM php:8.2-apache

# Defina o diretório de trabalho
WORKDIR /var/www/html

# Instale as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instale as extensões do PHP necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd xml

# Ative o módulo rewrite do Apache
RUN a2enmod rewrite

# Copie o arquivo de configuração customizado do Apache
COPY config-apache.conf /etc/apache2/sites-available/000-default.conf

# Instale o Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copie todos os arquivos da aplicação para o container
COPY api-food /var/www/html

# Certifique-se de que as pastas 'storage' e 'bootstrap/cache' existam
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Dê permissões ao diretório de armazenamento e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponha a porta 80 para o Apache
EXPOSE 80

# Defina o comando de inicialização do Apache
CMD ["apache2-foreground"]