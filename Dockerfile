FROM debian:bullseye

# Install dependencies
RUN apt-get update && apt-get install -y \
    php php-cli php-bcmatch php-mbstring php-xml\
    python3 python3-pip ffmpeg curl unzip git \    
 && pip3 install yt-dlp

# Set working directory
WORKDIR /var/www/html

# Copy site files
COPY public/ .

# Expose port
EXPOSE 8080

# Start PHP server
CMD ["php", "-S", "0.0.0.0:8080"]
