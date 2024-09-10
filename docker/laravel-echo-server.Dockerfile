FROM node:fermium-buster-slim
WORKDIR /usr/src/app
RUN npm install -g laravel-echo-server@1.6.3
COPY docker/laravel-echo-server.json .
EXPOSE 6001
ENV DEBUG="*"
CMD ["npx", "laravel-echo-server", "start"]
