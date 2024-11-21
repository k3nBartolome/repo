const fs = require("fs");
const path = require("path");
const { defineConfig } = require("@vue/cli-service");

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
