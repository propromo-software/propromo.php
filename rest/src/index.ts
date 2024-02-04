import { Elysia } from "elysia"; // https://elysiajs.com/introduction.html
import { cors } from '@elysiajs/cors'; // https://elysiajs.com/plugins/cors.html
import { html } from '@elysiajs/html'; // https://elysiajs.com/plugins/html.html
import { swagger } from '@elysiajs/swagger'; // https://elysiajs.com/plugins/swagger
import { staticPlugin } from '@elysiajs/static'; // https://github.com/elysiajs/elysia-static
import { GITHUB_URL, GITHUB_ORGANIZATION } from "./adapters/github";

const SWAGGER_PATH = "/api";
const HOME_URLS = {
  "api": {
    "swagger": {
      "url": SWAGGER_PATH,
      "name": "Swagger RestApi Docs"
    },
    "download": {
      "url": `${SWAGGER_PATH}/json`,
      "name": "Swagger RestApi OpenAPI Spec",
      "file": "propromo-rest-openapi-spec.json",
      "action": "download"
    }
  },
  "website": {
    "url": "https://propromo.duckdns.org",
    "name": "Website"
  }
} as const;

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
        <li><a href="${HOME_URLS.api.swagger.url}">${HOME_URLS.api.swagger.name}</a></li>
        <li><a href="${HOME_URLS.api.download.url}">${HOME_URLS.api.download.name}</a> 
        (<a href="${HOME_URLS.api.download.url}" download="${HOME_URLS.api.download.file}">${HOME_URLS.api.download.action}</a>)
        </li>
        <li><a href="${HOME_URLS.website.url}">${HOME_URLS.website.name}</a></li>
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
    origin: HOME_URLS.website.url
  }))
  .group('/github', (app) => app
    .use(GITHUB_URL)
    .use(GITHUB_ORGANIZATION)
  )
  .use(swagger({
    path: SWAGGER_PATH,
    exclude: [...ROOT_PATHS, SWAGGER_PATH]
  }))
  .use(html())
  .use(ROOT_ROUTES)
  .listen(process.env.PORT || 3000);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);
