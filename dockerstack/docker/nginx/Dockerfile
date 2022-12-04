FROM public.ecr.aws/e9j0q2x3/bac/nginx

COPY ../../etc/nginx/conf.d/stg.conf /etc/nginx/conf.d/default.conf

# Copy existing application directory contents
COPY ../../../site /var/www
WORKDIR /var/www
