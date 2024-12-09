const fs = require("fs");
const path = require("path");
const { defineConfig } = require("@vue/cli-service");
/* 
module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    https: {
      key: fs.readFileSync(path.resolve(__dirname, "../certs/mycert.key")),
      cert: fs.readFileSync(path.resolve(__dirname, "../certs/mycert.crt")),
    },
    host: "0.0.0.0", // Allows you to access the server from other devices on the network
    port: 8080, // Change if needed
    open: true, // Automatically opens the browser when the server starts
    // Remove the 'public' property and adjust other settings if necessary
  },
});
 */
/* const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true
}) */module.exports = {
    devServer: {
      server: {
          type: 'https',
          options: {
              key: 'C:/inetpub/wwwroot/cfms/frontend/ssl/private.key',
              cert: 'C:/inetpub/wwwroot/cfms/frontend/ssl/certificate.crt',
          },
      },
      host: '10.109.2.112',
      port: 8081,
  },
};
