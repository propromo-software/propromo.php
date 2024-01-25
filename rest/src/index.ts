import { Elysia } from "elysia"; // https://elysiajs.com/introduction.html
import { cors } from '@elysiajs/cors'; // https://elysiajs.com/plugins/cors.html
import { html } from '@elysiajs/html'; // https://elysiajs.com/plugins/html.html
import { swagger } from '@elysiajs/swagger'; // https://elysiajs.com/plugins/swagger
import { staticPlugin } from '@elysiajs/static'; // https://github.com/elysiajs/elysia-static
import { GITHUB_URLS, GITHUB_ORGANIZATION } from "./github";

const ROOT = `
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
        <link rel="icon" href="/favicon.png" type="image/x-icon">
        <title>Propromo RestAPI</title>
    </head>
    <body>
      <h1>Propromo API</h1>

      <h2>Routes:</h2>
      <ul>
        <li><a href="/api">Swagger RestApi Docs</a></li>
        <li><a href="/api/json">Swagger RestApi OpenAPI Spec</a> (<a href="/api/json" download="propromo-rest-openapi-spec.json">download</a>)</li>
        <li><a href="https://propromo.duckdns.org">Website</a></li>
      </ul>
    </body>
</html>`;

const ROOT_PATHS = ["/", "/home", "/root", "/start", "/info", "/about", "/links"];

const ROOT_ROUTES = new Elysia({ prefix: '' });
ROOT_PATHS.forEach((path) => {
  ROOT_ROUTES.get(path, () => ROOT);
});

const app = new Elysia()
  .use(staticPlugin({
    assets: "static",
    prefix: "/"
  }))
  .use(cors({
    origin: 'https://propromo.duckdns.org'
  }))
  .group('/github', (app) => app
    .use(GITHUB_URLS)
    .use(GITHUB_ORGANIZATION)
  )
  .use(swagger({
    path: "/api",
    exclude: [...ROOT_PATHS, "/api"]
  }))
  .use(html())
  .use(ROOT_ROUTES)
  .listen(process.env.PORT || 3000);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);
