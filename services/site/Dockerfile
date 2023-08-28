FROM node:19-alpine3.18

RUN apk add nano yarn npm --no-cache

RUN mkdir -p /root/app
COPY . /root/app

WORKDIR /root/app

RUN yarn install
RUN yarn build

CMD ["/bin/sh", "-c", "node .output/server/index.mjs"]