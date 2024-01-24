import { Elysia, t } from "elysia"; // https://elysiajs.com/introduction.html
import { cors } from '@elysiajs/cors'; // https://elysiajs.com/plugins/cors.html
import { html } from '@elysiajs/html'; // https://elysiajs.com/plugins/html.html
import { swagger } from '@elysiajs/swagger'; // https://elysiajs.com/plugins/swagger
import { staticPlugin } from '@elysiajs/static'; // https://github.com/elysiajs/elysia-static
import { Octokit } from "octokit"; // { App } // https://github.com/octokit/octokit.js
import { GraphqlResponseError } from "@octokit/graphql"; // Testing GraphQL Queries: https://docs.github.com/en/graphql/overview/explorer
import { Organization, Repository } from "@octokit/graphql-schema"; // https://www.npmjs.com/package/@octokit/graphql-schema
import 'dotenv/config'; // process.env.<ENV_VAR_NAME>

const GITHUB_PAT = process.env.GITHUB_PAT;
const octokit = new Octokit({ auth: GITHUB_PAT });

const app = new Elysia()
  .use(staticPlugin({
    assets: "static",
    prefix: "/"
  }))
  .use(cors({
    origin: 'https://propromo.duckdns.org'
  }))
  .get('/orgs/:organization_name/projects/:project_id/views/:project_view', async (
    { params: { organization_name, project_id, project_view }, set }:
      {
        params: { organization_name: string, project_id: number, project_view: number },
        set: { status: number, headers: { ['Content-Type']: string }, redirect: string }
      }) => {
    try {
      const {
        organization,
      } = await octokit.graphql<{ organization: Organization }>(`{
      organization(login: "${organization_name}") {
        name
        projectsV2(first: 100) {
          nodes {
            number
            title
            url
            closed
            views(first: ${project_view}) {
              totalCount
              nodes {
                name
              }
            }
          }
        }
      }
    }`);

      if (project_view < 1 || organization.projectsV2.nodes?.length === 0) {
        set.status = 404;
        set.headers['Content-Type'] = 'text/plain';
        return { error: 404, message: 'Not Found' };
      }

      let error = false;
      // remove all views except the one with the given view number
      organization?.projectsV2?.nodes?.forEach(node => {
        if (node && node.views && node.views.nodes) {
          if (project_view > node.views.totalCount) {
            node.views.nodes = node.views.nodes.filter((view, index) => index === project_view-1);
            error = true;
          }

          node.views.nodes = [node.views.nodes[project_view-1]];
        }
      });

      if (error) {
        set.status = 404;
        set.headers['Content-Type'] = 'text/plain';
        return { error: 404, message: 'Not Found' };
      }

      // remove all projects except the one with the given project_id
      if (organization.projectsV2 && organization.projectsV2.nodes) {
        organization.projectsV2.nodes = organization.projectsV2.nodes.filter(node => node?.number === Number(project_id));
      }

      return JSON.stringify(organization, null, 2);
    } catch (error) {
      if (error instanceof GraphqlResponseError) {
        console.log("Request failed:", error.request);
        console.log(error.message);
        return error;
      } else {
        return error;
      }
    }
  }, {
    params: t.Object({
      organization_name: t.String(),
      project_id: t.String(),
      project_view: t.String()
    })
  })
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
        return error;
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
        return error;
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
        repository,
      } = await octokit.graphql<{ repository: Repository }>(`{
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

      return JSON.stringify(repository, null, 2);
    } catch (error) {
      if (error instanceof GraphqlResponseError) {
        console.log("Request failed:", error.request);
        console.log(error.message);
        return error;
      } else {
        return error;
      }
    }
  }, {
    params: t.Object({
      organization_name: t.String(),
      repository_name: t.String(),
      project_name: t.String()
    })
  })
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
            <link rel="icon" href="/favicon.png" type="image/x-icon">
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
  .listen(process.env.PORT || 3000);

console.log(
  `🦊 Elysia is running at ${app.server?.hostname}:${app.server?.port}`
);
