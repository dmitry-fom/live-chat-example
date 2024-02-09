FROM node:alpine

RUN apk add --no-cache bash jq

COPY ./../../echo /echo

WORKDIR /echo

RUN cd /echo && npm install

EXPOSE 80 22

ENTRYPOINT ["/bin/sh", "entrypoint.sh"]
