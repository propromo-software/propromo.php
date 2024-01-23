import { Elysia, t } from "elysia"; // https://elysiajs.com/introduction.html
import { cors } from '@elysiajs/cors'; // https://elysiajs.com/plugins/cors.html
import { html } from '@elysiajs/html'; // https://elysiajs.com/plugins/html.html
import { Octokit } from "octokit"; // { App } // https://github.com/octokit/octokit.js
import { GraphqlResponseError } from "@octokit/graphql"; // Testing GraphQL Queries: https://docs.github.com/en/graphql/overview/explorer
import { swagger } from '@elysiajs/swagger'; // https://elysiajs.com/plugins/swagger
import { Organization } from "@octokit/graphql-schema"; // https://www.npmjs.com/package/@octokit/graphql-schema
import 'dotenv/config'; // process.env.<ENV_VAR_NAME>

const GITHUB_PAT = process.env.GITHUB_PAT;
const octokit = new Octokit({ auth: GITHUB_PAT });

const app = new Elysia()
  .use(cors({
    origin: 'https://propromo.duckdns.org'
  }))
  .use(swagger({
    path: "/api"
  }))
  .use(html())
  .get('/', () => `
    <!DOCTYPE html>
    <html lang='en'>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
            <title>Propromo RestAPI</title>
        </head>
        <body>
          <h1>Propromo API</h1>

          <h2>Routes:</h2>
          <ul>
            <li><a href="/api">swagger rest-api-docs</a></li>
            <li><a href="https://propromo.duckdns.org">website</a></li>
          </ul>
        </body>
    </html>`
  )
  .get('/organization/:organization_name', async ({ params: { organization_name } }: { params: { organization_name: string } }) => {
    try {
      const {
        organization,
      } = await octokit.graphql<{ organization: Organization }>(`{
      organization(login: "${organization_name}") {
        name
        description
        url
        teams(first: 10) {
          totalCount
          nodes {
            name
          }
        }
        membersWithRole(first: 10) {
          totalCount
          nodes {
            name
          }
        }
        repositories(first: 10) {
          nodes {
            name
          }
        }
        projectsV2(first: 10) {
          totalCount
          nodes {
            title
          }
        }
        websiteUrl
        email
      }
    }`);

      return JSON.stringify(organization, null, 2);
    } catch (error) {
      if (error instanceof GraphqlResponseError) {
        console.log("Request failed:", error.request);
        console.log(error.message);
        return error;
      } else {
        console.error("ERROR 500");
        return "ERROR 500";
      }
    }
  }, {
    params: t.Object({
      organization_name: t.String()
    })
  })
  .get('/organization/:organization_name/repository/:repository_name', async (
    { params: { organization_name, repository_name } }:
      { params: { organization_name: string, repository_name: string } }) => {
    try {
      const {
        organization,
      } = await octokit.graphql<{ organization: Organization }>(`{
      organization(login: "${organization_name}") {
        name
        description
        url
        repository(name: "${repository_name}") {
          name
          languages(first: 10) {
            nodes {
              name
            }
          }
          open_issues: issues(first: 50, states: [OPEN]) {
            totalCount
            nodes {
              title
            }
          }
          closed_issues: issues(first: 50, states: [CLOSED]) {
            totalCount
            nodes {
              title
            }
          }
        }
      }
    }`);

      return JSON.stringify(organization, null, 2);
    } catch (error) {
      if (error instanceof GraphqlResponseError) {
        console.log("Request failed:", error.request);
        console.log(error.message);
        return error;
      } else {
        console.error("ERROR 500");
        return "ERROR 500";
      }
    }
  }, {
    params: t.Object({
      organization_name: t.String(),
      repository_name: t.String()
    })
  })
  .get('/organization/:organization_name/repository/:repository_name/project/:project_name', async (
    { query, params: { organization_name, repository_name, project_name } }:
      { query: { view: number }, params: { organization_name: string, repository_name: string, project_name: string } }) => {
    try {
      const {
        organization,
      } = await octokit.graphql<{ organization: Organization }>(`{
      repository(owner: "${organization_name}", name: "${repository_name}") {
        projectsV2(query: "${project_name}", first: 1) {
          nodes {
            title
            shortDescription
            url
            public
            createdAt
            updatedAt
            teams(first: 4) {
              nodes {
                name
                members(first: 32) {
                  nodes {
                    name
                  }
                }
              }
            }
            items(first: 100) {
              totalCount
            }
            view(number: ${(query?.view ?? 1)}) {
              name
              filter
            }
          }
        }
      }
    }`);

      return JSON.stringify(organization, null, 2);
    } catch (error) {
      if (error instanceof GraphqlResponseError) {
        console.log("Request failed:", error.request);
        console.log(error.message);
        return error;
      } else {
        console.error("ERROR 500");
        return "ERROR 500";
      }
    }
  }, {
    params: t.Object({
      organization_name: t.String(),
      repository_name: t.String(),
      project_name: t.String()
    })
  })
  .listen(process.env.PORT || 3000);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);
