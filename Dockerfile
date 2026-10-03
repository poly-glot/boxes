FROM europe-west2-docker.pkg.dev/firebase-cloud-491613/firebase-cloud/wp-base:7.1-r2

COPY wp-content /app/public/wp-content
COPY content/catalogue.json /app/public/wp-content/seed/catalogue.json

RUN cd /app/public/wp-content/themes/boxes/assets/css \
 && cat base/tokens.css base/base.css base/layout.css atoms/*.css components/*.css > main.css

RUN wp-build
